<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserToken;
use App\Form\Type\UserSignUpType;
use App\Form\Type\UserSignInType;
use App\Form\Type\UserRequestPasswordResetType;
use App\Form\Type\UserResetPasswordType;
use App\Exception\EntityNotFoundException;
use App\Exception\RequestNotValidException;
use App\Exception\TokenNotValidException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
     * Signs up a user.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/sign-up", name="user_sign_up")
     */
    public function signUp(Request $request)
    {
        $form = $this->formFactory->create(UserSignUpType::class, new User());

        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid() === false) {
                throw new RequestNotValidException();

            } elseif ($form->isSubmitted() && $form->isValid()) {
                $user = $form->getData();
                $user
                    ->enable()
                    ->addRole('ROLE_USER')
                    ->addActivationToken()
                ;

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->mailer
                    ->sendUserSignUpEmail($user)
                    ->sendUserActivationEmail($user)
                ;

                $status = 201;

            } else {
                $status = 200;
            }

        } catch (\Exception $exception) {
            if ($exception instanceof RequestNotValidException) {
                $status = 400;

            } else {
                $status = 500;

                $this->logger->critical(
                    sprintf(
                        'An %s has occured during a user sign up action',
                        get_class($exception)
                    ),
                    array(
                        'exception' => $exception,
                        'request'   => $request,
                    )
                );
            }
        }

        if ($this->translator->getCatalogue()->has('user.sign_up.notification.' . (string) $status)) {
            $request->getSession()->getFlashBag()->add(
                $status,
                $this->translator->trans(
                    'user.sign_up.notification.' . (string) $status,
                    array(
                        '%username%' => $form->getData()->getUsername(),
                        '%email%'    => $form->getData()->getEmail(),
                    )
                )
            );
        }

        if (isset($this->parameters['sign_up']['redirection'][$status])) {
            return new RedirectResponse(
                $this->router->generate(
                    $this->parameters['sign_up']['redirection'][$status]
                )
            );

        } else {
            return new Response(
                $this->twig->render(
                    'user/sign_up.html.twig',
                    array(
                        'form' => $form->createView(),
                    )
                ),
                $status
            );
        }
    }

    /**
     * Signs in a user.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @param  Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authUtils
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/sign-in", name="user_sign_in")
     */
    public function signIn(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $form  = $this->formFactory->create(UserSignInType::class);

        return new Response(
            $this->twig->render(
                'user/sign_in.html.twig',
                array(
                    'form'  => $form->createView(),
                    'error' => $error,

                )
            )
        );
    }

    /**
     * Signs out a user.
     *
     * @Route("/sign-out", name="user_sign_out")
     */
    public function signOut()
    {
    }

    /**
     * Activates a user.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/activate/{token}", name="user_activate")
     */
    public function activate(Request $request)
    {
        try {
            $userToken = $this->entityManager->getRepository(UserToken::class)->findOneByKey(
                $request->attributes->get('token')
            );

            if ($userToken === null || $userToken->isExpired()) {
                throw new TokenNotValidException();
            }

            $user = $userToken->getUser();

            $user->enable();

            $this->entityManager->remove($userToken);
            $this->entityManager->flush();

            $status = 201;

        } catch (\Exception $exception) {
            if ($exception instanceof TokenNotValidException) {
                $status = 403;

            } else {
                $status = 500;

                $this->logger->critical(
                    sprintf(
                        'An %s has occured during a user activation action',
                        get_class($exception)
                    ),
                    array(
                        'exception' => $exception,
                        'request'   => $request,
                    )
                );
            }
        }

        if ($this->translator->getCatalogue()->has('user.activate.notification.' . (string) $status)) {
            $request->getSession()->getFlashBag()->add(
                $status,
                $this->translator->trans(
                    'user.activate.notification.' . (string) $status
                )
            );
        }

        return new RedirectResponse(
            $this->router->generate(
                $this->parameters['activate']['redirection'][$status]
            )
        );
    }

    /**
     * Requests a user password reset.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/request-password-reset", name="user_request_password_reset")
     */
    public function requestPasswordReset(Request $request)
    {
        $form = $this->formFactory->create(UserRequestPasswordResetType::class, new User());

        $form->handleRequest($request);

        try {
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

        } catch (\Exception $exception) {
            if ($exception instanceof RequestNotValidException) {
                $status = 400;

            } elseif ($exception instanceof EntityNotFoundException) {
                $status = 404;

            } else {
                $status = 500;

                $this->logger->critical(
                    sprintf(
                        'A %s has occured during a user password reset request action',
                        get_class($exception)
                    ),
                    array(
                        'exception' => $exception,
                        'request'   => $request,
                    )
                );
            }
        }

        if ($this->translator->getCatalogue()->has('user.request_password_reset.notification.' . (string) $status)) {
            $request->getSession()->getFlashBag()->add(
                $status,
                $this->translator->trans(
                    'user.request_password_reset.notification.' . (string) $status,
                    array(
                        '%username%' => $form->getData()->getUsername(),
                    )
                )
            );
        }

        if (isset($this->parameters['request_password_reset']['redirection'][$status])) {
            return new RedirectResponse(
                $this->router->generate(
                    $this->parameters['request_password_reset']['redirection'][$status]
                )
            );

        } else {
            return new Response(
                $this->twig->render(
                    'user/request_password_reset.html.twig',
                    array(
                        'form' => $form->createView(),
                    )
                ),
                $status
            );
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

        try {
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

        } catch (\Exception $exception) {
            if ($exception instanceof RequestNotValidException) {
                $status = 400;

            } elseif ($exception instanceof TokenNotValidException) {
                $status = 403;

            } else {
                $status = 500;

                $this->logger->critical(
                    sprintf(
                        'A %s has occured during a user password reset action',
                        get_class($exception)
                    ),
                    array(
                        'exception' => $exception,
                        'request'   => $request,
                    )
                );
            }
        }

        if ($this->translator->getCatalogue()->has('user.reset_password.notification.' . (string) $status)) {
            $request->getSession()->getFlashBag()->add(
                $status,
                $this->translator->trans(
                    'user.reset_password.notification.' . (string) $status
                )
            );
        }

        if (isset($this->parameters['reset_password']['redirection'][$status])) {
            return new RedirectResponse(
                $this->router->generate(
                    $this->parameters['reset_password']['redirection'][$status]
                )
            );

        } else {
            return new Response(
                $this->twig->render(
                    'user/reset_password.html.twig',
                    array(
                        'form' => $form->createView(),
                    )
                ),
                $status
            );
        }
    }
}

