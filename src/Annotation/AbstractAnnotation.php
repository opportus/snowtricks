<?php

namespace App\Annotation;

/**
 * The abstract annotation.
 *
 * @version 0.0.1
 * @package App\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
abstract class AbstractAnnotation
{
    /**
     * Gets the alias of the annotation.
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
