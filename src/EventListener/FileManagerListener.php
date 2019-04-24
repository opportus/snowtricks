<?php

namespace App\EventListener;

use App\HttpFoundation\FileManagerInterface;
use App\HttpKernel\ControllerResultInterface;
use App\HttpKernel\ControllerException;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

/**
 * The file manager listener.
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class FileManagerListener
{
    /**
     * @var App\HttpFoundation\FileManagerInterface $fileManager
     */
    private $fileManager;

    /**
     * Constructs the file manager listener.
     *
     * @param App\HttpFoundation\FileManagerInterface $fileManager
     */
    public function __construct(FileManagerInterface $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * Uploads files on kernel view.
     * 
     * @param Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        if (!\is_object($event->getControllerResult()) ||
            !$event->getControllerResult() instanceof ControllerResultInterface ||
            $event->getControllerResult() instanceof ControllerException
        ) {
            return;
        }

        $this->fileManager->uploadPool();
    }
}
