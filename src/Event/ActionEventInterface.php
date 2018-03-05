<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The action event interface...
 *
 * @version 0.0.1
 * @package App\Event
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface ActionEventInterface
{
    /**
     * Gets the request.
     *
     * @return Symfony\Component\HttpFoundation\Request
     */
    public function getRequest() : Request;

    /**
     * Gets the response.
     *
     * @return null|Symfony\Component\HttpFoundation\Response
     */
    public function getResponse() : ?Response;

    /**
     * Sets the response.
     *
     * @param  Symfony\Component\HttpFoundation\Response $response
     * @return App\Event\ActionEventInterface
     */
    public function setResponse(Response $response) : ActionEventInterface;

    /**
     * Gets the status.
     *
     * @return null|int
     */
    public function getStatus() : ?int;

    /**
     * Sets the status.
     *
     * @param  int $status
     * @return App\Event\ActionEventInterface
     */
    public function setStatus(int $status) : ActionEventInterface;

    /**
     * Gets the operation results.
     *
     * @return array
     */
    public function getOperationResults() : array;

    /**
     * Sets the operation results.
     *
     * @param  array $operationResults
     * @return App\Event\ActionEventInterface
     */
    public function setOperationResults(array $operationResults) : ActionEventInterface;

    /**
     * Gets the fully qualified name.
     *
     * @return string
     */
    public function getFqName() : string;

    /**
     * Gets the camel name.
     *
     * @return string
     */
    public function getCamelName() : string;


    /**
     * Gets the snake name.
     *
     * @return string
     */
    public function getSnakeName() : string;

    /**
     * Checks whether or not the propagation is stopped.
     *
     * @return bool
     */
    public function isPropagationStopped();

    /**
     * Stops the propagation.
     */
    public function stopPropagation();
}

