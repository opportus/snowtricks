<?php

namespace App\Annotation;

/**
 * The response annotation.
 *
 * @version 0.0.1
 * @package App\Annotation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 * 
 * @Annotation
 * @Target("METHOD")
 * @Attributes({
 *     @Attribute("content", type="App\Annotation\View"),
 *     @Attribute("statusCode", type="integer", required=true),
 *     @Attribute("headers", type="array"),
 *     @Attribute("options", type="array")
 * })
 */
class Response extends AbstractAnnotation
{
    /**
     * @var null|App\Annotation\View $content
     */
    private $content;

    /**
     * @var int $statusCode
     */
    private $statusCode;

    /**
     * @var array $headers
     */
    private $headers;

    /**
     * @var array $options
     */
    private $options;

    /**
     * Constructs the response annotation.
     * 
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->content = $values['content'] ?? null;
        $this->statusCode = $values['statusCode'];
        $this->headers = $values['headers'] ?? [];
        $this->options = $values['options'] ?? [];
    }

    /**
     * Gets the content.
     * 
     * @return null|App\Annotation\View
     */
    public function getContent(): ?View
    {
        return $this->content;
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
     * Gets the headers.
     * 
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
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