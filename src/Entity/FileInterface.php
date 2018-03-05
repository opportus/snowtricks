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
    public function getPath() : ?string;

    /**
     * Sets the path.
     *
     * @param  string $directory
     * @param  null|string $name
     * @return App\Entity\FileInterface
     */
    public function setPath(string $directory, ?string $name) : FileInterface;

    /**
     * Gets the name.
     *
     * @return null|string
     */
    public function getName() : ?string;

    /**
     * Gets the extension.
     *
     * @return null|string
     */
    public function getExtension() : ?string;

    /**
     * Gets the directory.
     *
     * @return null|string
     */
    public function getDirectory() : ?string;

    /**
     * Gets the mime type.
     *
     * @return null|string
     */
    public function getMimeType() : ?string;

    /**
     * Gets the uploader.
     *
     * @return null|App\Entity\UserInterface
     */
    public function getUploader() : ?UserInterface;

    /**
     * Sets the uploader.
     *
     * @param  App\Entity\UserInterface $uploader
     * @return App\Entity\FileInterface
     */
    public function setUploader(UserInterface $uploader) : FileInterface;

    /**
     * Gets the file manager.
     *
     * @return null|Symfony\Component\HttpFoundation\File\File
     */
    public function getFileManager() : ?FileManager;

    /**
     * Sets the file manager.
     *
     * @param  Symfony\Component\HttpFoundation\File\File $fileManager
     * @return App\Entity\FileInterface
     */
    public function setFileManager(FileManager $fileManager) : FileInterface;

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
