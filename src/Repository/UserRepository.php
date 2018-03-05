<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * The user repository...
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    use EntityRepositoryTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);

    }

    /**
     * {@inheritdoc}
     */
    public function findOneByUsername(string $username) : ?UserInterface
    {
        return $this->findOneBy(
            array(
                'username' => $username
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByEmail(string $email) : ?UserInterface
    {
        return $this->findOneBy(
            array(
                'email' => $email
            )
        );
    }
}

