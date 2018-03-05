<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The action event...
 *
 * @version 0.0.1
 * @package App\Event
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ActionEvent extends Event implements ActionEventInterface
{
    /**
     * @var Symfony\Component\HttpFoundation\Request $request
     */
    protected $request;

    /**
     * @var Symfony\Component\HttpFoundation\Response $response
     */
    protected $response;

    /**
     * @var int $status
     */
    protected $status;

    /**
     * @var array $operationResults
     */
    protected $operationResults;

    /**
     * Constructs the action event.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request          = $request;
        $this->operationResults = array();
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest() : Request
    {
        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse() : ?Response
    {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function setResponse(Response $response) : ActionEventInterface
    {
        $this->response = $response;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus() : ?int
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus(int $status) : ActionEventInterface
    {
        $this->status = $status;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOperationResults() : array
    {
        return $this->operationResults;
    }

    /**
     * {@inheritdoc}
     */
    public function setOperationResults(array $operationResults) : ActionEventInterface
    {
        $this->operationResults = $operationResults;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFqName() : string
    {
        return str_replace(
            ':',
            '::',
            $this->request->attributes->get('_controller')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCamelName() : string
    {
        $fqName = $this->getFqName();

        return substr($fqName, strpos($fqName, '::') + 2);
    }

    /**
     * {@inheritdoc}
     */
    public function getSnakeName() : string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->getSnakeName()));
    }
}

