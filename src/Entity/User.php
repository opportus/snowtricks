<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * The user...
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends Entity implements UserInterface
{
    /**
     * @var null|string $username
     *
     * @ORM\Column(name="username", type="string", length=35, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=35)
     */
    protected $username;

    /**
     * @var null|string $email
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Assert\Email()
     */
    protected $email;

    /**
     * @var null|string $plainPassword
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=4096)
     */
    protected $plainPassword;

    /**
     * @var null|string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected $password;

    /**
     * @var null|string $salt
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected $salt;

    /**
     * @var null|bool $activation
     *
     * @ORM\Column(name="activation", type="boolean")
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     */
    protected $activation;

    /**
     * @var array $roles
     *
     * @ORM\Column(name="roles", type="array")
     * @Assert\NotBlank()
     * @Assert\Type(type="array")
     */
    protected $roles;

    /**
     * @var Doctrine\Common\Collections\Collection $tokens
     *
     * @ORM\OneToMany(targetEntity="App\Entity\UserToken", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    protected $tokens;

    /**
     * Constructs the user.
     */
    public function __construct()
    {
        parent::__construct();

        $this->activation = false;
        $this->roles      = array();
        $this->tokens     = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername(string $username) : UserInterface
    {
        $this->username = $username;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail() : ?string
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail(string $email) : UserInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPlainPassword() : ?string
    {
        return $this->plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function setPlainPassword(string $plainPassword) : UserInterface
    {
        $this->password      = null;
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function setPassword(string $password) : UserInterface
    {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function setSalt(string $salt) : UserInterface
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getActivation() : bool
    {
        return $this->activation;
    }

    /**
     * {@inheritdoc}
     */
    public function setActivation(bool $activation) : UserInterface
    {
        $this->activation = $activation;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function addRole(string $role) : UserInterface
    {
        $role = strtoupper($role);

        if (! in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole(string $role) : UserInterface
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole(string $role) : bool
    {
        return in_array(strtoupper($role), $this->roles);
    }

    /**
     * {@inheritdoc}
     */
    public function getTokens() : array
    {
        return $this->tokens->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getActivationToken() : ?UserTokenInterface
    {
        return $this->getToken('activation');
    }

    /**
     * {@inheritdoc}
     */
    public function getPasswordResetToken() : ?UserTokenInterface
    {
        return $this->getToken('password_reset');
    }

    /**
     * Gets the token.
     *
     * @return null|App\Entity\UserTokenInterface
     */
    protected function getToken($type) : ?UserTokenInterface
    {
        $criteria = new Criteria();

        return $this->tokens->matching(
            $criteria->where(
                $criteria->expr()->eq('type', $type)
            )

        )->get(0);
    }

    /**
     * {@inheritdoc}
     */
    public function addActivationToken() : UserInterface
    {
        $token = new UserToken();

        $token->setType('activation');

        $this->addToken($token);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addPasswordResetToken() : UserInterface
    {
        $token = new UserToken();

        $token->setType('password_reset');

        $this->addToken($token);

        return $this;
    }

    /**
     * Adds the token.
     *
     * @param  App\Entity\UserTokenInterface
     * @return App\Entity\UserInterface
     */
    protected function addToken(UserTokenInterface $token) : UserInterface
    {
        $token->setUser($this);

        if ($this->tokens->contains($token) === false) {
            $this->tokens->add($token);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGravatar(?int $size = 80, ?string $imageSet = 'mm', ?string $rating = 'g') : ?string
    {
        if ($this->email === null) {
            return null;
        }

        return 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($this->email))).'?s='.$size.'&d='.$imageSet.'&r='.$rating;
    }

    /**
     * {@inheritdoc}
     */
    public function enable() : UserInterface
    {
        $this->activation = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function disable() : UserInterface
    {
        $this->activation = false;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return (bool) $this->activation;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return (bool) $this->activation;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return (bool) $this->activation;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return (bool) $this->activation;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->salt,
            $this->activation,
            $this->createdAt,
            $this->roles,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->salt,
            $this->activation,
            $this->createdAt,
            $this->roles
        ) = unserialize($serialized);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString() : string
    {
        return (string) $this->username;
    }
}

