<?php

namespace App\HttpFoundation;

use App\Exception\ConfigurationException;
use App\View\ViewBuilderInterface;
use App\HttpKernel\ControllerResultInterface;
use App\HttpKernel\ControllerResult;
use App\HttpKernel\ControllerException;
use App\Configuration\ControllerResultDataFetcherInterface;
use App\Configuration\ControllerResultDataAccessorInterface;
use App\Configuration\Route as RouteConfiguration;
use App\Configuration\Response as ResponseConfiguration;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\Routing\RouterInterface;

/**
 * The response builder.
 *
 * @version 0.0.1
 * @package App\HttpFoundation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ResponseBuilder implements ResponseBuilderInterface
{
    /**
     * @var App\View\ViewBuilderInterface[] $viewBuilders
     */
    private $viewBuilders;

    /**
     * @var App\Configuration\ControllerResultDataFetcherInterface $controllerResultDataFetcher
     */
    private $controllerResultDataFetcher;

    /**
     * @var Symfony\Component\Routing\RouterInterface $router
     */
    private $router;

    /**
     * Constructs the response builder.
     *
     * @param App\View\ViewBuilderInterface[] $viewBuilders
     * @param App\Configuration\ControllerResultDataFetcherInterface $controllerResultDataFetcher
     * @param Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(array $viewBuilders, ControllerResultDataFetcherInterface $controllerResultDataFetcher, RouterInterface $router)
    {
        $this->viewBuilders = $viewBuilders;
        $this->controllerResultDataFetcher = $controllerResultDataFetcher;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function build(Request $request, ControllerResultInterface $controllerResult): Response
    {
        if (empty($request->attributes->get('_response_configurations', []))) {
            throw new ConfigurationException(\sprintf(
                'Action "%s" must be configured in order for the response builder to build a response.',
                $request->attributes->get('_controller')
            ));
        }

        // Looks for the contextual response configuration...
        foreach ($request->attributes->get('_response_configurations') as $candidateResponseConfiguration) {
            if ($candidateResponseConfiguration instanceof ResponseConfiguration && $candidateResponseConfiguration->getStatusCode() === $controllerResult->getStatusCode()) {
                if (\in_array($candidateResponseConfiguration->getContent()->getFormat(), $request->getAcceptableContentTypes())) {
                    $responseConfiguration = $candidateResponseConfiguration;
                    break;
                }
            }
        }

        if (!isset($responseConfiguration)) {
            throw new ConfigurationException(\sprintf(
                'No "%s" found on action "%s" for the following context:%s
                    controller result status code: "%d"%s
                    request acceptable content types: "%s"
                ',
                ResponseConfiguration::class,
                $request->attributes->get('_controller'),
                \PHP_EOL,
                $controllerResult->getStatusCode(),
                \PHP_EOL,
                \implode(',', $request->getAcceptableContentTypes())
            ));
        }

        // Defines the response content...
        $viewConfiguration = $responseConfiguration->getContent();

        if (null !== $viewConfiguration->getBuilder()) {
            if (!isset($this->viewBuilders[$viewConfiguration->getBuilder()])) {
                throw new ConfigurationException(\sprintf(
                    'View builder "%s" is unknown to the response builder. Make sure that this view builder implements "%s" and is registered as a service tagged "%s".',
                    $viewConfiguration->getBuilder(),
                    ViewBuilderInterface::class,
                    'view_builder'
                ));
            }

            $viewBuilder = $this->viewBuilders[$viewConfiguration->getBuilder()];

            if (!$viewBuilder->supports($viewConfiguration)) {
                throw new ConfigurationException(\sprintf(
                    'View builder "%s" does not support the current view.',
                    $viewConfiguration->getBuilder()
                ));
            }
        } else {
            foreach ($this->viewBuilders as $candidateViewBuilder) {
                if ($candidateViewBuilder->supports($viewConfiguration)) {
                    $viewBuilder = $candidateViewBuilder;
                    break;
                }
            }

            if (!isset($viewBuilder)) {
                throw new ConfigurationException('None of the registered view builders support the current view.');
            }
        }

        $content = $viewBuilder->build($viewConfiguration, $controllerResult->getData());

        // Defines headers...
        $data = $controllerResult->getData();
        $headers = $responseConfiguration->getHeaders();
        foreach ($headers as $name => $value) {
            if (\is_string($value)) {
                continue;
            } elseif ($value instanceof ControllerResultDataAccessorInterface) {
                $headers[$name] = $this->controllerResultDataFetcher->fetch($value, $data);
            } elseif ($value instanceof RouteConfiguration) {
                $routeName = $value->getName();
                $routeParameters = $value->getParameters();

                foreach ($routeParameters as $key => $value) {
                    if (\is_string($value)) {
                        continue;
                    } elseif ($value instanceof ControllerResultDataAccessorInterface) {
                        $routeParameters[$key] = $this->controllerResultDataFetcher->fetch($value, $data);
                    }
                }

                $headers[$name] = $this->router->generate($routeName, $routeParameters);
            }
        }

        $headers['Content-Type'] = $responseConfiguration->getContent()->getFormat();

        // Defines status code...
        $statusCode = $controllerResult->getStatusCode();

        // Prepares the response...
        $response = new Response($content, $statusCode, $headers);
        $response->prepare($request);

        return $response;
    }
}
