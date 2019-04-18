<?php

namespace App\HttpFoundation;

use App\Exception\FlashGenerationException;
use App\Annotation\Flash as FlashAnnotation;
use App\Annotation\DatumGetterReference;
use App\Annotation\DatumPropertyReference;
use App\Annotation\DatumKeyReference;
use App\HttpKernel\ControllerResult;
use Symfony\Component\HttpFoundation\Request;
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
     * @var Symfony\Component\Translations\TranslatorItnerface $translator
     */
    private $translator;

    /**
     * Constructs the session manager.
     *
     * @param Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param Symfony\Component\HttpFoundation\Translation\TranslatorInterface $translator
     */
    public function __construct(SessionInterface $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function generateFlash(FlashAnnotation $flashAnnotation, ControllerResult $controllerResult)
    {
        $transId = $flashAnnotation->getMessage()->getId();
        $transParameters = $flashAnnotation->getMessage()->getParameters();
        $transDomain = $flashAnnotation->getMessage()->getDomain();
        $transLocale = $flashAnnotation->getMessage()->getLocale();

        $data = $controllerResult->getData();
        foreach ($transParameters as $key => $reference) {
            if ($reference instanceof DatumGetterReference) {
                if (!\is_callable([$data, $reference->getName()])) {
                    throw new FlashGenerationException(\sprintf(
                        'Controller result\'s data neither is an object or has a public method called "%s".',
                        $reference
                    ));
                }

                $transParameters[$key] = $data->{$reference}();
            } elseif ($reference instanceof DatumPropertyReference) {
                if (!\is_object($data) || !\property_exists($data, $reference->getName()) || !\array_key_exists($reference->getName(), \get_object_vars($data))) {
                    throw new FlashGenerationException(\sprintf(
                        'Controller result\'s data neither is an object or has a public property called "%s".',
                        $reference
                    ));
                }

                $transParameters[$key] = $data->{$reference};
            } elseif ($reference instanceof DatumKeyReference) {
                if (!\is_array($data) || !\array_key_exists($reference->getName(), $data)) {
                    throw new FlashGenerationException(\sprintf(
                        'Controller result\'s data neither is an array or has a key called "%s".',
                        $reference
                    ));
                }

                $transParameters[$key] = $data[$reference];
            }
        }

        $statusCode = $flashAnnotation->getStatusCode();
        $message = $this->translator->trans($transId, $transParameters, $transDomain, $transLocale);

        $this->session->getFlashBag()->add($statusCode, $message);
    }
}
