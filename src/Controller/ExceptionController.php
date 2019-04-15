<?php

namespace App\Controller;

use App\HttpKernel\ControllerResult;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;

/**
 * The exception controller.
 *
 * @version 0.0.1
 * @package App\Controller
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ExceptionController
{
    /**
     * Gets the entity not found exception.
     *
     * @return App\HttpKernel\ControllerResult
     */
    public function getEntityNotFoundException() : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_NOT_FOUND,
            null
        );
    }

    /**
     * Gets the invalid submitted entity exception.
     *
     * @param Symfony\Component\Form\FormInterface $submittedEntityForm
     * @return App\HttpKernel\ControllerResult
     */
    public function getInvalidSubmittedEntityException(FormInterface $submittedEntityForm) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_BAD_REQUEST,
            $submittedEntityForm
        );
    }
}
