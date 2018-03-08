<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserToken;
use App\Form\Type\UserSignUpType;
use App\Form\Type\UserSignInType;
use App\Form\Type\UserRequestPasswordResetType;
use App\Form\Type\UserResetPasswordType;
use App\HttpKernel\ControllerResult;
use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * Posts sign up.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/sign-up", name="user_post_sign_up" defaults={"_format": "html"})
     * @Method("POST")
     */
    public function postSignUp(Request $request) : ControllerResultInterface
    {
        $user = new User();

        $form = $this->formFactory->createNamed(
            'user_sign_up_form',
            UserSignUpType::class,
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
     * @Route("/sign-up", name="user_get_sign_up_form" defaults={"_format": "html"})
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
     * Patches activation.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/activate/{token}", name="user_patch_activation", defaults={"_format": "html"})
     * @Method("PATCH")
     */
    public function patchActivation(Request $request) : ControllerResultInterface
    {
        $userToken = $this->entityManager->getRepository(UserToken::class)
            ->findOneByKey($request->attributes->get('token'))
        ;

        if ($userToken === null) {
            return new ControllerResult(404);

        } elseif ($userToken->isExpired()) {
            return new ControllerResult(400);
        }

        $user = $userToken->getUser();

        $user->enable();

        $this->entityManager->remove($userToken);
        $this->entityManager->flush();

        return new ControllerResult(204);
        $user = new User();

        $form = $this->formFactory->createNamed(
            'user_activation_form',
            UserSignUpType::class,
            $user,
            array(
                'action' => $this->router->generate($request->attributes->get('_route')),
                'method' => 'PATCH',
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * Requests a user password reset.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/request-password-reset", name="user_request_password_reset", defaults={"_format": html})
     * @Method("GET")
     */
    public function getPasswordReset(Request $request) : ControllerResultInterface
    {
        $form = $this->formFactory->create(UserRequestPasswordResetType::class, new User());

        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid() === false) {
                throw new RequestNotValidException();

            } elseif ($form->isSubmitted() && $form->isValid()) {
                $user = $this->entityManager->getRepository(User::class)->findOneByUsername(
                    $form->getData()->getUsername()
                );

                if ($user === null) {
                    throw new EntityNotFoundException();
                }

                $user->addPasswordResetToken();

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->mailer->sendUserPasswordResetEmail($user);

                $status = 201;

            } else {
                $status = 200;
            }

    }

    /**
     * Resets a user password.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/reset-password/{token}", name="user_reset_password")
     */
    public function resetPassword(Request $request)
    {
        $form = $this->formFactory->create(UserResetPasswordType::class, new User());

        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid() === false) {
                throw new RequestNotValidException();

            } elseif ($form->isSubmitted() && $form->isValid()) {
                $userToken = $this->entityManager->getRepository(UserToken::class)->findOneByKey(
                    $request->attributes->get('token')
                );

                if ($userToken === null || $userToken->isExpired()) {
                    throw new TokenNotValidException();
                }

                $user = $userToken->getUser();

                $user->setPlainPassword($form->getData()->getPlainPassword());

                $this->entityManager->remove($userToken);
                $this->entityManager->flush();

                $status = 201;

            } else {
                $status = 200;
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

