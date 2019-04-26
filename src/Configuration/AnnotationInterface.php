<?php

namespace App\Configuration;

/**
 * The annotation interface.
 *
 * @version 0.0.1
 * @package App\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface AnnotationInterface
{
    /**
     * Gets the alias.
     * 
     * @return string
     */
    public function getAlias(): string;
}
