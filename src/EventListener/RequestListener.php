<?php

namespace App\EventListener;

use App\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * The request listener...
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class RequestListener
{
    /**
     * @var App\Validator\ValidatorInterface $validator
     */
    protected $validator;

    /**
     * Constructs the request listener.
     *
     * @param App\Validator\ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Listens on kernel request.
     *
     * @param  Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     * @throws Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->validateRequest($event);
    }

    /**
     * Validates the request.
     *
     * @param  Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     * @throws Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    private function validateRequest(GetReponseEvent $event)
    {
        $action      = $event->getRequest()->attributes->get('_controller');
        $constraints = array(
            'App\Controller\TrickCommentController::getTrickCommentList'          => 'App\Validator\Constraints\GetTrickCommentListQuery',
            'App\Controller\TrickCommentController::getTrickCommentEmptyEditForm' => 'App\Validator\Constraints\GetTrickCommentEmptyEditFormQuery',
            'App\Controller\TrickController::getTrickList'                        => 'App\Validator\Constraints\GetTrickListQuery',
        );

        if (array_key_exists($action, $constraints)) {
            $constraint = new $constraints[$action]();

            $this->validator->validateWithException($event->getRequest()->query, $constraint, null, BadRequestHttpException::class);
        }
    }
}

