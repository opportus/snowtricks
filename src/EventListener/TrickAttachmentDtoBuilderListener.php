<?php

namespace App\EventListener;

use App\HttpFoundation\FileUploaderInterface;
use Symfony\Component\Form\FormEvent;

/**
 * The trick attachment DTO builder listener.
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickAttachmentDtoBuilderListener
{
    /**
     * @var App\HttpFoundation\FileUploaderInterface $fileUploader
     */
    private $fileUploader;

    /**
     * Constructs the trick attachment DTO builder listener.
     * 
     * @param App\HttpFoundation\FileUploaderInterface $fileUploader
     */
    public function __construct(FileUploaderInterface $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    /**
     * Builds the trick attachment DTO.
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

        if ($form->get('embed')->getViewData()) {
            $data->type = 'video/embed';
            $data->src = $form->get('embed')->getViewData();
        } elseif ($form->get('upload')->getViewData()) {
            $file = $form->get('upload')->getViewData();

            $data->type = $file->getMimeType();

            $file = $form->get('upload')->getViewData();

            $data->src = $this->fileUploader->upload($file, 'trick-attachments');
        }
    }
}
