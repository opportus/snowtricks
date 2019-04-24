<?php

namespace App\EventSubscriber;

use App\Entity\TrickAttachment;
use App\HttpFoundation\FileManagerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * The file manager subscriber.
 *
 * @version 0.0.1
 * @package App\EventSubscriber
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class FileManagerSubscriber implements EventSubscriber
{
    /**
     * @var App\HttpFoundation\FileManagerInterface $fileManager
     */
    private $fileManager;

    /**
     * Constructs the file manager subscriber.
     *
     * @param App\HttpFoundation\FileManagerInterface $fileManager
     */
    public function __construct(FileManagerInterface $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            'postPersist',
            'postRemove'
        ];
    }

    /**
     * Writes on filesystem after CREATE operation.
     *
     * @param Doctrine\Common\Persistence\Event\LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        if (!$args->getEntity() instanceof TrickAttachment) {
            return;
        }

        $this->fileManager->writeFile($args->getEntity()->getSrc());
    }

    /**
     * Writes on filesystem after DELETE operation.
     *
     * @param Doctrine\Common\Persistence\Event\LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        if (!$args->getEntity() instanceof TrickAttachment) {
            return;
        }

        $this->fileManager->removeFile($args->getEntity()->getSrc());
    }
}
