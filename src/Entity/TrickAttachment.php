<?php

namespace App\Entity;

use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * The trick attachment.
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\TrickAttachmentRepository", readOnly=true)
 * @ORM\Table(name="trick_attachment")
 */
class TrickAttachment extends Entity
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
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=20)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @AppAssert\TrickAttachmentMimeType()
     */
    private $type;

    /**
     * @var null|App\Entity\TrickVersion $trickVersion
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
     * @param string $type
     * @param App\Entity\TrickVersion $trickVersion
     */
    public function __construct(
        string       $src,
        string       $type,
        TrickVersion $trickVersion
    ) {
        $this->id           = $this->generateId();
        $this->createdAt    = new \DateTime();
        $this->src          = $src;
        $this->type         = $type;
        $this->trickVersion = $trickVersion;
    }

    /**
     * Gets the src.
     *
     * @return string
     */
    public function getSrc() : string
    {
        return $this->src;
    }

    /**
     * Gets the type.
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Gets the trick version.
     *
     * @return App\Entity\TrickVersion
     */
    public function getTrickVersion() : TrickVersion
    {
        return $this->trickVersion;
    }
}
