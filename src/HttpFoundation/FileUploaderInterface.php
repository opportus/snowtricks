<?php

namespace App\HttpFoundation;

use Symfony\Component\HttpFoundation\File\File;

/**
 * The file uploader interface.
 *
 * @version 0.0.1
 * @package App\HttpFoundation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface FileUploaderInterface
{
    /**
     * Uploads a file.
     * 
     * @param Symfony\Component\HttpFoundation\File\File $file
     * @param string $dir A directory name relative to the upload base directory
     * @return string The URI of the uploaded file
     */
    public function upload(File $file, string $dir): string;
}
