<?php

namespace App\Annotation;

/**
 * The flash annotation.
 *
 * @version 0.0.1
 * @package App\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 * 
 * @Annotation
 * @Target("ANNOTATION")
 * @Attributes({
 *     @Attribute("type", type="string", required=true),
 *     @Attribute("message", type="App\Annotation\Trans", required=true)
 * })
 */
class Flash extends AbstractAnnotation
{
    /**
     * @var string $type
     */
    private $type;

    /**
     * @var App\Annotation\Trans $message
     */
    private $message;

    /**
     * Constructs the flash annotation.
     * 
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->type = $values['type'];
        $this->message = $values['message'];
    }

    /**
     * Gets the type.
     * 
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Gets the message.
     * 
     * @return App\Annotation\Trans
     */
    public function getMessage(): Trans
    {
        return $this->message;
    }
}
