<?php

namespace App\Annotation;

/**
 * The session annotation.
 *
 * @version 0.0.1
 * @package App\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 * 
 * @Annotation
 * @Target("METHOD")
 * @Attributes({
 *     @Attribute("flash", type="App\Annotation\Flash"),
 *     @Attribute("options", type="array")
 * })
 */
class Session extends AbstractAnnotation
{
    /**
     * @var null|App\Annotation\Flash $flash
     */
    private $flash;

    /**
     * @var array $options
     */
    private $options;

    /**
     * Constructs the session annotation.
     * 
     * @param array $values
     */
    public function __cosntruct(array $values)
    {
        $this->flash = $values['flash'] ?? null;
        $this->options = $values['options'] ?? [];
    }

    /**
     * Gets the flash.
     * 
     * @return null|App\Annotation\Flash
     */
    public function getFlash(): ?Flash
    {
        return $this->flash;
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
