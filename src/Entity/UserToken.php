<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * The user token.
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserTokenRepository", readOnly=true)
 * @ORM\Table(name="user_token")
 */
class UserToken extends Entity
{
    const ACTIVATION_TYPE = 'activation';
    const PASSWORD_RESET_TYPE = 'password_reset';

    /**
     * @var string $key
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    private $key;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=20)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=20)
     * @Assert\Choice(choices={"activation", "password_reset"})
     */
    private $type;

    /**
     * @var int $ttl
     *
     * @ORM\Column(name="ttl", type="smallint")
     * @Assert\NotNull()
     * @Assert\Type(type="integer")
     * @Assert\Range(min=1, max=32767)
     */
    private $ttl;

    /**
     * @var App\Entity\User $user
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tokens")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    private $user;

    /**
     * Constructs the user token.
     *
     * @param App\Entity\User $user
     * @param string $type
     * @param null|int $ttl
     */
    public function __construct(User $user, string $type, ?int $ttl = 24)
    {
        $this->id = $this->generateId();
        $this->createdAt = new \DateTime();
        $this->key = \rtrim(\strtr(\base64_encode(\random_bytes(32)), '+/', '-_'), '=');
        $this->user = $user;
        $this->type = $type;
        $this->ttl = $ttl ?? 24;
    }

    /**
     * Gets the key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Gets the type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Gets the TTL.
     *
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * Gets the user.
     *
     * @return App\Entity\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Checks whether the token is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->createdAt->diff(new \DateTime())->h > $this->ttl;
    }

    /**
     * Returns the key.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->key;
    }
}
