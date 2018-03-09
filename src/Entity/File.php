<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File as FileManager;

/**
 * The file...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 * @ORM\Table(name="file")
 *
 * @todo Implement Doctrine file manager subscriber.
 */
class File extends Entity implements FileInterface
{
    /**
     * @var null|string $path
     *
     * @ORM\Column(name="path", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected $path;

    /**
     * @var null|App\Entity\UserInterface $uploader
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="uploader_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $uploader;

    /**
     * @var null|Symfony\Component\HttpFoundation\File\File $fileManager
     *
     * @Assert\NotBlank()
     * @Assert\File(mimeTypes={"image/png", "image/jpeg", "image/gif", "video/mp4"})
     */
    protected $fileManager;

    /**
     * {@inheritdoc}
     */
    public function getPath() : ?string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath(string $directory, ?string $name = null) : FileInterface
    {
        $name      = $name === null ? md5(uniqid()) : $name;
        $extension = $this->fileManager->guessExtension();

        $this->path = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . $name . '.' . $extension;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() : ?string
    {
        return $this->fileManager->getFilename() === ''
            ? null
            : $this->fileManager->getFilename()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension() : ?string
    {
        return $this->fileManager->getExtension() === ''
            ? null
            : $this->fileManager->getExtension()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getDirectory() : ?string
    {
        return $this->fileManager->getPath() === ''
            ? null
            : $this->fileManager->getPath()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType() : ?string
    {
        return $this->fileManager->getMimeType();
    }

    /**
     * {@inheritdoc}
     */
    public function getUploader() : ?UserInterface
    {
        return $this->uploader;
    }

    /**
     * {@inheritdoc}
     */
    public function setUploader(UserInterface $uploader) : FileInterface
    {
        $this->uploader = $uploader;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFileManager() : FileManager
    {
        return $this->fileManager;
    }

    /**
     * {@inheritdoc}
     */
    public function setFileManager(FileManager $fileManager) : FileInterface
    {
        $this->fileManager = $fileManager;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function move(string $directory) : FileInterface
    {
        $this->setPath($directory, $this->getName());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function rename(string $name) : FileInterface
    {
        $this->setPath($this->getDirectory(), $name);

        return $this;
    }
}
