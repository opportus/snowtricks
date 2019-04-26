<?php

namespace App\Configuration;

/**
 * The controller result data accessor interface.
 *
 * @version 0.0.1
 * @package App\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface ControllerResultDataAccessorInterface
{
    /**
     * Gets the name.
     * 
     * @return string
     */
    public function getName(): string;
}
