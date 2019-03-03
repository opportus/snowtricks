<?php

namespace App\EventListener;

use App\Validator\Constraints\TrickListQuery;
use App\Validator\Constraints\TrickCommentListQuery;
use App\Validator\Constraints\TrickCommentEmptyEditFormQuery;
use App\Controller\TrickController;
use App\Controller\TrickCommentController;
use App\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * The request validator listener...
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class RequestValidatorListener
{
    /**
     * @var Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    private $requestStack;

    /**
     * @var App\Validator\ValidatorInterface $validator
     */
    private $validator;

    /**
     * Constructs the request validator listener.
     *
     * @param Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param App\Validator\ValidatorInterface $validator
     */
    public function __construct(RequestStack $requestStack, ValidatorInterface $validator)
    {
        $this->requestStack = $requestStack;
        $this->validator    = $validator;
    }

    /**
     * Listens on kernel request.
     *
     * @param  Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     * @throws Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->requestStack->getParentRequest() === null) {
            $this->validateRequest($event);
        }
    }

    /**
     * Validates the request.
     *
     * @param  Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     * @throws Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    private function validateRequest(GetResponseEvent $event)
    {
        $action      = $event->getRequest()->attributes->get('_controller');
        $constraints = array(
            TrickController::class . '::getTrickList'                        => TrickListQuery::class,
            TrickCommentController::class . '::getTrickCommentList'          => TrickCommentListQuery::class,
            TrickCommentController::class . '::getTrickCommentEmptyEditForm' => TrickCommentEmptyEditFormQuery::class,
        );

        if (array_key_exists($action, $constraints)) {
            $constraint = new $constraints[$action]();

            $this->validator->validateWithException($event->getRequest()->query->all(), $constraint, null, BadRequestHttpException::class);
        }
    }
}
