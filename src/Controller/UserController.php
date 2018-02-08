<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserToken;
use App\Form\UserSignUpFormType;
use App\Form\UserSignInFormType;
use App\Form\UserRequestPasswordResetFormType;
use App\Form\UserResetPasswordFormType;
use App\Exception\EntityNotFoundException;
use App\Exception\TokenNotValidException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

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
        $status   = 200;
        // @todo If necessary, implement a factory...
        $formData = new User();
        $form     = $this->formFactory->create(UserSignUpFormType::class, $formData);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $formData;
            $user
                ->setActivation(false)
                ->addRole('ROLE_USER')
            ;

            // @todo If necessary, implement a factory...
            $userToken = new UserToken();
            $userToken
                ->setUser($user)
                ->setType('activation')
            ;

            try {
                $this->entityManager->persist($user);
                $this->entityManager->persist($userToken);
                $this->entityManager->flush();

                $this->mailer
                    ->sendUserSignUpEmail($user)
                    ->sendUserActivationEmail($user, $userToken)
                ;

                $status = 201;

            } catch (\Exception $exception) {
                if ($exception instanceof UniqueConstraintViolationException) {
                    $status = 409;

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
        }

        if ($this->translator->getCatalogue()->has('user.sign_up.notification.' . (string) $status)) {
            $request->getSession()->getFlashBag()->add(
                $status,
                $this->translator->trans(
                    'user.sign_up.notification.' . (string) $status,
                    array(
                        '%username%' => $formData->getUsername(),
                        '%email%'    => $formData->getEmail(),
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
                    $this->parameters['sign_up']['template'],
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
        $form  = $this->formFactory->create(UserSignInFormType::class);

        return new Response(
            $this->twig->render(
                $this->parameters['sign_in']['template'],
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

            $user->setActivation(true);

            $this->entityManager->persist($user);
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
                    'user.activate.notification.' . (string) $status,
                    array(
                        '%token%' => $request->attributes->get('token'),
                    )
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
        $status   = 200;
        // @todo If necessary, implement a factory...
        $formData = new User();
        $form     = $this->formFactory->create(UserRequestPasswordResetFormType::class, $formData);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $this->entityManager->getRepository(User::class)->findOneByUsername(
                    $formData->getUsername()
                );

                if ($user === null) {
                    throw new EntityNotFoundException();
                }

                // @todo If necessary, implement a factory...
                $userToken = new UserToken();
                $userToken
                    ->setUser($user)
                    ->setType('password_reset')
                ;

                $this->entityManager->persist($userToken);
                $this->entityManager->flush();

                $this->mailer->sendUserPasswordResetEmail($user, $userToken);

                $status = 201;

            } catch (\Exception $exception) {
                if ($exception instanceof EntityNotFoundException) {
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
        }

        if ($this->translator->getCatalogue()->has('user.request_password_reset.notification.' . (string) $status)) {
            $request->getSession()->getFlashBag()->add(
                $status,
                $this->translator->trans(
                    'user.request_password_reset.notification.' . (string) $status,
                    array(
                        '%username%' => $formData->getUsername(),
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
                    $this->parameters['request_password_reset']['template'],
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
        $status   = 200;
        $formData = new User();
        $form     = $this->formFactory->create(UserResetPasswordFormType::class, $formData);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $userToken = $this->entityManager->getRepository(UserToken::class)->findOneByKey(
                    $request->attributes->get('token')
                );

                if ($userToken === null || $userToken->isExpired()){
                    throw new TokenNotValidException();
                }

                $user = $userToken->getUser();

                $user->setPlainPassword($formData->getPlainPassword());

                $this->entityManager->persist($user);
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
        }

        if ($this->translator->getCatalogue()->has('user.reset_password.notification.' . (string) $status)) {
            $request->getSession()->getFlashBag()->add(
                $status,
                $this->translator->trans(
                    'user.reset_password.notification.' . (string) $status,
                    array(
                        '%token%' => $request->attributes->get('token'),
                    )
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
                    $this->parameters['reset_password']['template'],
                    array(
                        'form' => $form->createView(),
                    )
                ),
                $status
            );
        }
    }
}

