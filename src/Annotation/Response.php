<?php

namespace App\Annotation;

use Doctrine\Annotations\AnnotationException;

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
 *     @Attribute("statusCode", type="integer", required=true),
 *     @Attribute("content", type="App\Annotation\View", required=true),
 *     @Attribute("headers", type="array"),
 *     @Attribute("options", type="array")
 * })
 */
class Response extends AbstractAnnotation
{
    /**
     * @var int $statusCode
     */
    private $statusCode;

    /**
     * @var App\Annotation\View $content
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
     * Constructs the response annotation.
     * 
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->statusCode = $values['statusCode'];
        $this->content = $values['content'];
        $this->headers = $values['headers'] ?? [];
        $this->options = $values['options'] ?? [];

        foreach ($this->headers as $headerName => $headerValue) {
            if (!\is_string($headerValue) && !(\is_object($headerValue) && ($headerValue instanceof AbstractDatumReference || $headerValue instanceof Route))) {
                throw AnnotationException::typeError(\sprintf(
                    'Header "%s" of annotation @%s must have as value either a string or an annotation of type %s or %s.',
                    (string)$headerName,
                    self::class,
                    AbstractDatumReference::class,
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
     * @return null|App\Annotation\View
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
