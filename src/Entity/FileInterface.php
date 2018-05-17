<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File as FileManager;

/**
 * The file interface...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface FileInterface extends EntityInterface
{
    /**
     * Gets the path.
     *
     * @return null|string
     */
    public function getPath() : string;

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Gets the extension.
     *
     * @return string
     */
    public function getExtension() : string;

    /**
     * Gets the directory.
     *
     * @return string
     */
    public function getDirectory() : string;

    /**
     * Gets the mime type.
     *
     * @return string
     */
    public function getMimeType() : string;

    /**
     * Gets the uploader.
     *
     * @return App\Entity\UserInterface
     */
    public function getUploader() : UserInterface;

    /**
     * Gets the file manager.
     *
     * @return Symfony\Component\HttpFoundation\File\File
     */
    public function getFileManager() : FileManager;

    /**
     * Moves.
     *
     * @param  string $directory
     * @return App\Entity\FileInterface
     */
    public function move(string $directory) : FileInterface;

    /**
     * Renames.
     *
     * @param  string $name
     * @return App\Entity\FileInterface
     */
    public function rename(string $name) : FileInterface;
}
