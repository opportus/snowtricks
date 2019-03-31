<?php

namespace App\HttpFoundation;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * The file uploader...
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
     * Constructs the file uploader.
     *
     * @param Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    public function __construct(RequestStack $requestStack, KernelInterface $kernel)
    {
        $this->requestStack = $requestStack;
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     */
    public function upload(File $file, string $dir) : string
    {
        $dir = trim($dir, '/ \\');
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move(
            $this->kernel->getRootDir().'/../public/'.$dir,
            $fileName
        );

        return $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost().'/snowtricks.com/public/'.$dir.'/'.$fileName;
    }
}
