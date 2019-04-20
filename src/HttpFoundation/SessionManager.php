<?php

namespace App\HttpFoundation;

use App\HttpKernel\ControllerResultInterface;
use App\Annotation\Flash as FlashAnnotation;
use App\Annotation\DatumFetcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * The session manager.
 *
 * @version 0.0.1
 * @package App\HttpFoundation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class SessionManager implements SessionManagerInterface
{
    /**
     * @var Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    private $session;

    /**
     * @var App\Annotation\DatumFetcherInterface $datumFetcher
     */
    private $datumFetcher;

    /**
     * @var Symfony\Component\Translations\TranslatorItnerface $translator
     */
    private $translator;

    /**
     * Constructs the session manager.
     *
     * @param Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param App\Annotation\DatumFetcherInterface $datumFetcher
     * @param Symfony\Component\HttpFoundation\Translation\TranslatorInterface $translator
     */
    public function __construct(SessionInterface $session, DatumFetcherInterface $datumFetcher, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->datumFetcher = $datumFetcher;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function generateFlash(FlashAnnotation $flashAnnotation, ControllerResultInterface $controllerResult)
    {
        $transId = $flashAnnotation->getMessage()->getId();
        $transParameters = $flashAnnotation->getMessage()->getParameters();
        $transDomain = $flashAnnotation->getMessage()->getDomain();
        $transLocale = $flashAnnotation->getMessage()->getLocale();

        $data = $controllerResult->getData();
        foreach ($transParameters as $key => $reference) {
            $transParameters[$key] = $this->datumFetcher->fetch($reference, $data);
        }

        $statusCode = $flashAnnotation->getStatusCode();
        $message = $this->translator->trans($transId, $transParameters, $transDomain, $transLocale);

        $this->session->getFlashBag()->add($statusCode, $message);
    }
}
