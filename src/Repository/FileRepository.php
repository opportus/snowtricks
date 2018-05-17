<?php

namespace App\Repository;

use App\Entity\File;
use App\Entity\FileInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * The file repository...
 *
 * @version 0.0.1
 * @package App\Repository
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class FileRepository extends ServiceEntityRepository implements FileRepositoryInterface
{
    use EntityRepositoryTrait;

    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, File::class);

    }

    /**
     * {@inheritdoc}
     */
    public function findOneByPath(string $path) : ?FileInterface
    {
        return $this->findOneBy(
            array(
                'path' => $path
            )
        );
    }
}

