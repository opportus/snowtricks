<?php

namespace App\ParamConverter;

use App\HttpKernel\ControllerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;

/**
 * The entity collection param converter.
 * 
 * @version 0.0.1
 * @package App\ParamConverter
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class EntityCollectionParamConverter implements ParamConverterInterface
{
    /**
     * @var Doctrine\ORM\EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * Constructs the entity collection param converter.
     *
     * @param Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $entityClass = $configuration->getClass();
        $options = $configuration->getOptions();
        $repositoryMethod = $options['repository_method'] ?? null;
        $limitKey = $options['limit'] ?? 'limit';
        $pageKey = $options['page'] ?? 'page';
        $orderKey = $options['order'] ?? 'order';
        $attributeKey = $options['attribute'] ?? 'attribute';

        $limit = $request->query->getInt($limitKey, 10);
        $offset = ($request->query->getInt($pageKey, 1) - 1) * $limit;
        $order = $request->query->get($orderKey, array());
        $criteria = array_map(
            function ($value) {
                if ($value === '') {
                    return null;
                } else {
                    return $value;
                }
            },
            $request->query->get($attributeKey, array())
        );

        $args = [$criteria, $order, $limit, $offset];

        if (null === $repositoryMethod) {
            $entityCollection = $this->entityManager->getRepository($entityClass)->findBy(...$args);
        } else {
            $entityCollection = $this->entityManager->getRepository($entityClass)->{$repositoryMethod}(...$args);
        }

        if (0 >= \count($entityCollection)) {
            throw new ControllerException(Response::HTTP_NOT_FOUND);
        }

        $request->attributes->set($configuration->getName(), $entityCollection);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        if ('app.entity_collection_param_converter' !== $configuration->getConverter()) {
            return false;
        }

        if (null === $configuration->getClass()) {
            return false;
        }

        return true;
    }
}
