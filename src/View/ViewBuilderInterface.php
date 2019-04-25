<?php

namespace App\View;

use App\Annotation\View as ViewAnnotation;

/**
 * The view builder interface.
 *
 * @version 0.0.1
 * @package App\View
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface ViewBuilderInterface
{
    /**
     * Builds the view.
     * 
     * @param App\Annotation\View $viewAnnotation
     * @param mixed $data
     * @return string
     * @throws App\Exception\ViewBuildingException
     */
    public function build(ViewAnnotation $viewAnnotation, $data = null): string;

    /**
     * Checks whether the given view annotation is supported.
     * 
     * @return bool
     */
    public function supports(ViewAnnotation $viewAnnotation): bool;
}
