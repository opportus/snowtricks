<?php

namespace App\Repository;

use App\Entity\UserToken;
use App\Entity\UserTokenInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * The user token repository...
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserTokenRepository extends ServiceEntityRepository implements UserTokenRepositoryInterface
{
    use EntityRepositoryTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserToken::class);

    }

    /**
     * {@inheritdoc}
     */
    public function findOneByKey(string $key) : ?UserTokenInterface
    {
        return $this->findOneBy(
            array(
                'key' => $key
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function findAllByUserId(int $userId) : array
    {
        return $this->findBy(
            array(
                'user' => $userId
            )
        );
    }
}

