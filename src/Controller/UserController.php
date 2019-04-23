<?php

namespace App\Controller;

use App;
use App\Entity\User;
use App\Form\Type\UserSignUpType;
use App\Form\Type\UserSignInType;
use App\Form\Type\UserPasswordResetRequestType;
use App\Form\Type\UserPasswordResetType;
use App\Form\Type\UserActivationEmailType;
use App\Form\Data\UserData;
use App\View\TwigViewBuilder;
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
class UserController extends AbstractEntityController
{
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
     *     class=User::class,
     *     converter="app.entity_form_param_converter",
     *     options={
     *         "form_type"=UserSignUpType::class,
     *         "form_options"={
     *             "data_class"=UserData::class,
     *             "method"="POST"
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder=TwigViewBuilder::class,
     *         options={
     *             "template"="user/sign_up.html.twig"
     *         }
     *     )
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
     *     class=User::class,
     *     converter="app.submitted_entity_param_converter",
     *     options={
     *         "form_type"=UserSignUpType::class,
     *         "form_options"={
     *             "data_class"=UserData::class,
     *             "method"="POST",
     *             "validation_groups"= {"user.form.sign_up"}
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     headers={
     *         "location"=@App\Annotation\Route(name="get_home")
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_BAD_REQUEST,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder=TwigViewBuilder::class,
     *         options={
     *             "template"="user/sign_up.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     message=@App\Annotation\Trans(
     *         id="user.sign_up.notification.201",
     *         parameters={
     *             "%username%"=@App\Annotation\DatumGetterReference(name="getUsername"),
     *             "%email%"=@App\Annotation\DatumGetterReference(name="getEmail")
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
     *     class=User::class,
     *     converter="app.submitted_entity_param_converter",
     *     options={
     *         "form_type"=UserActivationEmailType::class,
     *         "form_options"={
     *             "data_class"=UserData::class,
     *             "method"="PATCH",
     *             "csrf_protection"=false
     *         },
     *         "grant"="PATCH"
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     headers={
     *         "location"=@App\Annotation\Route(name="get_home")
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_FORBIDDEN,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder=TwigViewBuilder::class,
     *         options={
     *             "template"="error/forbidden.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NOT_FOUND,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder=TwigViewBuilder::class,
     *         options={
     *             "template"="error/not-found.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     message=@App\Annotation\Trans(id="user.activation.notification.303")
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_FORBIDDEN,
     *     message=@App\Annotation\Trans(id="user.activation.notification.403")
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_NOT_FOUND,
     *     message=@App\Annotation\Trans(id="user.activation.notification.404")
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
     *     class=User::class,
     *     converter="app.entity_form_param_converter",
     *     options={
     *         "form_type"=UserPasswordResetRequestType::class,
     *         "form_options"={
     *             "data_class"=UserData::class,
     *             "method"="GET"
     *         }
     *     }
     * )
     *
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder=TwigViewBuilder::class,
     *         options={
     *             "template"="user/password_reset_request.html.twig"
     *         }
     *     )
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
     *     class=User::class,
     *     converter="app.entity_param_converter",
     *     options={
     *         "id"=@App\Annotation\DatumPropertyReference(name="username"),
     *         "form_type"=UserPasswordResetRequestType::class,
     *         "form_options"={
     *             "data_class"=UserData::class,
     *             "method"="GET",
     *             "validation_groups"={"user.form.password_reset_request"}
     *         },
     *         "repository_method"="findOneByUsername"
     *     }
     * )
     *
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     headers={
     *         "location"=@App\Annotation\Route(name="get_home")
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_BAD_REQUEST,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder=TwigViewBuilder::class,
     *         options={
     *             "template"="user/password_reset_request.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     message=@App\Annotation\Trans(id="user.password_reset_request.notification.303")
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
     *     class=User::class,
     *     converter="app.entity_form_param_converter",
     *     options={
     *         "form_type"=UserPasswordResetType::class,
     *         "form_options"={
     *             "data_class"=UserData::class,
     *             "method"="PATCH"
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder=TwigViewBuilder::class,
     *         options={
     *             "template"="user/password_reset.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NOT_FOUND,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder=TwigViewBuilder::class,
     *         options={
     *             "template"="user/password_reset.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_NOT_FOUND,
     *     message=@App\Annotation\Trans(id="user.password_reset.notification.404")
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
     *     class=User::class,
     *     converter="app.submitted_entity_param_converter",
     *     options={
     *         "form_type"=UserPasswordResetType::class,
     *         "form_options"={
     *             "data_class"=UserData::class,
     *             "method"="PATCH",
     *             "validation_groups"= {"user.form.password_reset"}
     *         },
     *         "grant"="PATCH"
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     headers={
     *         "location"=@App\Annotation\Route(name="get_home")
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_BAD_REQUEST,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder=TwigViewBuilder::class,
     *         options={
     *             "template"="user/password_reset.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_FORBIDDEN,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder=TwigViewBuilder::class,
     *         options={
     *             "template"="error/forbidden.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_NOT_FOUND,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder=TwigViewBuilder::class,
     *         options={
     *             "template"="error/not_found.html.twig"
     *         }
     *     )
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_SEE_OTHER,
     *     message=@App\Annotation\Trans(id="user.password_reset.notification.303")
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_FORBIDDEN,
     *     message=@App\Annotation\Trans(id="user.password_reset.notification.403")
     * )
     * 
     * @App\Annotation\Flash(
     *     statusCode=Response::HTTP_NOT_FOUND,
     *     message=@App\Annotation\Trans(id="user.password_reset.notification.404")
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
     *     class=User::class,
     *     converter="app.entity_form_param_converter",
     *     options={
     *         "form_type"=UserSignInType::class,
     *         "form_options"={
     *             "data_class"=UserData::class,
     *             "method"="POST"
     *         }
     *     }
     * )
     * 
     * @App\Annotation\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder=TwigViewBuilder::class,
     *         options={
     *             "template"="user/sign_in.html.twig"
     *         }
     *     )
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
