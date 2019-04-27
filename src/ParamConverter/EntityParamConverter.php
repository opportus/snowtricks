<?php

namespace App\ParamConverter;

use App\HttpKernel\ControllerException;
use App\Configuration\ControllerResultDataAccessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\AbstractType;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * The entity param converter.
 *
 * @version 0.0.1
 * @package App\ParamConverter
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class EntityParamConverter extends AbstractParamConverter implements ParamConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $this->normalizeConfiguration($configuration);

        if (null === $configuration->getOptions()['form_type']) {
            $entity = $this->getEntityFromRequest($configuration, $request);
        } else {
            $form = $this->createForm($configuration, $request);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entity = $this->getEntityFromForm($configuration, $form);
            } else {
                throw new ControllerException(Response::HTTP_BAD_REQUEST, $form);
            }
        }

        $request->attributes->set($configuration->getName(), $entity);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        if ('app.entity_param_converter' !== $configuration->getConverter()) {
            return false;
        }

        if (null === $configuration->getClass()) {
            return false;
        }

        if (isset($configuration->getOptions()['form_type'])) {
            if (!\is_string($configuration->getOptions()['form_type'])) {
                return false;
            }
            
            if (!\is_subclass_of($configuration->getOptions()['form_type'], AbstractType::class)) {
                return false;
            }

            if (!isset($configuration->getOptions()['form_options']['method']) || 'GET' !== $configuration->getOptions()['form_options']['method']) {
                return false;
            }

            if (!isset($configuration->getOptions()['id']) ||
                !\is_object($configuration->getOptions()['id']) ||
                !$configuration->getOptions()['id'] instanceof ControllerResultDataAccessorInterface
            ) {
                return false;
            }

            if (!isset($configuration->getOptions()['repository_method'])) {
                return false;
            }
        }

        return true;
    }
}
