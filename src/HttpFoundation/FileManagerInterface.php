<?php

namespace App\HttpFoundation;

use Symfony\Component\HttpFoundation\File\File;

/**
 * The file manager interface.
 *
 * @version 0.0.1
 * @package App\HttpFoundation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface FileManagerInterface
{
    /**
     * Adds the file to the write pool.
     * 
     * @param Symfony\Component\HttpFoundation\File\File $file
     * @return string The URI of the file
     */
    public function addToWriteFilePool(File $file): string;

    /**
     * Writes the file that has been previously added to the pool.
     * 
     * @param string $fileUri
     * @return bool
     * @throws Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function writeFile(string $fileUri): bool;

    /**
     * Removes the file.
     * 
     * @param string $fileUri
     * @return bool
     */
    public function removeFile(string $fileUri): bool;
}
