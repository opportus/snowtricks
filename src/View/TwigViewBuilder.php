<?php

namespace App\View;

use App\Annotation\View as ViewAnnotation;
use Twig_Environment;

/**
 * The Twig view builder.
 *
 * @version 0.0.1
 * @package App\View
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TwigViewBuilder implements ViewBuilderInterface
{
    /**
     * @var Twig_Environment $twig
     */
    private $twig;

    /**
     * Constructs the Twig view builder.
     * 
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function build(ViewAnnotation $viewAnnotation, $data = null): string
    {
        $template = $viewAnnotation->getOptions()['template'] ?? null;
        $context = null === $data ? [] : ['data' => $data];

        if (null !== $template) {
            $view = $this->twig->render($template, $context);
        } elseif ('application/json' === $viewAnnotation->getFormat()) {
            $view = $context;
        } else {
            $view = '';
        }

        if ('application/json' === $viewAnnotation->getFormat()) {
            $view = \json_encode($view);
        }

        return $view;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ViewAnnotation $viewAnnotation): bool
    {
        $viewFormat = $viewAnnotation->getFormat();

        return \in_array($viewFormat, ['text/html', 'application/json']);
    }
}
