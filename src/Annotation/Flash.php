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
 * @Target("METHOD")
 * @Attributes({
 *     @Attribute("statusCode", type="integer", required=true),
 *     @Attribute("message", type="App\Annotation\Trans", required=true)
 * })
 */
class Flash extends AbstractAnnotation
{
    /**
     * @var int $statusCode
     */
    private $statusCode;

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
     * @return App\Annotation\Trans
     */
    public function getMessage(): Trans
    {
        return $this->message;
    }
}
