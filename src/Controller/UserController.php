<?php

namespace App\Controller;

use App\HttpKernel\ControllerResult;
use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * The user controller...
 *
 * @version 0.0.1
 * @package App\Controller
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserController extends Controller
{
    /**
     * Gets user sign up empty form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/sign-up", name="get_user_sign_up_empty_form")
     * @Method("GET")
     */
    public function getUserSignUpEmptyForm(Request $request) : ControllerResultInterface
    {
        return $this->getForm($request);
    }

    /**
     * Posts user by sign up form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/sign-up", name="post_user_by_sign_up_form")
     * @Method("POST")
     */
    public function postUserBySignUpForm(Request $request) : ControllerResultInterface
    {
        return $this->post($request);
    }

    /**
     * Patches user by activation email form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/activation/{id}", name="patch_user_by_activation_email_form")
     * @Method("PATCH")
     */
    public function patchUserByActivationEmailForm(Request $request) : ControllerResultInterface
    {
        return $this->patch($request);
    }

    /**
     * Gets user password reset request empty form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/password-reset-request", name="get_user_password_reset_request_empty_form")
     * @Method("GET")
     */
    public function getUserPasswordResetRequestEmptyForm(Request $request) : ControllerResultInterface
    {
        return $this->getForm($request);
    }

    /**
     * Proceeds by user password reset request form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/password-reset-request", name="proceed_by_user_password_reset_request_form")
     * @Method("POST")
     */
    public function proceedByUserPasswordResetRequestForm(Request $request) : ControllerResultInterface
    {
        return $this->proceed($request);
    }

    /**
     * Gets user password reset form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/password-reset/{id}", name="get_user_password_reset_empty_form")
     * @Method("GET")
     */
    public function getUserPasswordResetEmptyForm(Request $request) : ControllerResultInterface
    {
        return $this->getForm($request);
    }

    /**
     * Patches user by password reset form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/password-reset/{id}", name="patch_user_by_password_reset_form")
     * @method("PATCH")
     */
    public function patchUserByPasswordResetForm(Request $request) : ControllerResultInterface
    {
        return $this->patch($request);
    }

    /**
     * Gets user sign in empty form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/sign-in", name="get_user_sign_in_empty_form")
     */
    public function getUserSignInEmptyForm(Request $request, AuthenticationUtils $authenticationUtils) : ControllerResultInterface
    {
        $controllerResult = $this->getForm($request);

        return $controllerResult->setData(array_merge(
            $controllerResult->getData(),
            array(
                'error'    => $authenticationUtils->getLastAuthenticationError(),
                'username' => $authenticationUtils->getLastUsername(),
            )
        ));
    }

    /**
     * Proceeds user sign out.
     *
     * @Route("/user/sign-out", name="proceed_user_sign_out")
     */
    public function proceedUserSignOut()
    {}
}

