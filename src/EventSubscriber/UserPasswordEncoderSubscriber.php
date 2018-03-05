<?php

namespace App\EventSubscriber;

use App\Entity\UserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * The user password encoder subscriber...
 *
 * @version 0.0.1
 * @package App\EventSubscriber
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserPasswordEncoderSubscriber implements EventSubscriber
{
    /**
     * @var Symfony\Component\Security\Core\Encoder\EncoderFactoyInterface $encoderFactory
     */
    protected $encoderFactory;

    /**
     * Constructs the user password encoder subscriber.
     *
     * @param Symfony\Component\Security\Core\Encoder\EncoderFactoyInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate'
        );
    }

    /**
     * Encodes the password before CREATE operation.
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof UserInterface && $args->getEntity()->getPlainPassword() !== null) {
            $this->encodePassword($args->getEntity());
        }
    }

    /**
     * Encodes the password before UPDATE operation.
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof UserInterface && $args->getEntity()->getPlainPassword() !== null) {
            $this->encodePassword($args->getEntity());
        }
    }

    /**
     * Encodes the password.
     *
     * @param App\Entity\UserInterface $user
     */
    protected function encodePassword(UserInterface $user)
    {
        $encoder = $this->encoderFactory->getEncoder($user);

        if (($encoder instanceof BCryptPasswordEncoder) === false) {
            $user->setSalt(base64_encode(random_bytes(32)));
        }

        $user->setPassword(
            $encoder->encodePassword($user->getPlainPassword(), $user->getSalt())
        );
    }
}

