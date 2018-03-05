<?php

namespace App\Repository;

use App\Entity\TrickAttachment;
use App\Entity\TrickAttachmentInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * The trick attachment repository...
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickAttachmentRepository extends ServiceEntityRepository implements TrickAttachmentRepositoryInterface
{
    use EntityRepositoryTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TrickAttachment::class);

    }
}

