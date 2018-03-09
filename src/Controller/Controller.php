<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
    protected $authorizationchecker;

    /**
     * @var Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    protected $formFactory;

    /**
     * @var Symfony\Component\Routing\RouterInterface $router
     */
    protected $router;

    /**
     * Constructs the app controller.
     *
     * @param array $parameters
     * @param Doctrine\ORM\EntityManagerInterface $entityManager
     * @param Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(
        array                         $parameters,
        EntityManagerInterface        $entityManager,
        TokenStorageInterface         $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        FormFactoryInterface          $formFactory,
        RouterInterface               $router
    )
    {
        $this->entityManager        = $entityManager;
        $this->tokenStorage         = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->formFactory          = $formFactory;
        $this->router               = $router;

        foreach ($parameters as $routeName => $routeParameters) {
            if (isset($routeParameters['form_options']['action'])) {
                $parameters[$routeName]['form_options']['action'] = $this->router->generate($routeParameters['form_options']['action']);
            }
        }

        $this->parameters = $parameters;
    }
}

