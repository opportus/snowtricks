<?php

namespace App\Entity;

use Doctirne\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * The user token...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserTokenRepository")
 * @ORM\Table(name="user_token")
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity(fields={"key"}, groups={"CREATE", "UPDATE"})
 */
class UserToken implements UserTokenInterface
{
    /**
     * @var null|int $id
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     *
     * @Assert\NotNull(groups={"UPDATE", "DELETE"})
     * @Assert\Type(type="integer", groups={"UPDATE", "DELETE"})
     * @Assert\Range(min=1, groups={"UPDATE", "DELETE"})
     */
    protected $id;

    /**
     * @var null|string $key
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(groups={"CREATE", "user.activate.email", "user.reset_password.email"})
     * @Assert\Type(type="string", groups={"CREATE", "UPDATE", "user.activate.email", "user.reset_password.email"})
     * @Assert\Length(max=255, groups={"CREATE", "UPDATE", "user.activate.email", "user.reset_password.email"})
     */
    protected $key;

    /**
     * @var null|string $type
     *
     * @ORM\Column(name="type", type="string", length=35)
     *
     * @Assert\NotBlank(groups={"CREATE"})
     * @Assert\Type(type="string", groups={"CREATE", "UPDATE"})
     * @Assert\Length(max=35, groups={"CREATE", "UPDATE"})
     * @Assert\Choice(choices={"activation", "password_reset"}, groups={"CREATE", "UPDATE"})
     */
    protected $type;

    /**
     * @var null|int $ttl
     *
     * @ORM\Column(name="ttl", type="smallint")
     *
     * @Assert\NotNull(groups={"CREATE", "user.activate.email", "user.reset_password.email"})
     * @Assert\Type(type="integer", groups={"CREATE", "UPDATE", "user.activate.email", "user.reset_password.email"})
     * @Assert\Range(min=1, groups={"CREATE", "UPDATE", "user.activate.email", "user.reset_password.email"})
     */
    protected $ttl;

    /**
     * @var null|\Datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Assert\NotNull(groups={"CREATE"})
     * @Assert\Type(type="object", groups={"CREATE", "UPDATE"})
     * @Assert\DateTime(groups={"CREATE", "UPDATE"})
     */
    protected $createdAt;

    /**
     * @var null|App\Entity\UserInterface $user
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tokens")
     *
     * @Assert\NotNull(groups={"CREATE"})
     * @Assert\Valid(groups={"CREATE", "UPDATE"})
     */
    protected $user;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId(int $id) : UserTokenInterface
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     *
     * @ORM\PrePersist
     */
    public function setKey() : UserTokenInterface
    {
        $this->key = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

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
    public function setType(string $type) : UserTokenInterface
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * {@inheritdoc}
     *
     * @ORM\PrePersist
     */
    public function setTtl() : UserTokenInterface
    {
        $this->ttl = 24;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     *
     * @ORM\PrePersist
     */
    public function setCreatedAt() : UserTokenInterface
    {
        $this->createdAt = new \Datetime();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(UserInterface $user) : UserTokenInterface
    {
        $this->user = $user;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isExpired() : bool
    {
        return
            $this->getCreatedAt() !== null &&
            $this->getTtl() !== null &&
            $this->getCreatedAt()->diff(new \Datetime())->h > $this->getTtl()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(string $token) : bool
    {
        return hash_equals((string) $this, $token);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString() : string
    {
        return (string) $this->getKey();
    }
}

