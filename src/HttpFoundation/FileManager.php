<?php

namespace App\HttpFoundation;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * The file manager.
 *
 * @version 0.0.1
 * @package App\HttpFoundation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class FileManager implements FileManagerInterface
{
    /**
     * @var string $fileBasePath
     */
    private $fileBasePath;

    /**
     * @var string $fileBaseUri
     */
    private $fileBaseUri;

    /**
     * @var Symfony\Component\HttpFoundation\File\File[] $writeFilePool
     */
    private $writeFilePool = [];

    /**
     * Constructs the file manager.
     *
     * @param string $fileBasePath
     * @param string $fileBaseUri
     */
    public function __construct(string $fileBasePath, string $fileBaseUri)
    {
        $this->fileBasePath = \rtrim($fileBasePath, \DIRECTORY_SEPARATOR).\DIRECTORY_SEPARATOR;
        $this->fileBaseUri = \rtrim($fileBaseUri, '/').'/';
    }

    /**
     * {@inheritdoc}
     */
    public function addToWriteFilePool(File $file): string
    {
        $fileName = \md5(\uniqid()).'.'.$file->guessExtension();
        $fileUri = $this->fileBaseUri.$fileName;

        $this->writeFilePool[$fileUri] = $file;

        return $fileUri;
    }

    /**
     * {@inheritdoc}
     */
    public function writeFile(string $fileUri): bool
    {
        if (false === \array_key_exists($fileUri, $this->writeFilePool)) {
            return false;
        }

        if (false === $fileName = $this->getFileNameFromFileUri($fileUri)) {
            return false;
        }

        return (bool) $this->writeFilePool[$fileUri]->move($this->fileBasePath, $fileName);
    }

    /**
     * {@inheritdoc}
     */
    public function removeFile(string $fileUri): bool
    {
        if (false === $fileName = $this->getFileNameFromFileUri($fileUri)) {
            return false;
        }

        return \unlink($this->fileBasePath.$fileName);
    }

    /**
     * Gets the file name form the file URI.
     * 
     * @param string $fileUri
     * @return string|bool
     */
    private function getFileNameFromFileUri(string $fileUri)
    {
        $fileName = \substr($fileUri, \strrpos($fileUri, '/') + 1);

        return empty($fileName) ? false : $fileName;
    }
}
