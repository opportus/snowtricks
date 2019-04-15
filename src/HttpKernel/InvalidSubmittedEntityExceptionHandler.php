<?php

namespace App\HttpKernel;

use App\Controller\ExceptionController;
use App\Exception\InvalidSubmittedEntityException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * The invalid submitted entity exception handler.
 *
 * @version 0.0.1
 * @package App\HttpKernel
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class InvalidSubmittedEntityExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @var Symfony\Component\HttpKernel\HttpKernelInterface $kernel
     */
    private $kernel;

    /**
     * Constructs the entity not found exception handler.
     * 
     * @param Symfony\Component\HttpKernel\HttpKernelInterface $kernel
     */
    public function __construct(HttpKernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    
    /**
     * {@inheritdoc}
     */
    public function handle(Request $request) : Response
    {
        $subRequest = $request->duplicate();
        
        $subRequest->attributes->set('_converters', []);
        $subRequest->attributes->set('_controller', ExceptionController::class.'::getInvalidSubmittedEntityException');

        return $this->kernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(\Exception $exception) : bool
    {
        return $exception instanceof InvalidSubmittedEntityException;
    }
}
