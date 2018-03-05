<?php

namespace App\Repository;

use App\Entity\TrickVersion;
use App\Entity\TrickVersionInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * The trick version repository...
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickVersionRepository extends ServiceEntityRepository implements TrickVersionRepositoryInterface
{
    use EntityRepositoryTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TrickVersion::class);

    }
}

