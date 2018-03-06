<?php

namespace App\HttpKernel;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig_Environment;

/**
 * The response builder...
 *
 * @version 0.0.1
 * @package App\HttpKernel
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ResponseBuilder implements ResponseBuilderInterface
{
    /**
     * @var array $parameters
     */
    protected $parameters;

    /**
     * @var Twig_Environment $twig
     */
    protected $twig;

    /**
     * Constructs the response builder.
     *
     * @param array $parameters
     * @param Twig_Environment $twig
     */
    public function __construct(array $parameters, Twig_Environment $twig)
    {
        $this->parameters = $parameters;
        $this->twig       = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFromGetResponseForExceptionEvent(GetResponseForExceptionEvent $event) : Response
    {
        $status = $event->getException()->getStatusCode();
        $format = $event->getRequest()->getRequestFormat();

        if (isset($this->parameters[$action->getSnakeName()]['template'][$format])) {
            $template = $this->parameters[$action->getSnakeName()]['template'][$format];
        }

        if (isset($this->parameters[$action->getSnakeName()]['redirection'][$format][$status])) {
            $redirection = $this->parameters[$action->getSnakeName()]['redirection'][$format][$status];
        }

        // Redirect response...
        if (isset($redirection)) {
            $response = new RedirectResponse(
                $this->router->generate($redirection)
            );

        } elseif ($status === 303) {
            $response = new RedirectResponse(
                $action->getRequest()->headers->get('Location')
            );

        // HTML response...
        } elseif ($format === 'html') {
            if (isset($template)) {
                $response = new Response(
                    $this->twig->render(
                        $template,
                        $action->getOperationResults()
                    ),
                    $status
                );
            }

        // JSON response...
        } elseif ($format === 'json') {
            if (isset($template)) {
                $response = new JsonResponse(
                    array(
                        'html' => $this->twig->render(
                            $template,
                            $action->getOperationResults()
                        ),
                        'exception' => $action->getException(),
                    ),
                    $status
                );

            } else {
                // Serialize operation results to JSON...
            }
        }

        return $action->setResponse($response);
    }

    /**
     * {@inheritdoc}
     */
    public function buildFromGetResponseForControllerResultEvent(GetResponseForControllerResultEvent $event) : Response
    {

    }
}

