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
     * Adds a file to the upload pool.
     * 
     * @param Symfony\Component\HttpFoundation\File\File $file
     * @return string The URI of the uploaded file
     */
    public function addToUploadPool(File $file): string;

    /**
     * Uploads the pool.
     * 
     * @throws Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function uploadPool();
}
