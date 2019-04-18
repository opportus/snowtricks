<?php

namespace App\Annotation;

/**
 * The datum key reference annotation.
 *
 * @version 0.0.1
 * @package App\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target("ANNOTATION")
 * @Attributes({
 *     @Attribute("name", type="string", required=true)
 * })
 */
class DatumKeyReference extends AbstractDatumReference
{
}
