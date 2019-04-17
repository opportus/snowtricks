<?php

namespace App\HttpFoundation;

use App\Exception\ResponseBuildingException;
use App\View\ViewBuilderInterface;
use App\HttpKernel\ControllerResult;
use App\Annotation\Response as ResponseAnnotation;
use App\Annotation\Route as RouteAnnotation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
     * @var Symfony\Component\Routing\RouterInterface $router
     */
    private $router;

    /**
     * Constructs the response builder.
     *
     * @param App\View\ViewBuilderInterface[] $viewBuilders
     * @param Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(array $viewBuilders, RouterInterface $router)
    {
        $this->viewBuilders = $viewBuilders;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function build(Request $request, ControllerResult $controllerResult): Response
    {
        if (empty($request->attributes->get('_response', []))) {
            throw new ResponseBuildingException(\sprintf(
                '"%s" must be annoted on action "%s" in order for the response builder to build a response.',
                ResponseAnnotation::class,
                $request->attributes->get('_controller')
            ));
        }

        // Looks for the contextual response annotations...
        foreach ($request->attributes->get('_response') as $candidateResponseAnnotation) {
            if ($candidateResponseAnnotation->getStatusCode() === $controllerResult->getStatusCode()) {
                if (null === $candidateResponseAnnotation->getContent() || \in_array($candidateResponseAnnotation->getContent()->getFormat(), $request->getAcceptableContentTypes())) {
                    $responseAnnotation = $candidateResponseAnnotation;
                    break;
                }
            }
        }

        if (!isset($responseAnnotation)) {
            throw new ResponseBuildingException(\sprintf(
                'No "%s" annotation found on action "%s" for the following context:%s
                    controller_result_status_code: "%d"%s
                    request_acceptable_content_types: "%s"
                ',
                ResponseAnnotation::class,
                $request->attributes->get('_controller'),
                \PHP_EOL,
                $controllerResult->getStatusCode(),
                \PHP_EOL,
                \implode(',', $request->getAcceptableContentTypes())
            ));
        }

        // Defines the response content...
        if (null === $responseAnnotation->getContent()) {
            $content = '';
        } else {
            $viewAnnotation = $responseAnnotation->getContent();

            if (null !== $viewAnnotation->getBuilder()) {
                if (!isset($this->viewBuilders[$viewAnnotation->getBuilder()])) {
                    throw new ResponseBuildingException(\sprintf(
                        'View builder "%s" is unknown to the response builder. Make sure the view builder implements "%s" and is registered as a service tagged "%s".',
                        $viewAnnotation->getBuilder(),
                        ViewBuilderInterface::class,
                        'view_builder'
                    ));
                }

                $viewBuilder = $this->viewBuilders[$viewAnnotation->getBuilder()];

                if (!$viewBuilder->supports($viewAnnotation)) {
                    throw new ResponseBuildingException(\sprintf(
                        'View builder "%s" does not support the current view',
                        $viewAnnotation->getBuilder()
                    ));
                }
            } else {
                foreach ($this->viewBuilders as $candidateViewBuilder) {
                    if ($candidateViewBuilder->supports($viewAnnotation)) {
                        $viewBuilder = $candidateViewBuilder;
                        break;
                    }
                }

                if (!isset($viewBuilder)) {
                    throw new ResponseBuildingException('None of registered view builders support the current view.');
                }
            }

            $content = $viewBuilder->build($viewAnnotation, $controllerResult->getData());
        }

        // Defines headers...
        $headers = [];
        foreach ($responseAnnotation->getHeaders() as $key => $value) {
            if (!\is_string($value) && !(\is_object($value) && $value instanceof RouteAnnotation)) {
                throw new ResponseBuildingException(\sprintf(
                    'Values of "%s" headers must be only of type string or "%s", got a value of type "%s".',
                    ResponseAnnotation::class,
                    RouteAnnotation::class,
                    \gettype($value)
                ));
            }

            if (\is_object($value)) {
                $routeName = $value->getName();
                $routeParameters = $value->getParameters();

                $resolvedRouteParameters = [];
                foreach ($routeParameters as $routeParameter) {
                    $resolvedRouteParameters[$routeParameter] = $request->attributes->get($routeParameter);
                }

                $value = $this->router->generate($routeName, $resolvedRouteParameters);
            }

            $headers[$key] = $value;
        }

        if ($content) {
            $headers['Content-Type'] = $responseAnnotation->getContent()->getFormat();
        }

        // Defines status code...
        $statusCode = $controllerResult->getStatusCode();

        return new Response($content, $statusCode, $headers);
    }
}
