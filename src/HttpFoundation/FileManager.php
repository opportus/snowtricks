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
     * @var Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    private $requestStack;

    /**
     * @var Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    private $kernel;

    /**
     * @var string $uploadBaseDir
     */
    private $uploadBaseDir;

    /**
     * @var string $uploadBaseDirHttp
     */
    private $uploadBaseDirHttp;

    /**
     * @var callable[] $uploadPool
     */
    private $uploadPool = [];

    /**
     * Constructs the file manager.
     *
     * @param Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param string $uploadBaseDir Relative to the root dir of the kernel
     * @param string $uploadBaseDirHttp
     */
    public function __construct(RequestStack $requestStack, KernelInterface $kernel, string $uploadBaseDir = '', string $uploadBaseDirHttp = '')
    {
        $this->requestStack = $requestStack;
        $this->kernel = $kernel;
        $this->uploadBaseDir = '' === $uploadBaseDir ? $uploadBaseDir : \DIRECTORY_SEPARATOR.\trim($uploadBaseDir, \DIRECTORY_SEPARATOR);
        $this->uploadBaseDirHttp = '' === $uploadBaseDirHttp ? $uploadBaseDirHttp : '/'.\trim($uploadBaseDirHttp, '/');
    }

    /**
     * {@inheritdoc}
     */
    public function addToUploadPool(File $file): string
    {
        $fileBasePath = $this->kernel->getRootDir().$this->uploadBaseDir;
        $fileName = \md5(\uniqid()).'.'.$file->guessExtension();

        $this->uploadPool[] = function () use ($file, $fileBasePath, $fileName) {
            $file->move($fileBasePath, $fileName);
        };

        return $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost().$this->uploadBaseDirHttp.'/'.$fileName;
    }

    /**
     * {@inheritdoc}
     */
    public function uploadPool()
    {
        foreach ($this->uploadPool as $upload) {
            $upload();
        }
    }
}
