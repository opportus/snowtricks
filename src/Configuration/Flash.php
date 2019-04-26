<?php

namespace App\Configuration;

/**
 * The flash.
 *
 * @version 0.0.1
 * @package App\Configuration
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @Annotation
 * @Target("METHOD")
 * @Attributes({
 *     @Attribute("statusCode", type="integer", required=true),
 *     @Attribute("message", type="App\Configuration\Trans", required=true)
 * })
 */
class Flash implements AnnotationInterface
{
    use AnnotationTrait;

    /**
     * @var int $statusCode
     */
    private $statusCode;

    /**
     * @var App\Configuration\Trans $message
     */
    private $message;

    /**
     * Constructs the flash.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->statusCode = $values['statusCode'];
        $this->message = $values['message'];
    }

    /**
     * Gets the status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Gets the message.
     *
     * @return App\Configuration\Trans
     */
    public function getMessage(): Trans
    {
        return $this->message;
    }
}
