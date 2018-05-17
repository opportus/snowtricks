<?php

namespace App\Entity;

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
 * @ORM\Table(name="user_token")
 */
class UserToken extends Entity implements UserTokenInterface
{
    /**
     * @var string $key
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected $key;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=20)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=20)
     * @Assert\Choice(choices={"activation", "password_reset"})
     */
    protected $type;

    /**
     * @var int $ttl
     *
     * @ORM\Column(name="ttl", type="smallint")
     * @Assert\NotNull()
     * @Assert\Type(type="integer")
     * @Assert\Range(min=1, max=32767)
     */
    protected $ttl;

    /**
     * @var App\Entity\UserInterface $user
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tokens")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $user;

    /**
     * Constructs the user token.
     *
     * @param App\Entity\UserInterface $user
     * @param string $type
     * @param int $ttl
     */
    public function __construct(UserInterface $user, string $type, int $ttl = 24)
    {
        $this->id        = $this->generateId();
        $this->createdAt = new \DateTime();
        $this->key       = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        $this->type      = $type;
        $this->ttl       = $ttl;
        $this->user      = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getKey() : string
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getTtl() : int
    {
        return $this->ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser() : UserInterface
    {
        return $this->user;
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
    public function hasKey(string $token) : bool
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

