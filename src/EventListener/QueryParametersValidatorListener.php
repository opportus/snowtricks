<?php

namespace App\EventListener;

use App\Validator\Constraints\TrickCollectionQueryParameters;
use App\Validator\Constraints\TrickCommentCollectionQueryParameters;
use App\Validator\Constraints\TrickCommentEmptyEditFormQueryParameters;
use App\Controller\TrickController;
use App\Controller\TrickCommentController;
use App\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * The query parameters validator listener.
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class QueryParametersValidatorListener
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
     * Constructs the query parameters validator listener.
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
            $this->validateQueryParameters($event);
        }
    }

    /**
     * Validates the query parameters.
     *
     * @param  Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     * @throws Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    private function validateQueryParameters(GetResponseEvent $event)
    {
        $action      = $event->getRequest()->attributes->get('_controller');
        $constraints = array(
            TrickController::class . '::getTrickCollection'                  => TrickCollectionQueryParameters::class,
            TrickCommentController::class . '::getTrickCommentCollection'    => TrickCommentCollectionQueryParameters::class,
            TrickCommentController::class . '::getTrickCommentEmptyEditForm' => TrickCommentEmptyEditFormQueryParameters::class,
        );

        if (array_key_exists($action, $constraints)) {
            $constraint = new $constraints[$action]();

            $this->validator->validateWithException($event->getRequest()->query->all(), $constraint, null);
        }
    }
}
