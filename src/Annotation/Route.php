<?php

namespace App\Annotation;

use Doctrine\Annotations\AnnotationException;

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

        foreach ($this->parameters as $parameterKey => $parameterValue) {
            if (!\is_string($parameterValue) && !(\is_object($parameterValue) && $parameterValue instanceof AbstractDatumReference)) {
                throw AnnotationException::typeError(\sprintf(
                    'Parameter "%s" of annotation @%s must have as value either a string or an annotation of type %s.',
                    (string)$parameterKey,
                    self::class,
                    AbstractDatumReference::class
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
