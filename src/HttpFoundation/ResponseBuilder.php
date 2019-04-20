<?php

namespace App\HttpFoundation;

use App\Exception\ResponseBuildingException;
use App\View\ViewBuilderInterface;
use App\HttpKernel\ControllerResultInterface;
use App\HttpKernel\ControllerResult;
use App\HttpKernel\ControllerException;
use App\Annotation\DatumFetcherInterface;
use App\Annotation\AbstractDatumReference;
use App\Annotation\Route;
use App\Annotation\Response as ResponseAnnotation;
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
     * @var App\Annotation\DatumFetcherInterface $datumFetcher
     */
    private $datumFetcher;

    /**
     * @var Symfony\Component\Routing\RouterInterface $router
     */
    private $router;

    /**
     * Constructs the response builder.
     *
     * @param App\View\ViewBuilderInterface[] $viewBuilders
     * @param App\Annotation\DatumFetcherInterface $datumFetcher
     * @param Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(array $viewBuilders, DatumFetcherInterface $datumFetcher, RouterInterface $router)
    {
        $this->viewBuilders = $viewBuilders;
        $this->datumFetcher = $datumFetcher;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function build(Request $request, ControllerResultInterface $controllerResult): Response
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
                        'View builder "%s" does not support the current view.',
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
        $data = $controllerResult->getData();
        $headers = $responseAnnotation->getHeaders();
        foreach ($headers as $name => $value) {
            if (\is_string($value)) {
                continue;
            } elseif ($value instanceof AbstractDatumReference) {
                $headers[$name] = $this->datumFetcher->fetch($value, $data);
            } elseif ($value instanceof Route) {
                $routeName = $value->getName();
                $routeParameters = $value->getParameters();

                foreach ($routeParameters as $key => $value) {
                    if (\is_string($value)) {
                        continue;
                    } elseif ($value instanceof AbstractDatumReference) {
                        $routeParameters[$key] = $this->datumFetcher->fetch($value, $data);
                    }
                }

                $headers[$name] = $this->router->generate($routeName, $routeParameters);
            }
        }

        if ($content) {
            $headers['Content-Type'] = $responseAnnotation->getContent()->getFormat();
        }

        // Defines status code...
        $statusCode = $controllerResult->getStatusCode();

        return new Response($content, $statusCode, $headers);
    }
}
