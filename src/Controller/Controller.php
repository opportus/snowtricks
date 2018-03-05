<?php

namespace App\Controller;

use App\Events;
use App\Event\ActionEvent;
use App\Event\ActionEventInterface;
use App\Validator\ValidatorInterface;
use App\Mailer\MailerInterface;
use App\Exception\Http\HttpException;
use App\Exception\Http\ClientException;
use App\Exception\Http\ServerException;
use App\Exception\Http\BadRequestException;
use App\Exception\Http\ForbiddenException;
use App\Exception\Http\NotFoundException;
use App\Exception\Http\ConflictException;
use App\Exception\Http\LockedException;
use App\Exception\Http\InternalServerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Twig_Environment;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The controller...
 *
 * @version 0.0.1
 * @package App\Controller
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
abstract class Controller extends AbstractController
{
    /**
     * @var array $parameters
     */
    protected $parameters;

    /**
     * @var Doctrine\ORM\EntityManagerInterface $entityManager
     */
    protected $entityManager;

    /**
     * @var Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    protected $tokenStorage;

    /**
     * @var Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     */
    protected $authorizationChecker;

    /**
     * @var Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrfTokenManager
     */
    protected $csrfTokenManager;

    /**
     * @var Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    protected $formFactory;

    /**
     * @var Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var Symfony\Component\Routing\RouterInterface $router
     */
    protected $router;

    /**
     * @var Symfony\Component\Translation\TranslatorInterface $translator
     */
    protected $translator;

    /**
     * @var App\Validator\ValidatorInterface $validator
     */
    protected $validator;

    /**
     * @var Psr\Log\LoggerInterface $logger
     */
    protected $logger;

    /**
     * @var App\Mailer\MailerInterface $mailer
     */
    protected $mailer;

    /**
     * @var Twig_Environment $twig
     */
    protected $twig;

    /**
     * Constructs the app controller.
     *
     * @param array $parameters
     * @param Doctrine\ORM\EntityManagerInterface $entityManager
     * @param Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrfTokenManager
     * @param Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param Symfony\Component\Routing\RouterInterface $router
     * @param Symfony\Component\Translation\TranslatorInterface $translator
     * @param App\Validator\ValidatorInterface $validator
     * @param Psr\Log\LoggerInterface $logger
     * @param App\Mailer\MailerInterface $mailer
     * @param Twig_Environment $twig
     */
    public function __construct(
        array                         $parameters,
        EntityManagerInterface        $entityManager,
        TokenStorageInterface         $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        CsrfTokenManagerInterface     $csrfTokenManager,
        FormFactoryInterface          $formFactory,
        EventDispatcherInterface      $eventDispatcher,
        RouterInterface               $router,
        TranslatorInterface           $translator,
        ValidatorInterface            $validator,
        LoggerInterface               $logger,
        MailerInterface               $mailer,
        Twig_Environment              $twig
    )
    {
        $this->parameters           = $parameters;
        $this->entityManager        = $entityManager;
        $this->tokenStorage         = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->csrfTokenManager     = $csrfTokenManager;
        $this->formFactory          = $formFactory;
        $this->eventDispatcher      = $eventDispatcher;
        $this->router               = $router;
        $this->translator           = $translator;
        $this->validator            = $validator;
        $this->logger               = $logger;
        $this->mailer               = $mailer;
        $this->twig                 = $twig;
    }

    /**
     * Proceeds the request.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    protected function proceedRequest(Request $request) : Response
    {
        $action = new ActionEvent($request);

        $this->eventDispatcher->dispatch(
            Events::ACTION_REQUEST,
            $action
        );

        $this->operate($action);

        $this->respond($action);

        $this->eventDispatcher->dispatch(
            Events::ACTION_RESPONSE,
            $action
        );

        return $action->getResponse();
    }

    /**
     * Operates.
     *
     * @param  App\Event\ActionEventInterface $action
     * @return App\Event\ActionEventInterface
     */
    protected function operate(ActionEventInterface $action) : ActionEventInterface
    {
        $operation = 'operate' . ucfirst($action->getCamelName()) . 'Action';

        try {
            $action->setOperationResults(
                $this->$operation($action->getRequest())
            );

        } catch (HttpException $httpException) {
            $action->setStatus($httpException->getCode());

            if ($httpException instanceof ServerException) {
                $this->logger->critical(
                    sprintf(
                        'A %s has occurred during a %s action',
                        get_class($httpException),
                        $action->getFullName()
                    ),
                    array(
                        'action' => $action,
                    )
                );
            }
        }

        switch ($action->getRequest()->getMethod()) {
            case 'GET':
                $action->setStatus(200);
            break;
            case 'POST':
                $action->setStatus(201);
            break;
            case 'PATCH':
                $action->setStatus(201);
            break;
            case 'DELETE':
                $action->setStatus(204);
            break;
        }

        return $action;
    }

    /**
     * Responds.
     *
     * @param  App\Event\ActionEventInterface $action
     * @return App\Event\ActionEventInterface
     */
    protected function respond(ActionEventInterface $action) : ActionEventInterface
    {
        $status = $action->getStatus();
        $format = $action->getRequest()->getRequestFormat();

        if (isset($this->parameters[$action->getSnakeName()]['template'][$format])) {
            $template = $this->parameters[$action->getSnakeName()]['template'][$format];
        }

        if (isset($this->parameters[$action->getSnakeName()]['redirection'][$format][$status])) {
            $redirection = $this->parameters[$action->getSnakeName()]['redirection'][$format][$status];
        }

        // Redirect response...
        if (isset($redirection)) {
            $response = new RedirectResponse(
                $this->router->generate($redirection)
            );

        // HTML response...
        } elseif ($format === 'html') {
            if (isset($template)) {
                $response = new Response(
                    $this->twig->render(
                        $template,
                        $action->getOperationResults()
                    ),
                    $status
                );
            }

        // JSON response...
        } elseif ($format === 'json') {
            if (isset($template)) {
                $response = new JsonResponse(
                    array(
                        'html' => $this->twig->render(
                            $template,
                            $action->getOperationResults()
                        )
                    ),
                    $status
                );

            } else {
                // Serialize operation results to JSON...
            }
        }

        return $action->setResponse($response);
    }
}

