<?php

namespace App\HttpFoundation;

use App\HttpKernel\ControllerResultInterface;
use App\Configuration\Flash as FlashConfiguration;
use App\Configuration\ControllerResultDataFetcherInterface;
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
     * @var App\Configuration\ControllerResultDataFetcherInterface $controllerResultDataFetcher
     */
    private $controllerResultDataFetcher;


    /**
     * @var Symfony\Component\Translations\TranslatorItnerface $translator
     */
    private $translator;

    /**
     * Constructs the session manager.
     *
     * @param Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param App\Configuration\ControllerResultDataFetcherInterface $controllerResultDataFetcher
     * @param Symfony\Component\HttpFoundation\Translation\TranslatorInterface $translator
     */
    public function __construct(SessionInterface $session, ControllerResultDataFetcherInterface $controllerResultDataFetcher, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->controllerResultDataFetcher = $controllerResultDataFetcher;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function generateFlash(FlashConfiguration $flashConfiguration, ControllerResultInterface $controllerResult)
    {
        $transId = $flashConfiguration->getMessage()->getId();
        $transParameters = $flashConfiguration->getMessage()->getParameters();
        $transDomain = $flashConfiguration->getMessage()->getDomain();
        $transLocale = $flashConfiguration->getMessage()->getLocale();

        $data = $controllerResult->getData();
        foreach ($transParameters as $key => $accessor) {
            $transParameters[$key] = $this->controllerResultDataFetcher->fetch($accessor, $data);
        }

        $message = $this->translator->trans($transId, $transParameters, $transDomain, $transLocale);
        $statusCode = $flashConfiguration->getStatusCode();

        $this->session->getFlashBag()->add($statusCode, $message);
    }
}
