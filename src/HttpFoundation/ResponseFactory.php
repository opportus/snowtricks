<?php

namespace App\HttpFoundation;

use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

/**
 * The response factory...
 *
 * @version 0.0.1
 * @package App\HttpKernel
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * @var array $parameters
     */
    private $parameters;

    /**
     * @var Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    private $requestStack;

    /**
     * @var Symfony\Component\Routing\RouterInterface $router
     */
    private $router;

    /**
     * @var Twig_Environment $twig
     */
    private $twig;

    /**
     * Constructs the response factory.
     *
     * @param array $parameters
     * @param Symfony\Component\Routing\RouterInterface $router
     * @param Twig_Environment $twig
     * @param Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function __construct(array $parameters, RouterInterface $router, Twig_Environment $twig, RequestStack $requestStack)
    {
        $this->parameters = $parameters;
        $this->router = $router;
        $this->twig = $twig;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse(Request $request, ControllerResultInterface $controllerResult) : Response
    {
        $parameters  = $this->resolveResponseParameters($request, $controllerResult);

        if (!empty($parameters['redirection']['route']['name'])) {
            $response = new RedirectResponse(
                $this->generateRoute(
                    $parameters['redirection']['route']['name'],
                    $parameters['redirection']['route']['parameters'],
                    $controllerResult
                ),
                $parameters['redirection']['status']
            );
        } else {
            if ($this->isAcceptedContentType('html', $request)) {
                $response = new Response(
                    $this->generateResponseContent($parameters, $request, $controllerResult),
                    $controllerResult->getStatusCode(),
                    $this->generateResponseHeaders($parameters, $controllerResult)
                );
            } elseif ($this->isAcceptedContentType('json', $request)) {
                $response = new JsonResponse(
                    $this->generateResponseContent($parameters, $request, $controllerResult),
                    $controllerResult->getStatusCode(),
                    $this->generateResponseHeaders($parameters, $controllerResult)
                );
            }
        }

        return $this->prepareResponse($response, $request, $controllerResult);
    }

    /**
     * Generates the content of the response.
     *
     * @param  array $parameters
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @param  App\HttpKernel\ControllerResultInterface $controllerResult
     * @return string
     */
    private function generateResponseContent(array $parameters, Request $request, ControllerResultInterface $controllerResult) : string
    {
        if ($parameters['template']) {
            $content = $this->twig->render(
                $parameters['template'],
                ['data' => $controllerResult->getData()]
            );
        } else {
            $content = '';
        }

        return $content;
    }

    /**
     * Generates the headers of the response.
     *
     * @param  array $parameters
     * @param  App\HttpKernel\ControllerResultInterface $controllerResult
     * @return array
     */
    private function generateResponseHeaders(array $parameters, ControllerResultInterface $controllerResult) : array
    {
        $headers = array();

        foreach ($parameters['headers'] as $name => $value) {
            if (isset($value['route']['name'])) {
                $routeName       = $value['route']['name'];
                $routeParameters = isset($value['route']['parameters'])
                    ? $value['route']['parameters']
                    : array()
                ;

                $value = $this->generateRoute(
                    $routeName,
                    $routeParameters,
                    $controllerResult
                );
            }

            $headers[ucfirst($name)] = $value;
        }

        return $headers;
    }

    /**
     * Generates a route.
     *
     * @param  string $name
     * @param  array $parameters
     * @param  App\HttpKernel\ControllerResultInterface $controllerResult
     * @return string
     */
    private function generateRoute(string $name, array $parameters, ControllerResultInterface $controllerResult) : string
    {
        if ($parameters && isset($controllerResult->getData()['entity'])) {
            $parameters = array_map(
                function ($property) {
                    $property = 'get' . ucfirst($property);
                    return (string) $controllerResult->getData()['entity']->$property();
                },
                $parameters
            );
        }

        return $this->router->generate($name, $parameters);
    }

    /**
     * Prepares the response.
     *
     * Comply to RFCs here...
     *
     * @param  Symfony\Component\HttpFoundation\Response $response
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @param  App\HttpKernel\ControllerResultInterface $controllerResult
     * @return Symfony\Component\HttpFoundation\Response
     */
    private function prepareResponse(Response $response, Request $request, ControllerResultInterface $controllerResult) : Response
    {
        return $response;
    }

    /**
     * Checks wether the given format is the requested content type.
     *
     * @param  string $type
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    private function isAcceptedContentType(string $type, Request $request) : bool
    {
        if (strpos($request->headers->get('accept'), $type) === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Resolves the response parameters.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @param  App\HttpKernel\ControllerResultInterface $controllerResult
     * @return array
     */
    private function resolveResponseParameters(Request $request, ControllerResultInterface $controllerResult) : array
    {
        $parameters = array();
        $action     = $request->attributes->get('_controller');
        $status     = $controllerResult->getStatusCode();

        if (isset($this->parameters[$action]['response'])) {
            foreach ($this->parameters[$action]['response'] as $params) {
                if ((isset($params['status']) &&
                    $params['status'] === $status) &&
                    ((isset($params['format']) &&
                    $this->isAcceptedContentType($params['format'], $request)) ||
                    ! isset($params['format']))
                ) {
                    $parameters = $params;

                    break;
                }
            }
        }

        $parametersResolver = new OptionsResolver();
        $parametersResolver->setDefaults(array(
            'format'      => null,
            'status'      => null,
            'template'    => null,
            'redirection' => array(),
            'headers'     => array()
        ));

        $redirectionParametersResolver = new OptionsResolver();
        $redirectionParametersResolver->setDefaults(array(
            'route'  => array(),
            'status' => 302
        ));

        $redirectionRouteParametersResolver = new OptionsResolver();
        $redirectionRouteParametersResolver->setDefaults(array(
            'name'       => null,
            'parameters' => array()
        ));

        $resolvedParameters = $parametersResolver->resolve($parameters);

        $redirectionParameters = isset($parameters['redirection']) ? $parameters['redirection'] : array();
        $redirectionParameters = $redirectionParametersResolver->resolve($redirectionParameters);

        $redirectionRouteParameters = isset($parameters['redirection']['route']) ? $parameters['redirection']['route'] : array();
        $redirectionRouteParameters = $redirectionRouteParametersResolver->resolve($redirectionRouteParameters);

        $resolvedParameters['redirection']          = $redirectionParameters;
        $resolvedParameters['redirection']['route'] = $redirectionRouteParameters;

        return $resolvedParameters;
    }
}
