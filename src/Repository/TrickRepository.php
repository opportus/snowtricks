<?php

namespace App\Repository;

use App\Entity\Trick;
use App\Entity\TrickInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * The trick repository...
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickRepository extends ServiceEntityRepository implements TrickRepositoryInterface
{
    use EntityRepositoryTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trick::class);

    }

    /**
     * {@inheritdoc}
     */
    public function findOneBySlug(string $slug) : ?TrickInterface
    {
        return $this->findOneBy(
            array(
                'slug' => $slug
            )
        );
    }
}

