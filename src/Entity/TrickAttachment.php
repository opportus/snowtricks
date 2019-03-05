<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The trick attachment...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\TrickAttachmentRepository")
 * @ORM\Table(name="trick_attachment")
 */
class TrickAttachment extends Entity implements TrickAttachmentInterface
{
    /**
     * @var string $src
     *
     * @ORM\Column(name="src", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Assert\Url()
     */
    private $src;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    private $title;

    /**
     * @var null|App\Entity\TrickVersionInterface $trickVersion
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickVersion", inversedBy="attachments")
     * @ORM\JoinColumn(name="trick_version_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    private $trickVersion;

    /**
     * Constructs the attachment.
     *
     * @param string $src
     * @param string $title
     * @param App\Entity\TrickVersionInterface $trickVersion
     */
    public function __construct(
        string               $src,
        string               $title,
        string               $alt,
        string               $type,
        TrckVersionInterface $trickVersion
    ) {
        $this->id           = $this->generateId();
        $this->createdAt    = new \DateTime();
        $this->src          = $src;
        $this->title        = $title;
        $this->trickVersion = $version;
    }

    /**
     * {@inheritdoc}
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function getTrickVersion()
    {
        return $this->trickVersion;
    }
}
