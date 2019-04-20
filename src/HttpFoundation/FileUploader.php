<?php

namespace App\HttpFoundation;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * The file uploader.
 *
 * @version 0.0.1
 * @package App\HttpFoundation
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class FileUploader implements FileUploaderInterface
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
     * Constructs the file uploader.
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
    public function upload(File $file, string $dir): string
    {
        $dir = '' === $dir ? $dir : \DIRECTORY_SEPARATOR.\trim($dir, \DIRECTORY_SEPARATOR);
        $fileName = \md5(\uniqid()).'.'.$file->guessExtension();

        $file->move(
            $this->kernel->getRootDir().$this->uploadBaseDir.$dir,
            $fileName
        );

        $request = $this->requestStack->getCurrentRequest();

        return $request->getSchemeAndHttpHost().$this->uploadBaseDirHttp.$dir.'/'.$fileName;
    }
}
