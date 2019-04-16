<?php

namespace App\Annotation;

/**
 * The view annotation.
 *
 * @version 0.0.1
 * @package App\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 * 
 * @Annotation
 * @Target("ANNOTATION")
 * @Attributes({
 *     @Attribute("format", type="string", required=true),
 *     @Attribute("builder", type="string"),
 *     @Attribute("options", type="array")
 * })
 */
class View extends AbstractAnnotation
{
    /**
     * @var string $format
     */
    private $format;

    /**
     * @var null|string $builder
     */
    private $builder;

    /**
     * @var array $options
     */
    private $options;

    /**
     * Constructs the view annotation.
     * 
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->format = $values['format'];
        $this->builder = $values['builder'] ?? null;
        $this->options = $values['options'] ?? [];
    }

    /**
     * Gets the format.
     * 
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * Gets the builder.
     * 
     * @return null|string
     */
    public function getBuilder(): ?string
    {
        return $this->builder;
    }

    /**
     * Gets the options.
     * 
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
