<?php

namespace App\Controller;

use App;
use App\Entity\User;
use App\HttpKernel\ControllerResult;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * The user controller.
 *
 * @version 0.0.1
 * @package App\Controller
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserController
{
    use ControllerTrait;

    /**
     * Gets the user sign up empty form.
     *
     * @param Symfony\Component\Form\FormInterface $form
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/user/sign-up", name="get_user_sign_up_empty_form", methods={"GET"})
     * 
     * @ParamConverter(
     *     "form",
     *     class="App\Entity\User",
     *     converter="app.entity_form_param_converter",
     *     options={
     *         "form_type"="App\Form\Type\UserSignUpType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\UserDto",
     *             "method"="POST"
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="user/sign_up.html.twig"
     *         }
     *     ),
     *     statusCode=Response::HTTP_OK,
     *     headers={},
     *     options={}
     * )
     */
    public function getUserSignUpEmptyForm(FormInterface $form) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_OK,
            $form
        );
    }

    /**
     * Posts the user by sign up form.
     *
     * @param App\Entity\User $user
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/user/sign-up", name="post_user_by_sign_up_form", methods={"POST"})
     * 
     * @ParamConverter(
     *     "user",
     *     class="App\Entity\User",
     *     converter="app.submitted_entity_param_converter",
     *     options={
     *         "form_type"="App\Form\Type\UserSignUpType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\UserDto",
     *             "method"="POST",
     *             "validation_groups"= {"user.form.sign_up"}
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     headers={
     *         "location"=@App\Annotation\Route(
     *             name="get_home",
     *             parameters={}
     *         )
     *     },
     *     options={}
     * )
     * 
     * @App\Annotation\Response(
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="user/sign_up.html.twig"
     *         }
     *     ),
     *     statusCode=Response::HTTP_BAD_REQUEST,
     *     headers={},
     *     options={}
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     message=@App\Annotation\Trans(
     *         id="user.sign_up.notification.201",
     *         parameters={
     *             "username"=@App\Annotation\DatumGetterReference(
     *                 name="getUsername"
     *             ),
     *             "email"=@App\Annotation\DatumGetterReference(
     *                 name="getEmail"
     *             )
     *         }
     *     )
     * )
     */
    public function postUserBySignUpForm(User $user) : ControllerResult
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new ControllerResult(
            Response::HTTP_SEE_OTHER,
            $user
        );
    }

    /**
     * Patches the user by activation email form.
     *
     * @param App\Entity\User $user
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/user/activation/{id}", name="patch_user_by_activation_email_form", methods={"PATCH"})
     * 
     * @ParamConverter(
     *     "user",
     *     class="App\Entity\User",
     *     converter="app.submitted_entity_param_converter",
     *     options={
     *         "id"="id",
     *         "form_type"="App\Form\Type\UserActivationEmailType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\UserDto",
     *             "method"="PATCH",
     *             "csrf_protection"=false
     *         },
     *         "repository_method"="findOneByIdOrThrowExceptionIfNoResult",
     *         "grant"="PATCH"
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     headers={
     *         "location"=@App\Annotation\Route(
     *             name="get_home",
     *             parameters={}
     *         )
     *     },
     *     options={}
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     message=@App\Annotation\Trans(
     *         id="user.activation.notification.204"
     *     )
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_BAD_REQUEST,
     *     message=@App\Annotation\Trans(
     *         id="user.activation.notification.400"
     *     )
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_FORBIDDEN,
     *     message=@App\Annotation\Trans(
     *         id="user.activation.notification.403"
     *     )
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_NOT_FOUND,
     *     message=@App\Annotation\Trans(
     *         id="user.activation.notification.404"
     *     )
     * )
     */
    public function patchUserByActivationEmailForm(User $user) : ControllerResult
    {
        $this->entityManager->flush();

        return new ControllerResult(
            Response::HTTP_SEE_OTHER,
            $user
        );
    }

    /**
     * Gets the user password reset request empty form.
     * 
     * @param Symfony\Component\Form\FormInterface $form
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/user/password-reset-request", name="get_user_password_reset_request_empty_form", methods={"GET"})
     * 
     * @ParamConverter(
     *     "form",
     *     class="App\Entity\User",
     *     converter="app.entity_form_param_converter",
     *     options={
     *         "form_type"="App\Form\Type\UserPasswordResetRequestType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\UserDto",
     *             "method"="GET"
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="user/password_reset_request.html.twig"
     *         }
     *     ),
     *     statusCode=Response::HTTP_OK,
     *     headers={},
     *     options={}
     * )
     */
    public function getUserPasswordResetRequestEmptyForm(FormInterface $form) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_OK,
            $form
        );
    }

    /**
     * Proceeds by user password reset request form.
     *
     * @param App\Entity\User $user
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/user/password-reset-request/proceed", name="proceed_by_user_password_reset_request_form", methods={"GET"})
     * 
     * @ParamConverter(
     *     "user",
     *     class="App\Entity\User",
     *     converter="app.submitted_entity_param_converter",
     *     options={
     *         "id"="username",
     *         "form_type"="App\Form\Type\UserPasswordResetRequestType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\UserDto",
     *             "method"="GET",
     *             "validation_groups"={"user.form.password_reset_request"}
     *         },
     *         "repository_method"="findOneByUsernameOrThrowExceptionIfNoResult"
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     headers={
     *         "location"=@App\Annotation\Route(
     *             name="get_home",
     *             parameters={}
     *         )
     *     },
     *     options={}
     * )
     * 
     * @App\Annotation\Response(
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="user/password_reset_request.html.twig"
     *         }
     *     ),
     *     statusCode=Response::HTTP_BAD_REQUEST,
     *     headers={},
     *     options={}
     * )
     * 
     * @App\Annotation\Response(
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="user/password_reset_request.html.twig"
     *         }
     *     ),
     *     statusCode=Response::HTTP_NOT_FOUND,
     *     headers={},
     *     options={}
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     message=@App\Annotation\Trans(
     *         id="user.password_reset_request.notification.204"
     *     )
     * )
     */
    public function proceedByUserPasswordResetRequestForm(User $user) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_SEE_OTHER,
            $user
        );
    }

    /**
     * Gets the user password reset form.
     *
     * @param Symfony\Component\Form\FormInterface $form
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/user/password-reset/{id}", name="get_user_password_reset_empty_form", methods={"GET"})
     * 
     * @ParamConverter(
     *     "form",
     *     class="App\Entity\User",
     *     converter="app.entity_form_param_converter",
     *     options={
     *         "form_type"="App\Form\Type\UserPasswordResetType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\UserDto",
     *             "method"="PATCH"
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="user/password_reset.html.twig"
     *         }
     *     ),
     *     statusCode=Response::HTTP_OK,
     *     headers={},
     *     options={}
     * )
     */
    public function getUserPasswordResetEmptyForm(FormInterface $form) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_OK,
            $form
        );
    }

    /**
     * Patches the user by password reset form.
     *
     * @param App\Entity\User $user
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/user/password-reset/{id}", name="patch_user_by_password_reset_form", methods={"PATCH"})
     * 
     * @ParamConverter(
     *     "user",
     *     class="App\Entity\User",
     *     converter="app.submitted_entity_param_converter",
     *     options={
     *         "id"="id",
     *         "form_type"="App\Form\Type\UserPasswordResetType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\UserDto",
     *             "method"="PATCH",
     *             "validation_groups"= {"user.form.password_reset"}
     *         },
     *         "repository_method"="findOneByIdOrThrowExceptionIfNoResult",
     *         "grant"="PATCH"
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     headers={
     *         "location"=@App\Annotation\Route(
     *             name="get_home",
     *             parameters={}
     *         )
     *     },
     *     options={}
     * )
     * 
     * @App\Annotation\Response(
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="user/password_reset.html.twig"
     *         }
     *     ),
     *     statusCode=Response::HTTP_BAD_REQUEST,
     *     headers={},
     *     options={}
     * )
     * 
     * @App\Annotation\Response(
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="user/password_reset.html.twig"
     *         }
     *     ),
     *     statusCode=Response::HTTP_NOT_FOUND,
     *     headers={},
     *     options={}
     * )
     * 
     * @App\Annotation\Response(
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="user/password_reset.html.twig"
     *         }
     *     ),
     *     statusCode=Response::HTTP_FORBIDDEN,
     *     headers={},
     *     options={}
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     message=@App\Annotation\Trans(
     *         id="user.password_reset.notification.204"
     *     )
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_FORBIDDEN,
     *     message=@App\Annotation\Trans(
     *         id="user.password_reset.notification.403"
     *     )
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_NOT_FOUND,
     *     message=@App\Annotation\Trans(
     *         id="user.password_reset.notification.404"
     *     )
     * )
     */
    public function patchUserByPasswordResetForm(User $user) : ControllerResult
    {
        $this->entityManager->flush();

        return new ControllerResult(
            Response::HTTP_SEE_OTHER,
            $user
        );
    }

    /**
     * Gets the user sign in empty form.
     *
     * @param Symfony\Component\Form\FormInterface $form
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/user/sign-in", name="get_user_sign_in_empty_form")
     * 
     * @ParamConverter(
     *     "form",
     *     class="App\Entity\User",
     *     converter="app.entity_form_param_converter",
     *     options={
     *         "form_type"="App\Form\Type\UserSignInType",
     *         "form_options"={
     *             "data_class"="App\Entity\Dto\UserDto",
     *             "method"="POST"
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="user/sign_in.html.twig"
     *         }
     *     ),
     *     statusCode=Response::HTTP_OK,
     *     headers={},
     *     options={}
     * )
     */
    public function getUserSignInEmptyForm(FormInterface $form, AuthenticationUtils $authenticationUtils) : ControllerResult
    {
        return new ControllerResult(
            Response::HTTP_OK,
            [
                'form' => $form,
                'error' => $authenticationUtils->getLastAuthenticationError(),
                'username' => $authenticationUtils->getLastUsername(),
            ]
        );
    }

    /**
     * Proceeds the user sign out.
     * 
     * Handled by the Security component.
     *
     * @Route("/user/sign-out", name="proceed_user_sign_out")
     */
    public function proceedUserSignOut()
    {
    }
}
