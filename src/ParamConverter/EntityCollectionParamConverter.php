<?php

namespace App\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * The entity collection param converter.
 * 
 * @version 0.0.1
 * @package App\ParamConverter
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class EntityCollectionParamConverter extends AbstractParamConverter implements ParamConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $this->normalizeConfiguration($configuration);
        
        if ($this->mustValidateQuery($configuration)) {
            $this->validateQuery($configuration, $request);
        }

        $entityCollection = $this->getEntityCollectionFromRequest($configuration, $request);

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

        if (isset($configuration->getOptions()['query_constraint'])) {
            if (!\is_string($configuration->getOptions()['query_constraint'])) {
                return false;
            }

            if (!\class_exists($configuration->getOptions()['query_constraint'])) {
                return false;
            }

            $constraintClassReflection = new \ReflectionClass($configuration->getOptions()['query_constraint']);

            if (null !== $constraintClassReflection->getConstructor() && 0 !== $constraintClassReflection->getConstructor()->getNumberOfRequiredParameters()) {
                return false;
            }
        }

        return true;
    }
}
