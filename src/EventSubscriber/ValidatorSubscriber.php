<?php

namespace App\EventSubscriber;

use App\Entity\EntityInterface;
use App\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * The validator subscriber...
 *
 * @version 0.0.1
 * @package App\EventSubscriber
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ValidatorSubscriber implements EventSubscriber
{
    /**
     * @var App\Validator\ValidatorInterface $validator
     */
    protected $validator;

    /**
     * @var Psr\Log\LoggerInterface $logger
     */
    protected $logger;

    /**
     * Constructs the validator subscriber.
     *
     * @param App\Validator\ValidatorInterface $validator
     * @param Psr\Log\LoggerInterface $logger
     */
    public function __construct(ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->validator = $validator;
        $this->logger    = $logger;
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
     * @param Doctrine\Common\Persistence\Event\LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->validator->validateWithExceptionAndLog($args->getEntity());
    }

    /**
     * Validates entity before UPDATE operation.
     *
     * @param Doctrine\Common\Persistence\Event\LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->validator->validateWithExceptionAndLog($args->getEntity());
    }

    /**
     * Validates entity before DELETE operation.
     *
     * @param Doctrine\Common\Persistence\Event\LifecycleEventArgs $args
     */
    public function preDelete(LifecycleEventArgs $args)
    {
        $this->validator->validateWithExceptionAndLog($args->getEntity());
    }
}

