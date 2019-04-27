<?php

namespace App\Configuration;

use App\Exception\ConfigurationException;

/**
 * The route.
 *
 * @version 0.0.1
 * @package App\Configuration
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
class Route implements AnnotationInterface
{
    use AnnotationTrait;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var array $parameters
     */
    private $parameters;

    /**
     * Constructs the route.
     * 
     * @param array $values
     * @throws App\Exception\ConfigurationException
     */
    public function __construct(array $values)
    {
        $this->name = $values['name'];
        $this->parameters = $values['parameters'] ?? [];

        foreach ($this->parameters as $parameterKey => $parameterValue) {
            if (!\is_string($parameterValue) && !(\is_object($parameterValue) && $parameterValue instanceof ControllerResultDataAccessorInterface)) {
                throw new ConfigurationException(\sprintf(
                    'Route parameter "%s" must have as value either a string or an object of type "%s".',
                    $parameterKey,
                    ControllerResultDataAccessorInterface::class
                ));
            }
        }
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
