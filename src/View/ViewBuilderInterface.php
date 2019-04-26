<?php

namespace App\View;

use App\Configuration\View as ViewConfiguration;

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
     * @param App\Configuration\View $viewConfiguration
     * @param mixed $data
     * @return string
     * @throws App\Exception\ViewBuildingException
     */
    public function build(ViewConfiguration $viewConfiguration, $data = null): string;

    /**
     * Checks whether the given view configuration is supported.
     * 
     * @return bool
     */
    public function supports(ViewConfiguration $viewConfiguration): bool;
}
