<?php

namespace App\ParamConverter;

use App\Exception\InvalidSubmittedEntityException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * The submitted entity param converter.
 *
 * @version 0.0.1
 * @package App\ParamConverter
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class SubmittedEntityParamConverter extends AbstractFormAwareParamConverter implements ParamConverterInterface
{
    /**
     * {@inheritdoc}
     * 
     * @throws App\Exception\InvalidSubmittedEntityException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $this->normalizeConfiguration($configuration);

        $form = $this->createForm($configuration, $request);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ('GET' === $form->getConfig()->getMethod()) {
                $entity = $this->getEntity($configuration, $request);
            } else {
                if ($this->mustMapEntityToFormData($configuration)) {
                    if ($this->mustSetFormData($configuration, $request)) {
                        $entity = $this->getEntity($configuration, $request);
                        $entity = $this->objectMapper->map($form->getData(), $entity);
                    } else {
                        $entity = $this->objectMapper->map($form->getData(), $configuration->getClass());
                    }
                } else {
                    $entity = $form->getData();
                }
            }

            $request->attributes->set($configuration->getName(), $entity);
        }

        $request->attributes->set($configuration->getOptions()['form_name'], $form);

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
