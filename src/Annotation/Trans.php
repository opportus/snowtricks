<?php

namespace App\Annotation;

/**
 * The trans annotation.
 *
 * @version 0.0.1
 * @package App\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 * 
 * @Annotation
 * @Target("ANNOTATION")
 * @Attributes({
 *     @Attribute("id", type="string", required=true),
 *     @Attribute("parameters", type="array"),
 *     @Attribute("domain", type="string"),
 *     @Attribute("locale", type="string"),
 * })
 */
class Trans extends AbstractAnnotation
{
    /**
     * @var string $id
     */
    private $id;

    /**
     * @var array $parameters
     */
    private $parameters;

    /**
     * @var null|string $domain
     */
    private $domain;

    /**
     * @var null|string $locale
     */
    private $locale;

    /**
     * Constructs the trans annotation.
     * 
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->id = $values['id'];
        $this->parameters = $values['parameters'] ?? [];
        $this->$domain = $values['domain'] ?? null;
        $this->$locale = $values['locale'] ?? null;
    }

    /**
     * Gets the id.
     * 
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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

    /**
     * Gets the domain.
     * 
     * @return null|string
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * Gets the locale.
     * 
     * @return null|string
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }
}
