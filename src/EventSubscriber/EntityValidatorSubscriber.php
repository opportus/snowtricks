<?php

namespace App\EventSubscriber;

use App\Validator\ValidatorInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * The entity validator subscriber...
 *
 * @version 0.0.1
 * @package App\EventSubscriber
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class EntityValidatorSubscriber implements EventSubscriber
{
    /**
     * @var App\Validator\ValidatorInterface $validator
     */
    protected $validator;

    /**
     * Constructs the validator subscriber.
     *
     * @param App\Validator\ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'preDelete'
        );
    }

    /**
     * Validates entity before CREATE operation.
     *
     * @param  Doctrine\Common\Persistence\Event\LifecycleEventArgs $args
     * @throws Symfony\Component\Validator\Exception\ValidatorException
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->validator->validateWithExceptionAndLog($args->getEntity());
    }

    /**
     * Validates entity before UPDATE operation.
     *
     * @param  Doctrine\Common\Persistence\Event\LifecycleEventArgs $args
     * @throws Symfony\Component\Validator\Exception\ValidatorException
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->validator->validateWithExceptionAndLog($args->getEntity());
    }

    /**
     * Validates entity before DELETE operation.
     *
     * @param  Doctrine\Common\Persistence\Event\LifecycleEventArgs $args
     * @throws Symfony\Component\Validator\Exception\ValidatorException
     */
    public function preDelete(LifecycleEventArgs $args)
    {
        $this->validator->validateWithExceptionAndLog($args->getEntity());
    }
}

