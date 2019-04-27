<?php

namespace App\EventListener;

use App\HttpFoundation\FileManagerInterface;
use Symfony\Component\Form\FormEvent;

/**
 * The trick attachment data builder listener.
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickAttachmentDataBuilderListener
{
    /**
     * @var App\HttpFoundation\FileManagerInterface $fileManager
     */
    private $fileManager;

    /**
     * Constructs the trick attachment data builder listener.
     * 
     * @param App\HttpFoundation\FileManagerInterface $fileManager
     */
    public function __construct(FileManagerInterface $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * Builds the trick attachment data.
     *
     * @param Symfony\Component\Form\FormEvent $event
     */
    public function onFormSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if ($data->src) {
            return;
        }

        if ($embed = $form->get('embed')->getViewData()) {
            $data->type = 'video/embed';
            $data->src = $embed;
        } elseif ($file = $form->get('upload')->getViewData()) {
            $data->type = $file->getMimeType();
            $data->src = $this->fileManager->addToWriteFilePool($file);
        }
    }
}
