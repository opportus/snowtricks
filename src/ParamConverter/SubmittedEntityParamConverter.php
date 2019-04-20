<?php

namespace App\ParamConverter;

use App\HttpKernel\ControllerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
class SubmittedEntityParamConverter extends AbstractParamConverter implements ParamConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $this->normalizeConfiguration($configuration);

        $form = $this->createForm($configuration, $request);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->mustMapEntityToFormData($configuration)) {
                if ($this->mustSetFormData($configuration, $request)) {
                    $entity = $this->getEntityFromRequest($configuration, $request);
                    $entity = $this->map($form->getData(), $entity);
                } else {
                    $entity = $this->map($form->getData(), $configuration->getClass());
                }
            } else {
                $entity = $form->getData();
            }

            $request->attributes->set($configuration->getName(), $entity);
        } else {
            throw new ControllerException(Response::HTTP_BAD_REQUEST, $form);
        }

        return true;
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
