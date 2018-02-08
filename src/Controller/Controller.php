<?php

namespace App\Controller;

use App\Validator\ValidatorInterface;
use App\Mailer\MailerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Twig_Environment;

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
    protected $mamiler;

    /**
     * @var Twig_Environment $twig
     */
    protected $twig;

    /**
     * Constructs the app controller.
     *
     * @param array $parameters
     * @param Doctrine\ORM\EntityManagerInterface $entityManager
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
        array                    $parameters,
        EntityManagerInterface   $entityManager,
        FormFactoryInterface     $formFactory,
        EventDispatcherInterface $eventDispatcher,
        RouterInterface          $router,
        TranslatorInterface      $translator,
        ValidatorInterface       $validator,
        LoggerInterface          $logger,
        MailerInterface          $mailer,
        Twig_Environment         $twig
    )
    {
        $this->parameters      = $parameters;
        $this->entityManager   = $entityManager;
        $this->formFactory     = $formFactory;
        $this->eventDispatcher = $eventDispatcher;
        $this->router          = $router;
        $this->translator      = $translator;
        $this->validator       = $validator;
        $this->logger          = $logger;
        $this->mailer          = $mailer;
        $this->twig            = $twig;
    }
}

