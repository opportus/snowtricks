<?php

namespace App\Configuration;

use App\Exception\ConfigurationException;

/**
 * The response.
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
 *     @Attribute("content", type="App\Configuration\View", required=true),
 *     @Attribute("headers", type="array"),
 *     @Attribute("options", type="array")
 * })
 */
class Response implements AnnotationInterface
{
    use AnnotationTrait;

    /**
     * @var int $statusCode
     */
    private $statusCode;

    /**
     * @var App\Configuration\View $content
     */
    private $content;

    /**
     * @var array $headers
     */
    private $headers;

    /**
     * @var array $options
     */
    private $options;

    /**
     * Constructs the response.
     * 
     * @param array $values
     * @throws App\Exception\ConfigurationException
     */
    public function __construct(array $values)
    {
        $this->statusCode = $values['statusCode'];
        $this->content = $values['content'];
        $this->headers = $values['headers'] ?? [];
        $this->options = $values['options'] ?? [];

        foreach ($this->headers as $headerName => $headerValue) {
            if (!\is_string($headerValue) && !(\is_object($headerValue) && ($headerValue instanceof ControllerResultDataAccessorInterface || $headerValue instanceof Route))) {
                throw new ConfigurationException(\sprintf(
                    'Response header "%s" must have as value either a string or an object of type "%s" or "%s".',
                    $headerName,
                    ControllerResultDataAccessorInterface::class,
                    Route::class
                ));
            }
        }
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
     * Gets the content.
     * 
     * @return null|App\Configuration\View
     */
    public function getContent(): ?View
    {
        return $this->content;
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
