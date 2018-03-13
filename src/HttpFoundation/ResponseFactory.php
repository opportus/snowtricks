<?php

namespace App\HttpFoundation;

use App\HttpKernel\ControllerResultInterface;
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
     * Constructs the response factory.
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
    public function createResponse(Request $request, ControllerResultInterface $controllerResult) : Response
    {
        $parameters = $this->resolveResponseParameters($request, $controllerResult);

        if (! empty($parameters['redirection']['route']['name'])) {
            $response = new RedirectResponse(
                $this->generateRoute(
                    $parameters['route']['name'],
                    $parameters['route']['parameters'],
                    $controllerResult
                ),
                $parameters['redirection']['status']
            );

        } else {
            if ($request->getRequestFormat() === 'html') {
                $response = new Response(
                    $this->generateResponseContent($parameters, $request, $controllerResult),
                    $controllerResult->getStatusCode(),
                    $this->generateResponseHeaders($parameters, $controllerResult)
                );

            } elseif ($request->getRequestFormat() === 'json') {
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
    protected function generateResponseContent(array $parameters, Request $request, ControllerResultInterface $controllerResult) : string
    {
        if ($parameters['template']) {
            $content = $this->twig->render(
                $parameters['template'],
                $controllerResult->getData()
            );

        } else {
            $content = $controllerResult->getData();
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
    protected function generateResponseHeaders(array $parameters, ControllerResultInterface $controllerResult) : array
    {
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
    protected function generateRoute(string $name, array $parameters, ControllerResultInterface $controllerResult) : string
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
    protected function prepareResponse(Response $response, Request $request, ControllerResultInterface $controllerResult) : Response
    {
        return $response;
    }

    /**
     * Resolves the response parameters.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @param  App\HttpKernel\ControllerResultInterface $controllerResult
     * @return array
     */
    protected function resolveResponseParameters(Request $request, ControllerResultInterface $controllerResult) : array
    {
        if (empty($this->parameters)) {
            $parameters = array();

        } else {
            $action = $request->attributes->get('_controller');
            $format = $request->getRequestFormat();
            $status = $controllerResult->getStatusCode();

            $parameters = isset($this->parameters[$action][$format][$status]) ? $this->parameters[$action][$format][$status] : array();
        }

        $redirectionParameters = isset($parameters['redirection'])      ? $parameters['redirection']      : array();
        $routeParameters       = isset($redirectionParameters['route']) ? $redirectionParameters['route'] : array();

        $routeParametersResolver = new OptionsResolver();
        $routeParametersResolver->setDefaults(array(
            'name'       => null,
            'parameters' => array()
        ));

        $redirectionParametersResolver = new OptionsResolver();
        $redirectionParametersResolver->setDefaults(array(
            'route'  => $routeParametersResolver->resolve($routeParameters),
            'status' => 302
        ));

        $parametersResolver = new OptionsResolver();
        $parametersResolver->setDefaults(array(
            'template'    => null,
            'redirection' => $redirectionParametersResolver->resolve($redirectionParameters),
            'headers'     => array()
        ));

        return $parametersResolver->resolve($parameters);
    }
}

