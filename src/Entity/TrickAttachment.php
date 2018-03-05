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
 *
 * @todo
 */
class TrickAttachment extends Entity implements TrickAttachmentInterface
{
    /**
     * @var null|string $src
     *
     * @ORM\Column(name="src", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Assert\Url()
     */
    protected $src;

    /**
     * @var null|string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank(groups={"trick_attachment.edit.form"})
     * @Assert\Type(type="string", groups={"trick_attachment.edit.form"})
     * @Assert\Length(max=255, groups={"trick_attachment.edit.form"})
     */
    protected $title;

    /**
     * @var null|string $alt
     *
     * @ORM\Column(name="alt", type="string", length=255)
     * @Assert\NotBlank(groups={"trick_attachment.edit.form"})
     * @Assert\Type(type="string", groups={"trick_attachment.edit.form"})
     * @Assert\Length(max=255, groups={"trick_attachment.edit.form"})
     */
    protected $alt;

    /**
     * @var null|string $type
     *
     * @ORM\Column(name="type", type="string", length=20)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=20)
     * @Assert\Choice(choices={"image/png", "image/jpeg", "image/gif", "video/mp4", "video/embed"})
     */
    protected $type;

    /**
     * @var null|App\Entity\TrickVersionInterface $trickVersion
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickVersion", inversedBy="attachments")
     * @ORM\JoinColumn(name="trick_version_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $trickVersion;

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
    public function setSrc(string $src) : TrickAttachmentInterface
    {
        $this->src = $src;

        return $this;
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
    public function setTitle(string $title) : TrickAttachmentInterface
    {
        $this->title = $title;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * {@inheritdoc}
     */
    public function setAlt(string $alt): TrickAttachmentInterface
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(string $type) : TrickAttachmentInterface
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTrickVersion()
    {
        return $this->trickVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function setTrickVersion(TrickVersionInterface $trickVersion) : TrickAttachmentInterface
    {
        $this->trickVersion = $trickVersion;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toHtml() : string
    {
        if ($this->type === 'video/mp4' || $this->type === 'video/embed') {
            $html = '<embed src="' . $this->src . '" title="' . $this->title . '"' . ($this->type === 'video/embed' ?  '' : ' type="' . $this->type . '"') . ' />';

        } else {
            $html = '<img src="' . $this->type . '" title="' . $this->title . '" alt="' . $this->alt . '" />';
        }

        return $html;
    }
}
