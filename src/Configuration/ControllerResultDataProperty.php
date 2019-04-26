<?php

namespace App\Configuration;

/**
 * The controller result data property.
 *
 * @version 0.0.1
 * @package App\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target("ANNOTATION")
 * @Attributes({
 *     @Attribute("name", type="string", required=true)
 * })
 */
class ControllerResultDataProperty implements ControllerResultDataAccessorInterface, AnnotationInterface
{
    use ControllerResultDataAccessorTrait;
    use AnnotationTrait;
}
