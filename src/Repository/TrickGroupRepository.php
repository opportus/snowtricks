<?php

namespace App\Repository;

use App\Entity\TrickGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * The trick group repository.
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickGroupRepository extends ServiceEntityRepository implements TrickGroupRepositoryInterface
{
    use EntityRepositoryTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TrickGroup::class);

    }

    /**
     * {@inheritdoc}
     */
    public function findOneBySlug(string $slug) : ?TrickGroup
    {
        return $this->findOneBy(
            array(
                'slug' => $slug
            )
        );
    }
}
