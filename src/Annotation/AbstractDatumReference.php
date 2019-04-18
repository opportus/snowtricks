<?php

namespace App\Annotation;

/**
 * The abstract datum reference annotation.
 *
 * @version 0.0.1
 * @package App\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
abstract class AbstractDatumReference extends AbstractAnnotation
{
    /**
     * @var string $name
     */
    private $name;

    /**
     * Constructs the abstract datum reference.
     * 
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->name = $values['name'];
    }

    /**
     * Gets the name.
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the name.
     * 
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }
}
