<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The user token...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserTokenRepository", readOnly=true)
 * @ORM\Table(name="user_token", uniqueConstraints={@UniqueConstraint(name="user_token_type_idx", columns={"user_id", "type"})})
 */
class UserToken extends Entity implements UserTokenInterface
{
    /**
     * @var null|string $key
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected $key;

    /**
     * @var null|string $type
     *
     * @ORM\Column(name="type", type="string", length=20)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=20)
     * @Assert\Choice(choices={"activation", "password_reset"})
     */
    protected $type;

    /**
     * @var null|int $ttl
     *
     * @ORM\Column(name="ttl", type="smallint")
     * @Assert\NotNull()
     * @Assert\Type(type="integer")
     * @Assert\Range(min=1, max=32767)
     */
    protected $ttl;

    /**
     * @var null|App\Entity\UserInterface $user
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tokens")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $user;

    /**
     * Constructs the user token.
     */
    public function __construct()
    {
        parent::__construct();

        $this->key = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        $this->ttl = 24;
    }

    /**
     * {@inheritdoc}
     */
    public function getKey() : ?string
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function getType() : ?string
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
    public function getTtl() : ?int
    {
        return $this->ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser() : ?UserInterface
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
            $this->createdAt !== null &&
            $this->ttl !== null &&
            $this->createdAt->diff(new \DateTime())->h > $this->ttl
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(string $token) : bool
    {
        return hash_equals($this->key, $token);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString() : string
    {
        return (string) $this->key;
    }
}

