<?php

namespace App\ParamConverter;

use App\Exception\InvalidSubmittedEntityException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;
use Opportus\ObjectMapper\ObjectMapperInterface;

/**
 * The submitted entity param converter.
 *
 * @version 0.0.1
 * @package App\ParamConverter
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class SubmittedEntityParamConverter implements ParamConverterInterface
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
     * @var Opportus\ObjectMapper\ObjectMapperInterface $objectMapper
     */
    private $objectMapper;

    /**
     * Constructs the submitted entity param converter.
     *
     * @param Doctrine\ORM\EntityManagerInterface $entityManager
     * @param Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param Opportus\ObjectMapper\ObjectMapperInterface $objectMapper
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        AuthorizationCheckerInterface $authorizationChecker,
        ObjectMapperInterface $objectMapper
    ) {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->authorizationChecker = $authorizationChecker;
        $this->objectMapper = $objectMapper;
    }

    /**
     * {@inheritdoc}
     * 
     * @throws App\Exception\InvalidSubmittedEntityException
     * @throws Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $entityClass = $configuration->getClass();
        $options = $configuration->getOptions();
        $idKey = $options['id'] ?? 'id';
        $formDataClass = $options['form_options']['data_class'] ?? null;
        $formOptions = $options['form_options'] ?? [];
        $formType = $options['form_type'];
        $formKey = $options['form_name'] ?? 'submittedEntityForm';
        $formMethod = $request->attributes->get('_method');
        $grant = $options['grant'] ?? false;
        $repositoryMethod = $options['repository_method'] ?? null;
        $doObjectMapping = !(null === $formDataClass || $entityClass === $formDataClass);

        if ('GET' === $formMethod || null === $request->attributes->get($idKey)) {
            $formData = null;
        } else {
            if ($repositoryMethod) {
                $entity = $this->entityManager->getRepository($entityClass)->{$repositoryMethod}($request->attributes->get($idKey));
            } else {
                $entity = $this->entityManager->getRepository($entityClass)->findOneBy([$idKey => $request->attributes->get($idKey)]);
            }

            if ($grant && !$this->authorizationChecker->isGranted($grant, $entity)) {
                throw new AccessDeniedException(\sprintf('The current user cannot access to the subject of type "%s".', $entityClass));
            }

            if ($doObjectMapping) {
                $formData = $this->objectMapper->map($entity, $formDataClass);
            } else {
                $formData = $entity;
            }
        }

        $form = $this->formFactory->create($formType, $formData, $formOptions);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ('GET' === $form->getConfig()->getMethod()) {
                if ($repositoryMethod) {
                    $entity = $this->entityManager->getRepository($entityClass)->{$repositoryMethod}($form->get($idKey)->getData());
                } else {
                    $entity = $this->entityManager->getRepository($entityClass)->findOneBy([$idKey => $form->get($idKey)->getData()]);
                }
            } else {
                if ($doObjectMapping) {
                    if (isset($entity)) {
                        $entity = $this->objectMapper->map($form->getData(), $entity);
                    } else {
                        $entity = $this->objectMapper->map($form->getData(), $entityClass);
                    }
                } else {
                    $entity = $form->getData();
                }
            }

            $request->attributes->set($configuration->getName(), $entity);
        }

        $request->attributes->set($formKey, $form);

        if ($request->attributes->get($configuration->getName())) {
            return true;
        }

        throw new InvalidSubmittedEntityException('Invalid submitted entity.');
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        if ('app.submitted_entity_param_converter' !== $configuration->getConverter()) {
            return false;
        }

        if (null === $configuration->getClass()) {
            return false;
        }

        if (!isset($configuration->getOptions()['form_type']) || !\is_subclass_of($configuration->getOptions()['form_type'], AbstractType::class)) {
            return false;
        }

        return true;
    }
}
