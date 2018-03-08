<?php

namespace App\HttpKernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Routing\RouterInterface;
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
     * @var Symfony\Component\Routing\RouterInterface $router
     */
    protected $router;

    /**
     * @var Twig_Environment $twig
     */
    protected $twig;

    /**
     * Constructs the response builder.
     *
     * @param array $parameters
     * @param Symfony\Component\Routing\RouterInterface $router
     * @param Twig_Environment $twig
     */
    public function __construct(array $parameters, RouterInterface $router, Twig_Environment $twig)
    {
        $this->parameters = $parameters;
        $this->router     = $router;
        $this->twig       = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildResponseFromGetResponseForControllerResultEvent(GetResponseForControllerResultEvent $event) : Response
    {
        $format = $event->getRequest()->getRequestFormat();
        $status = $event->getControllerResult()->getStatusCode();

        $parameters = $this->getRequestParameters($event->getRequest());

        if (isset($parameters[$format][$status]['redirection'])) {
            $redirection = $parameters[$format][$status]['redirection'];

        } elseif ($event->getControllerResult()->getRedirection()) {
            $redirection = $event->getControllerResult()->getRedirection();
        }

        if (isset($redirection)) {
            return new RedirectResponse(
                $this->router->generate($redirection),
                $status[0] === 3 ? $status : 302
            );
        }

        if (isset($parameters[$format][$status]['template'])) {
            $template = $parameters[$format][$status]['template'];
            $content  = $this->twig->render(
                $template,
                $event->getControllerResult()->getData()
            );

        } else {
            $content = $event->getControllerResult()->getData();
        }

        if ($format === 'html') {
            return new Response(
                $content,
                $status
            );

        } else {
            return new JsonResponse(
                $content,
                $status
            );
        }
    }

    /**
     * Gets the request parameters.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    private function getRequestParameters(Request $request) : array
    {
        $fqAction = $request->attributes->get('_controller');

        $controller = substr($fqAction, 0, strpos($fqAction, ':'));
        $controller = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $controller));

        $action = substr($fqAction, strpos($fqAction, ':') + 1);
        $action = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $action));

        return isset($this->parameters[$controller][$action])
            ? $this->parameters[$controller][$action]
            : array()
        ;
    }
}

