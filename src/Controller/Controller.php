<?php

namespace App\Controller;

use App\HttpKernel\ControllerResult;
use App\HttpFoundation\ResponseFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @var App\HttpFoundation\ResponseFactoryInterface $responseFactory
     */
    protected $responseFactory;

    /**
     * Constructs the app controller.
     *
     * @param array $parameters
     * @param Doctrine\ORM\EntityManagerInterface $entityManager
     * @param Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param App\HttpFoudation\ResponseFactoryInterface $responseFactory
     */
    public function __construct(
        array                    $parameters,
        EntityManagerInterface   $entityManager,
        FormFactoryInterface     $formFactory,
        ResponseFactoryInterface $responseFactory
    )
    {
        $this->parameters      = $parameters;
        $this->entityManager   = $entityManager;
        $this->formFactory     = $formFactory;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Gets list.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    protected function getList(Request $request) : Response
    {
        $parameters = $this->resolveActionParameters($request);

        $entityClass = $parameters['entity']['class'];

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

        $entities = $this->entityManager->getRepository($entityClass)->findBy(
            $criteria,
            $order,
            $limit,
            $offset
        );

        if (empty($entities)) {
            return $this->responseFactory->createResponse(
                $request,
                new ControllerResult(404)
            );
        }

        return $this->responseFactory->createResponse(
            $request,
            new ControllerResult(
                200,
                array('entities' => $entities)
            )
        );
    }

    /**
     * Gets.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    protected function get(Request $request) : Response
    {
        $parameters = $this->resolveActionParameters($request);

        $entityClass = $parameters['entity']['class'];
        $entityId    = $parameters['entity']['id'];

        $entity = $this->entityManager->getRepository($entityClass)->findOneBy(
            array($entityId => $request->attributes->get($entityId))
        );

        if ($entity === null) {
            return $this->responseFactory->createResponse(
                $request,
                new ControllerResult(404)
            );
        }

        return $this->responseFactory->createResponse(
            $request,
            new ControllerResult(
                200,
                array('entity' => $entity)
            )
        );
    }

    /**
     * Posts.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    protected function post(Request $request) : Response
    {
        $parameters = $this->resolveActionParameters($request);

        $entityClass = $parameters['entity']['class'];
        $formClass   = $parameters['form']['class'];
        $formOptions = $parameters['form']['options'];

        $entity = new $entityClass();

        $form = $this->formFactory->create(
            $formClass,
            $entity,
            $formOptions
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return $this->responseFactory->createResponse(
                $request,
                new ControllerResult(
                    201,
                    array('entity' => $entity)
                )
            );

        } elseif ($form->isSubmitted()) {
            return $this->responseFactory->createResponse(
                $request,
                new ControllerResult(
                    400,
                    array('form' => $form->createView())
                )
            );
        }
    }

    /**
     * Puts.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    protected function put(Request $request) : Response
    {
        $parameters = $this->resolveActionParameters($request);

        $entityClass = $parameters['entity']['class'];
        $entityId    = $parameters['entity']['id'];
        $formClass   = $parameters['form']['class'];
        $formOptions = $parameters['form']['options'];

        $entity = $this->entityManager->getRepository($entityClass)->findOneBy(
            array($entityId => $request->attributes->get($entityId))
        );

        if ($entity === null) {
            return $this->responseFactory->createResponse($request, 404);
        }

        $form = $this->formFactory->create(
            $formClass,
            $entity,
            $formOptions
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->responseFactory->createResponse(
                $request,
                new ControllerResult(
                    204,
                    array('entity' => $entity)
                )
            );

        } elseif ($form->isSubmitted()) {
            return $this->responseFactory->createResponse(
                $request,
                new ControllerResult(
                    400,
                    array('form' => $form->createView())
                )
            );
        }
    }

    /**
     * Patches.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    protected function patch(Request $request) : Response
    {
        $parameters = $this->resolveActionParameters($request);

        $entityClass = $parameters['entity']['class'];
        $entityId    = $parameters['entity']['id'];
        $formClass   = $parameters['form']['class'];
        $formOptions = $parameters['form']['options'];

        $entity = $this->entityManager->getRepository($entityClass)->findOneBy(
            array($entityId => $request->attributes->get($entityId))
        );

        if ($entity === null) {
            return $this->responseFactory->createResponse($request, 404);
        }

        $form = $this->formFactory->create(
            $formClass,
            $entity,
            $formOptions
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->responseFactory->createResponse(
                $request,
                new ControllerResult(
                    204,
                    array('entity' => $entity)
                )
            );

        } elseif ($form->isSubmitted()) {
            return $this->responseFactory->createResponse(
                $request,
                new ControllerResult(
                    400,
                    array('form' => $form->createView())
                )
            );
        }
    }

    /**
     * Deletes.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    protected function delete(Request $request) : Response
    {
        $parameters = $this->resolveActionParameters($request);

        $entityClass = $parameters['entity']['class'];
        $entityId    = $parameters['entity']['id'];
        $formClass   = $parameters['form']['class'];
        $formOptions = $parameters['form']['options'];

        $entity = $this->entityManager->getRepository($entityClass)->findOneBy(
            array($entityId => $request->attributes->get($entityId))
        );

        if ($entity === null) {
            return $this->responseFactory->createResponse($request, 404);
        }

        $form = $this->formFactory->create(
            $formClass,
            $entity,
            $formOptions
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();

            return $this->responseFactory->createResponse(
                $request,
                new ControllerResult(
                    204,
                    array('entity' => $entity)
                )
            );

        } elseif ($form->isSubmitted()) {
            return $this->responseFactory->createResponse(
                $request,
                new ControllerResult(
                    400,
                    array('form' => $form->createView())
                )
            );
        }
    }

    /**
     * Proceeds.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function proceed(Request $request) : Response
    {
        $parameters = $this->resolveActionParameters($request);

        $entityClass = $parameters['entity']['class'];
        $entityId    = $parameters['entity']['id'];
        $formClass   = $parameters['form']['class'];
        $formOptions = $parameters['form']['options'];

        $entity = new $entityClass();

        $form = $this->formFactory->create(
            $formClass,
            $entity,
            $formOptions
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->responseFactory->createResponse(
                $request,
                new ControllerResult(
                    202,
                    array('entity' => $entity)
                )
            );

        } elseif ($form->isSubmitted()) {
            return $this->responseFactory->createResponse(
                $request,
                new ControllerResult(
                    400,
                    array('form' => $form->createView())
                )
            );
        }
    }

    /**
     * Gets form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getForm(Request $request) : Response
    {
        $parameters = $this->resolveActionParameters($request);

        $entityClass = $parameters['entity']['class'];
        $entityId    = $parameters['entity']['id'];
        $formClass   = $parameters['form']['class'];
        $formOptions = $parameters['form']['options'];

        $entity = $this->entityManager->getRepository($entityClass)->findOneBy(
            array($entityId => $request->attributes->get($entityId))
        );

        if ($entity === null) {
            return $this->responseFactory->createResponse(
                $request,
                new ControllerResult(404)
            );
        }

        $form = $this->formFactory->create(
            $formClass,
            $entity,
            $formOptions
        );

        return $this->responseFactory->createResponse(
            $request,
            new ControllerResult(
                200,
                array('form' => $form->createView())
            )
        );
    }

    /**
     * Gets empty form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getEmptyForm(Request $request) : Response
    {
        $parameters = $this->resolveActionParameters($request);

        $entityClass = $parameters['entity']['class'];
        $formClass   = $parameters['form']['class'];
        $formOptions = $parameters['form']['options'];

        $entity = new $entityClass();
        $form   = $this->formFactory->create(
            $formClass,
            $entity,
            $formOptions
        );

        return $this->responseFactory->createResponse(
            $request,
            new ControllerResult(
                200,
                array('form' => $form->createView())
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
        if (empty($this->parameters)) {
            $parameters = array();

        } else {
            $action = $request->attributes->get('_controller');

            $parameters = isset($this->parameters[$action]) ? $this->parameters[$action] : array();
        }

        $entityParameters      = isset($parameters['entity'])      ? $parameters['entity']      : array();
        $formParameters        = isset($parameters['form'])        ? $parameters['form']        : array();
        $formOptionsParameters = isset($formParameters['options']) ? $formParameters['options'] : array();

        $entityParametersResolver = new OptionsResolver();
        $entityParametersResolver->setDefaults(array(
            'class' => str_replace(
                array('\Controller\\', 'Controller'),
                array('\Entity\\',     ''),
                get_class($this)
            ),
            'id' => 'id'
        ));

        $formOptionsParametersResolver = new OptionsResolver();
        $formOptionsParametersResolver->setDefaults(array(
            'action'            => $request->getBasePath(),
            'method'            => $request->getMethod(),
            'validation_groups' => array(),
            'csrf_protection'   => true,
            'csrf_field_name'   => '_token',
            'csrf_token_id'     => 'security',
            'data_class'        => str_replace(
                array('\Controller\\', 'Controller'),
                array('\Entity\\',     ''),
                get_class($this)
            )
        ));

        $formParametersResolver = new OptionsResolver();
        $formParametersResolver->setDefaults(array(
            'class' => str_replace(
                array('\Controller\\', 'Controller'),
                array('\Form\Type\\',  'Type'),
                get_class($this)
            ),
            'options' => $formOptionsParametersResolver->resolve($formOptionsParameters)
        ));

        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            'entity' => $entityParametersResolver->resolve($entityParameters),
            'form'   => $formParametersResolver->resolve($formParameters)
        ));

        return $resolver->resolve($parameters);
    }
}

