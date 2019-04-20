<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * The user repository.
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
    public function findOneByUsername(string $username) : ?User
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
    public function findOneByUsernameOrThrowExceptionIfNoResult(string $username) : User
    {
        $user = $this->findOneByUsername($username);

        if (null === $user) {
            throw new EntityNotFoundException('No entity matches this set of criteria');
        }

        return $user;
    }
}
