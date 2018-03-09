<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserToken;
use App\Form\Type\UserType;
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
     * Posts a user.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/sign-up", name="user_post")
     * @Method("POST")
     */
    public function post(Request $request) : ControllerResultInterface
    {
        $user = new User();
        $form = $this->formFactory->create(
            UserType::class,
            $user,
            $this->parameters[$request->attributes->get('_route')]['form_options']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $data = array(
                'user' => $user,
            );

            return new ControllerResult(201, $data);

        } elseif ($form->isSubmitted()) {
            $data = array(
                'form' => $form->createView(),
            );

            return new ControllerResult(400, $data);
        }
    }

    /**
     * Gets activation form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/activation/{id}", name="user_get_activation_form" defaults={"_format": "html"})
     * @Method("GET")
     */
    public function getActivationForm(Request $request) : ControllerResultInterface
    {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneById($request->attributes->getInt('id'))
        ;

        if ($user === null) {
            return new ControllerResult(404);
        }

        if ($user->getActivationToken() === null) {
            return new ControllerResult(400);
        }

        // @todo Implement userTokenActivation data transformer...
        // @todo Implement UserActivationType
        $form = $this->formFactory->createNamed(
            'user_activation_form',
            UserActivationType::class,
            $user,
            array(
                'action' => $this->router->generate(
                    str_replace('get_activation_form', 'patch_activation', $request->attributes->get('_route')),
                    array('id' => (string) $user->getId())
                ),
                'method' => 'PATCH',
            )
        );

        $data = array(
            'form' => $form,
        );

        return new ControllerResult(200, $data);
    }

    /**
     * Patches activation.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/activation/{id}", name="user_patch_activation", defaults={"_format": "html"})
     * @Method("PATCH")
     */
    public function patchActivation(Request $request) : ControllerResultInterface
    {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneById($request->attributes->getInt('id'))
        ;

        // @todo Implement getActivation property...
        $form = $this->formFactory->createNamed(
            'user_activation_form',
            UserActivationType::class,
            $user,
            array(
                'action' => $this->router->generate($request->attributes->get('_route'), array('id' => $user->getId())),
                'method' => 'PATCH',
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return new ControllerResult(204);

        } elseif ($form->isSubmitted()) {
            return new ControllerResult(400);
        }
    }

    /**
     * Gets password reset request.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/password-reset-request", name="user_get_password_reset_request", defaults={"_format": "html"})
     * @Method("GET")
     */
    public function getPasswordResetRequest(Request $request) : ControllerResultInterface
    {
        $user = new User();

        $form = $this->formFactory->createNamed(
            'user_password_reset_request_form',
            UserPasswordResetRequestType::class,
            $user,
            array(
                'action' => $this->router->generate($request->attributes->get('_route')),
                'method' => 'GET',
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->entityManager->getRepository(User::class)
                ->findOneByUsername($user->getUsername())
            ;

            if ($user === null) {
                return new ControllerResult(404);
            }

            $data = array(
                'user' => $user,
            );

            return new ControllerResult(204, $data);

        } elseif ($form->isSubmitted()) {
            $data = array(
                'form' => $form->createView(),
            );

            return new ControllerResult(400, $data);
        }
    }

    /**
     * Gets password reset request form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/password-reset-request", name="user_get_password_reset_request_form", defaults={"_format": "html"})
     * @Method("GET")
     */
    public function getPasswordResetRequestForm(Request $request) : ControllerResultInterface
    {
        $user = new User();

        $form = $this->formFactory->createNamed(
            'user_password_reset_request_form',
            UserPasswordResetRequestType::class,
            $user,
            array(
                'action' => $this->router->generate(
                    str_replace('get_password_reset_request_form', 'post_password_reset_request', $request->attributes->get('_route'))
                ),
                'method' => 'POST',
            )
        );

        $data = array(
            'form' => $form,
        );

        return new ControllerResult(200, $data);
    }

    /**
     * Posts password reset.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/password-reset/{token}", name="user_post_password_reset", defaults={"_format": "html"})
     * @method("PATCH")
     */
    public function patchPassword(Request $request)
    {
        $user = new User();

        $form = $this->formFactory->createNamed(
            'user_password_reset_form',
            UserPasswordResetType::class,
            $user,
            array(
                'action' => $this->router->generate($request->attributes->get('_route')),
                'method' => 'POST',
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->enable()
                ->addRole('ROLE_USER')
                ->addActivationToken()
            ;

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $data = array(
                'user' => $user,
            );

            return new ControllerResult(201, $data);

        } elseif ($form->isSubmitted()) {
            $data = array(
                'form' => $form->createView(),
            );

            return new ControllerResult(400, $data);
        }
    }

    /**
     * Gets sign up form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/sign-up", name="user_get_sign_up_form" defaults={"_format": "html"})
     * @Method("GET")
     */
    public function getSignUpForm(Request $request) : ControllerResultInterface
    {
        $user = new User();

        $form = $this->formFactory->createNamed(
            'user_sign_up_form',
            UserSignUpType::class,
            $user,
            array(
                'action' => $this->router->generate(str_replace('get_sign_up_form', 'post_sign_up', $request->attributes->get('_route'))),
                'method' => 'POST',
            )
        );

        $data = array(
            'form' => $form,
        );

        return new ControllerResult(200, $data);
    }

    /**
     * Signs in a user.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @param  Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authUtils
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/sign-in", name="user_sign_in", defaults={"_format": "html"})
     */
    public function signIn(Request $request, AuthenticationUtils $authUtils) : ControllerResultInterface
    {
        $error = $authUtils->getLastAuthenticationError();
        $form  = $this->formFactory->create(UserSignInType::class);

        $data = array(
            'form'  => $form,
            'error' => $error
        );

        return new ControllerResult(200, $data);
    }

    /**
     * Signs out a user.
     *
     * @Route("/sign-out", name="user_sign_out", defaults={"_format": "html"})
     */
    public function signOut()
    {
    }
}

