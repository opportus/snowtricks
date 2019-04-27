<?php

namespace App\View;

use App\Configuration\View as ViewConfiguration;
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
    public function build(ViewConfiguration $viewConfiguration, $data = null): string
    {
        $template = $viewConfiguration->getOptions()['template'] ?? null;
        $context = null === $data ? [] : ['data' => $data];

        if (null !== $template) {
            $view = $this->twig->render($template, $context);
        } elseif ('application/json' === $viewConfiguration->getFormat()) {
            $view = $context;
        } else {
            $view = '';
        }

        if ('application/json' === $viewConfiguration->getFormat()) {
            $view = \json_encode($view);
        }

        return $view;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ViewConfiguration $viewConfiguration): bool
    {
        $viewFormat = $viewConfiguration->getFormat();

        return \in_array($viewFormat, ['text/html', 'application/json']);
    }
}
