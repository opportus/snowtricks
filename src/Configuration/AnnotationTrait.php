<?php

namespace App\Configuration;

/**
 * The annotation trait.
 *
 * @version 0.0.1
 * @package App\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
trait AnnotationTrait
{
    /**
     * Gets the alias.
     * 
     * @return string
     */
    public function getAlias(): string
    {
        $fqcn = \get_class($this);
        $nfqcn = \substr($fqcn, \strrpos($fqcn, '\\')+1);

        return \strtolower(\preg_replace('/(?<!^)[A-Z]/', '_$0', $nfqcn));
    }
}
