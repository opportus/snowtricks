<?php

namespace App\Annotation;

/**
 * The route annotation.
 *
 * @version 0.0.1
 * @package App\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 * 
 * @Annotation
 * @Target("ANNOTATION")
 * @Attributes({
 *     @Attribute("name", type="string", required=true),
 *     @Attribute("parameters", type="array")
 * })
 */
class Route extends AbstractAnnotation
{
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var array $parameters
     */
    private $parameters;

    /**
     * Constructs the route annotation.
     * 
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->name = $values['name'];
        $this->parameters = $values['parameters'] ?? [];
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
     * Gets the parameters.
     * 
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
