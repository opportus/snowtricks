<?php

namespace App\ParamConverter;

use App\HttpKernel\ControllerException;
use App\Configuration\ControllerResultDataFetcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;
use Opportus\ObjectMapper\ObjectMapperInterface;

/**
 * The abstract param converter.
 *
 * @version 0.0.1
 * @package App\ParamConverter
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
abstract class AbstractParamConverter
{
    /**
     * @var Doctrine\ORM\EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    private $formFactory;

    /**
     * @var Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     */
    private $authorizationChecker;

    /**
     * @var Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    private $validator;

    /**
     * @var Opportus\ObjectMapper\ObjectMapperInterface $objectMapper
     */
    private $objectMapper;

    /**
     * @var App\Configuration\ControllerResultDataFetcherInterface $controllerResultDataFetcher
     */
    private $controllerResultDataFetcher;


    /**
     * Constructs the abstract form aware param converter.
     *
     * @param Doctrine\ORM\EntityManagerInterface $entityManager
     * @param Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param Opportus\ObjectMapper\ObjectMapperInterface $objectMapper
     * @param App\Configuration\ControllerResultDataFetcherInterface $controllerResultDataFetcher
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        AuthorizationCheckerInterface $authorizationChecker,
        ValidatorInterface $validator,
        ObjectMapperInterface $objectMapper,
        ControllerResultDataFetcherInterface $controllerResultDataFetcher
    ) {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->authorizationChecker = $authorizationChecker;
        $this->validator = $validator;
        $this->objectMapper = $objectMapper;
        $this->controllerResultDataFetcher = $controllerResultDataFetcher;
    }

    /**
     * Creates the form.
     * 
     * @param Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $config
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\Form\FormInterface
     * @throws App\HttpKernel\ControllerException
     */
    protected function createForm(ParamConverter $config, Request $request): FormInterface
    {
        $formType = $config->getOptions()['form_type'];
        $formOptions = $config->getOptions()['form_options'];

        if (!$this->mustSetFormData($config, $request)) {
            $formData = null;
        } else {
            try {
                $entity = $this->getEntityFromRequest($config, $request);
            } catch (ControllerException $controllerException) {
                throw new ControllerException(
                    $controllerException->getStatusCode(),
                    $this->formFactory->create($formType, null, $formOptions)
                );
            }

            if ($this->mustCheckEntityAccessAuthorization($config)) {
                $this->checkEntityAccessAuthorization($config, $entity);
            }

            if ($this->mustMapEntityToFormData($config)) {
                $formData = $this->objectMapper->map($entity, $config->getOptions()['form_options']['data_class']);
            } else {
                $formData = $entity;
            }
        }

        return $this->formFactory->create($formType, $formData, $formOptions);
    }

    /**
     * Gets the entity from a request.
     * 
     * @param Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $config
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return object
     * @throws App\HttpKernel\ControllerException
     */
    protected function getEntityFromRequest(ParamConverter $config, Request $request): object
    {
        if (null === $config->getOptions()['repository_method']) {
            $entity = $this->entityManager->getRepository($config->getClass())
                ->findOneBy([
                    $config->getOptions()['id'] => $request->attributes->get($config->getOptions()['id'])
                ])
            ;
        } else {
            $entity = $this->entityManager->getRepository($config->getClass())
                ->{$config->getOptions()['repository_method']}($request->attributes->get($config->getOptions()['id']))
            ;
        }

        if (null === $entity) {
            throw new ControllerException(Response::HTTP_NOT_FOUND);
        }

        return $entity;
    }

    /**
     * Gets the entity from a form.
     * 
     * @param Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $config
     * @param Symfony\Component\Form\FormInterface $form
     * @return object
     * @throws App\HttpKernel\ControllerException
     */
    protected function getEntityFromForm(ParamConverter $config, FormInterface $form): object
    {
        $id = $this->controllerResultDataFetcher->fetch($config->getOptions()['id'], $form->getData());

        $entity = $this->entityManager->getRepository($config->getClass())
            ->{$config->getOptions()['repository_method']}($id)
        ;

        if (null === $entity) {
            throw new ControllerException(Response::HTTP_NOT_FOUND);
        }

        return $entity;
    }

    /**
     * Gets the entity collection from a request.
     * 
     * @param Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $config
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return mixed
     * @throws App\HttpKernel\ControllerException
     */
    protected function getEntityCollectionFromRequest(ParamConverter $config, Request $request)
    {
        $limit = $request->query->getInt($config->getOptions()['limit'], 10);
        $offset = ($request->query->getInt($config->getOptions()['page'], 1) - 1) * $limit;
        $order = $request->query->get($config->getOptions()['order'], []);
        $criteria = array_map(
            function ($value) {
                if ($value === '') {
                    return null;
                } else {
                    return $value;
                }
            },
            $request->query->get($config->getOptions()['attribute'], [])
        );

        $args = [$criteria, $order, $limit, $offset];

        if (null === $config->getOptions()['repository_method']) {
            $entityCollection = $this->entityManager->getRepository($config->getClass())->findBy(...$args);
        } else {
            $entityCollection = $this->entityManager->getRepository($config->getClass())->{$config->getOptions()['repository_method']}(...$args);
        }

        if (0 >= \count($entityCollection)) {
            throw new ControllerException(Response::HTTP_NOT_FOUND);
        }

        return $entityCollection;
    }

    /**
     * Maps objects.
     * 
     * @param object|array $sources
     * @param object|array|string $targets
     * @return object|array
     */
    protected function map($sources, $targets)
    {
        return $this->objectMapper->map($sources, $targets);
    }

    /**
     * Checks the entity access authorization.
     * 
     * @param Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $config
     * @param object $entity
     * @throws App\HttpKernel\ControllerException
     */
    protected function checkEntityAccessAuthorization(ParamConverter $config, object $entity)
    {
        if (!$this->authorizationChecker->isGranted($config->getOptions()['grant'], $entity)) {
            throw new ControllerException(Response::HTTP_FORBIDDEN, $entity);
        }
    }

    /**
     * Validates the query.
     * 
     * @param Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $config
     * @param Symfony\Component\HttpFoundation\Request $request
     * @throws App\HttpKernel\ControllerException
     */
    protected function validateQuery(ParamConverter $config, Request $request)
    {
        $constraint = $config->getOptions()['query_constraint'];
        $constraintViolationList = $this->validator->validate($request->query->all(), new $constraint());

        if (0 !== \count($constraintViolationList)) {
            throw new ControllerException(Response::HTTP_BAD_REQUEST, $constraintViolationList);
        }
    }

    /**
     * Defines whether the param converter must set the form data.
     * 
     * @param Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $config
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    protected function mustSetFormData(ParamConverter $config, Request $request): bool
    {
        return 'GET' !== $config->getOptions()['form_options']['method'] && null !== $request->attributes->get($config->getOptions()['id']);
    }

    /**
     * Defines whether the param converter must check the entity access authorization.
     * 
     * @param Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $config
     * @return bool
     */
    protected function mustCheckEntityAccessAuthorization(ParamConverter $config): bool
    {
        return null !== $config->getOptions()['grant'];
    }
    
    /**
     * Defines whether the param converter must map the entity to the form data.
     * 
     * @param Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $config
     * @return bool
     */
    protected function mustMapEntityToFormData(ParamConverter $config): bool
    {
        return null !== $config->getOptions()['form_options']['data_class'] && $config->getClass() !== $config->getOptions()['form_options']['data_class'];
    }

    /**
     * Defines whether the param converter must validate the query.
     * 
     * @param Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $config
     * @return bool
     */
    protected function mustValidateQuery(ParamConverter $config): bool
    {
        return null !== $config->getOptions()['query_constraint'];
    }

    /**
     * Normalizes the param converter configuration.
     * 
     * @param Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $config
     * @return Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter
     */
    protected function normalizeConfiguration(ParamConverter $config): ParamConverter
    {
        $options = $config->getOptions();

        $options['id'] = $options['id'] ?? 'id';
        $options['grant'] = $options['grant'] ?? null;
        $options['repository_method'] = $options['repository_method'] ?? null;
        $options['form_type'] = $options['form_type'] ?? null;
        $options['form_name'] = $options['form_name'] ?? 'form';
        $options['form_options'] = $options['form_options'] ?? [];
        $options['form_options']['data_class'] = $options['form_options']['data_class'] ?? null;
        $options['query_constraint'] = $options['query_constraint'] ?? null;
        $options['limit'] = $options['limit'] ?? 'limit';
        $options['page'] = $options['page'] ?? 'page';
        $options['order'] = $options['order'] ?? 'order';
        $options['attribute'] = $options['attribute'] ?? 'attribute';

        $config->setOptions($options);

        return $config;
    }
}
