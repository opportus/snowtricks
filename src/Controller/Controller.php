<?php

namespace App\Controller;

use App\HttpKernel\ControllerResult;
use App\HttpKernel\ControllerResultInterface;
use Opportus\ObjectMapper\ObjectMapperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The controller...
 *
 * @version 0.0.1
 * @package App\Controller
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
abstract class Controller
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
     * @var Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     */
    protected $authorizationChecker;

    /**
     * @var Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    protected $formFactory;

    /**
     * @var Opportus\ObjectMapper\ObjectMapperInterface
     */
    protected $objectMapper;

    /**
     * Constructs the app controller.
     *
     * @param array $parameters
     * @param Doctrine\ORM\EntityManagerInterface $entityManager
     * @param Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param Opportus\ObjectMapper\ObjectMapperInterface;
     */
    public function __construct(
        array                         $parameters,
        EntityManagerInterface        $entityManager,
        AuthorizationCheckerInterface $authorizationChecker,
        FormFactoryInterface          $formFactory,
        ObjectMapperInterface         $objectMapper
    )
    {
        $this->parameters           = $parameters;
        $this->entityManager        = $entityManager;
        $this->authorizationChecker = $authorizationChecker;
        $this->formFactory          = $formFactory;
        $this->objectMapper         = $objectMapper;
    }

    /**
     * Gets list.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     */
    protected function getList(Request $request) : ControllerResultInterface
    {
        $parameters = $this->resolveActionParameters($request);

        $limit    = 10;
        $offset   = ($request->query->getInt('page', 1) - 1) * $limit;
        $order    = $request->query->get('order', array());
        $criteria = array_map(
            function ($value) {
                if ($value === '') {
                    return null;

                } else {
                    return $value;
                }
            },
            $request->query->get('attribute', array())
        );

        $entities = $this->entityManager->getRepository($parameters['entity']['class'])->findBy(
            $criteria,
            $order,
            $limit,
            $offset
        );

        if (empty($entities)) {
            return new ControllerResult(404);
        }

        foreach ($entities as $key => $entity) {
            if (! $this->authorizationChecker->isGranted('GET', $entity)) {
                unset($entities[$key]);
            }
        }

        if (empty($entities)) {
            return new ControllerResult(403);
        }

        return new ControllerResult(
            200,
            array('entities' => $entities)
        );
    }

    /**
     * Gets.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     */
    protected function get(Request $request) : ControllerResultInterface
    {
        $parameters = $this->resolveActionParameters($request);

        $entity = $this->entityManager->getRepository($parameters['entity']['class'])->findOneBy(
            array($parameters['entity']['id'] => $request->attributes->get($parameters['entity']['id']))
        );

        if ($entity === null) {
            return new ControllerResult(404);
        }

        if (! $this->authorizationChecker->isGranted('GET', $entity)) {
            return new ControllerResult(403);
        }

        return new ControllerResult(
            200,
            array('entity' => $entity)
        );
    }

    /**
     * Posts.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     */
    protected function post(Request $request) : ControllerResultInterface
    {
        $parameters = $this->resolveActionParameters($request);

        $form = $this->formFactory->create(
            $parameters['form']['class'],
            new $parameters['form']['options']['data_class'](),
            $parameters['form']['options']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $this->objectMapper->map($form->getData(), $parameters['entity']['class']);

            if (! $this->authorizationChecker->isGranted('POST', $entity)) {
                return new ControllerResult(403);
            }

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return new ControllerResult(
                201,
                array('entity' => $entity)
            );

        } elseif ($form->isSubmitted()) {
            return new ControllerResult(
                400,
                array(
                    'form'   => $form->createView(),
                    'entity' => $entity,
                )
            );
        }
    }

    /**
     * Puts.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     */
    protected function put(Request $request) : ControllerResultInterface
    {
        $parameters = $this->resolveActionParameters($request);

        $entity = $this->entityManager->getRepository($parameters['entity']['class'])->findOneBy(
            array($parameters['entity']['id'] => $request->attributes->get($parameters['entity']['id']))
        );

        if ($entity === null) {
            return new ControllerResult(404);
        }

        if (! $this->authorizationChecker->isGranted('PUT', $entity)) {
            return new ControllerResult(403);
        }

        $form = $this->formFactory->create(
            $parameters['form']['class'],
            $this->objectMapper->map($entity, $parameters['form']['options']['data_class']),
            $parameters['form']['options']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->objectMapper->map($form->getData(), $entity);

            $this->entityManager->flush();

            return new ControllerResult(
                204,
                array('entity' => $entity)
            );

        } elseif ($form->isSubmitted()) {
            return new ControllerResult(
                400,
                array(
                    'form'   => $form->createView(),
                    'entity' => $entity,
                )
            );
        }
    }

    /**
     * Patches.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     */
    protected function patch(Request $request) : ControllerResultInterface
    {
        $parameters = $this->resolveActionParameters($request);

        $entity = $this->entityManager->getRepository($parameters['entity']['class'])->findOneBy(
            array($parameters['entity']['id'] => $request->attributes->get($parameters['entity']['id']))
        );

        if ($entity === null) {
            return new ControllerResult(404);
        }

        if (! $this->authorizationChecker->isGranted('PATCH', $entity)) {
            return new ControllerResult(403);
        }

        $form = $this->formFactory->create(
            $parameters['form']['class'],
            $this->objectMapper->map($entity, $parameters['form']['options']['data_class']),
            $parameters['form']['options']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->objectMapper->map($form->getData(), $entity);

            $this->entityManager->flush();

            return new ControllerResult(
                204,
                array('entity' => $entity)
            );

        } elseif ($form->isSubmitted()) {
            return new ControllerResult(
                400,
                array(
                    'form'   => $form->createView(),
                    'entity' => $entity,
                )
            );
        }
    }

    /**
     * Deletes.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     */
    protected function delete(Request $request) : ControllerResultInterface
    {
        $parameters = $this->resolveActionParameters($request);

        $entity = $this->entityManager->getRepository($parameters['entity']['class'])->findOneBy(
            array($parameters['entity']['id'] => $request->attributes->get($parameters['entity']['id']))
        );

        if ($entity === null) {
            return new ControllerResult(404);
        }

        if (! $this->authorizationChecker->isGranted('DELETE', $entity)) {
            return new ControllerResult(403);
        }

        $form = $this->formFactory->create(
            $parameters['form']['class'],
            null,
            $parameters['form']['options']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();

            return new ControllerResult(
                204,
                array('entity' => $entity)
            );

        } elseif ($form->isSubmitted()) {
            return new ControllerResult(
                400,
                array(
                    'form'   => $form->createView(),
                    'entity' => $entity,
                )
            );
        }
    }

    /**
     * Proceeds.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     */
    public function proceed(Request $request) : ControllerResultInterface
    {
        $parameters = $this->resolveActionParameters($request);

        $form = $this->formFactory->create(
            $parameters['form']['class'],
            new $parameters['form']['options']['data_class'],
            $parameters['form']['options']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return new ControllerResult(
                202,
                array('form' => $form)
            );

        } elseif ($form->isSubmitted()) {
            return new ControllerResult(
                400,
                array('form' => $form->createView())
            );
        }
    }

    /**
     * Gets form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     */
    public function getForm(Request $request) : ControllerResultInterface
    {
        $parameters = $this->resolveActionParameters($request);

        if ($request->attributes->get($parameters['entity']['id']) !== null && $parameters['form']['options']['data_class'] !== null) {
            $entity = $this->entityManager->getRepository($parameters['entity']['class'])->findOneBy(
                array($parameters['entity']['id'] => $request->attributes->get($parameters['entity']['id']))
            );

            if ($entity === null) {
                return new ControllerResult(404);
            }

            if (! $this->authorizationChecker->isGranted('GET', $entity)) {
                return new ControllerResult(403);
            }
        }

        if (isset($entity)) {
            $data = $this->objectMapper->map($entity, $parameters['form']['options']['data_class']);

        } else {
            $data = null;
        }

        $form = $this->formFactory->create(
            $parameters['form']['class'],
            $data,
            $parameters['form']['options']
        );

        return new ControllerResult(
            200,
            array(
                'form'   => $form->createView(),
                'entity' => isset($entity) ? $entity : null,
            )
        );
    }

    /**
     * Resolves the action parameters.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    protected function resolveActionParameters(Request $request) : array
    {
        $action = $request->attributes->get('_controller');

        $parameters['entity']          = isset($this->parameters[$action]['entity'])          ? $this->parameters[$action]['entity']          : array();
        $parameters['form']            = isset($this->parameters[$action]['form'])            ? $this->parameters[$action]['form']            : array();
        $parameters['form']['options'] = isset($this->parameters[$action]['form']['options']) ? $this->parameters[$action]['form']['options'] : array();

        $parametersResolver = new OptionsResolver();
        $parametersResolver->setDefaults(array(
            'entity' => array(),
            'form'   => array()
        ));

        $entityParametersResolver = new OptionsResolver();
        $entityParametersResolver->setDefaults(array(
            'class' => str_replace(
                array('\Controller\\', 'Controller'),
                array('\Entity\\',     ''),
                get_class($this)
            ),
            'id'    => 'id'
        ));

        $formParametersResolver = new OptionsResolver();
        $formParametersResolver->setDefaults(array(
            'class'   => str_replace(
                array('\Controller\\', 'Controller'),
                array('\Form\Type\\',  'Type'),
                get_class($this)
            ),
            'options' => array()
        ));

        $formOptionsParametersResolver = new OptionsResolver();
        $formOptionsParametersResolver->setDefaults(array(
            'method'            => $request->getMethod(),
            'validation_groups' => array(),
            'csrf_protection'   => true,
            'csrf_field_name'   => '_token',
            'csrf_token_id'     => 'security',
            'data_class'        => null
        ));

        $resolvedParameters                    = $parametersResolver->resolve($parameters);
        $resolvedParameters['entity']          = $entityParametersResolver->resolve($parameters['entity']);
        $resolvedParameters['form']            = $formParametersResolver->resolve($parameters['form']);
        $resolvedParameters['form']['options'] = $formOptionsParametersResolver->resolve($parameters['form']['options']);

        return $resolvedParameters;
    }
}

