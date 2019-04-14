<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * The user.
 *
 * @version 0.0.1
 * @package App\Entity
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends Entity implements AdvancedUserInterface, \Serializable
{
    /**
     * @var string $username
     *
     * @ORM\Column(name="username", type="string", length=35, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=35)
     */
    protected $username;

    /**
     * @var string $email
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
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    protected $password;

    /**
     * @var bool $activation
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
     *
     * @param string $username
     * @param string $email
     * @param string $plainPassword
     * @param null|bool $activation
     * @param null|array $roles
     */
    public function __construct(
        string $username,
        string $email,
        string $plainPassword,
        ?bool  $activation = null,
        ?array $roles      = null
    )
    {
        $this->id            = $this->generateId();
        $this->createdAt     = new \DateTime();
        $this->username      = $username;
        $this->email         = $email;
        $this->plainPassword = $plainPassword;
        $this->password      = \password_hash($plainPassword, \PASSWORD_BCRYPT);
        $this->activation    = $activation === null ? false : $activation;
        $this->roles         = $roles === null ? array('ROLE_USER') : $roles;
        $this->tokens        = new ArrayCollection();
    }

    /**
     * Updates the user.
     *
     * @param null|string $username
     * @param null|string $email
     * @param null|string $plainPassword
     * @param null|bool $activation
     * @param null|array $roles
     */
    public function update(
        ?string $username      = null,
        ?string $email         = null,
        ?string $plainPassword = null,
        ?bool   $activation    = null,
        ?array  $roles         = null
    )
    {
        $this->username      = $username ?? $username;
        $this->email         = $email ?? $email;
        $this->plainPassword = $plainPassword ?? $plainPassword;
        $this->activation    = $activation ?? $activation;
        $this->roles         = $roles ?? $roles;

        if ($this->plainPassword !== null) {
            $this->password = \password_hash($this->plainPassword, PASSWORD_BCRYPT);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Gets the email.
     *
     * @return string
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * Gets the plain password.
     *
     * @return null|string
     */
    public function getPlainPassword() : ?string
    {
        return $this->plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Gets the activation.
     *
     * @return bool
     */
    public function getActivation() : bool
    {
        return $this->activation;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Adds a role.
     *
     * @param string $role
     */
    public function addRole(string $role)
    {
        $role = strtoupper($role);

        if (! in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    /**
     * Removes a role.
     *
     * @param string $role
     */
    public function removeRole(string $role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
    }

    /**
     * Checks whether or not the user has this role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role) : bool
    {
        return in_array(strtoupper($role), $this->roles);
    }

    /**
     * Gets the activation token.
     *
     * @return null|App\Entity\UserToken
     */
    public function getActivationToken() : ?UserToken
    {
        return $this->getToken('activation');
    }

    /**
     * Gets the password reset token.
     *
     * @return null|App\Entity\UserToken
     */
    public function getPasswordResetToken() : ?UserToken
    {
        return $this->getToken('password_reset');
    }

    /**
     * Gets the token.
     *
     * @return null|App\Entity\UserToken
     */
    protected function getToken($type) : ?UserToken
    {
        $criteria = new Criteria();

        $token = $this->tokens->matching(
            $criteria->where(
                $criteria->expr()->eq('type', $type)
            )

        )->last();

        return $token === false ? null : $token;
    }

    /**
     * Creates an activation token.
     *
     * @param int $ttl
     * @return App\Entity\UserToken
     */
    public function createActivationToken(int $ttl = 24) : UserToken
    {
        if ($token = $this->getActivationToken()) {
            $this->tokens->removeElement($token);
        }

        $token = new UserToken($this, 'activation', $ttl);

        $this->addToken($token);

        return $token;
    }

    /**
     * Creates a password reset token.
     *
     * @param int $ttl
     * @return App\Entity\UserToken
     */
    public function createPasswordResetToken(int $ttl = 24) : UserToken
    {
        if ($token = $this->getPasswordResetToken()) {
            $this->tokens->removeElement($token);
        }

        $token = new UserToken($this, 'password_reset', $ttl);

        $this->addToken($token);

        return $token;
    }

    /**
     * Adds the token.
     *
     * @param App\Entity\UserToken
     */
    protected function addToken(UserToken $token)
    {
        $this->tokens->add($token);
    }

    /**
     * Gets the gravatar.
     *
     * @param null|int $size
     * @param null|string $imageSet
     * @param null|string $rating
     * @return null|string
     */
    public function getGravatar(?int $size = 80, ?string $imageSet = 'mm', ?string $rating = 'g') : string
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($this->email))).'?s='.$size.'&d='.$imageSet.'&r='.$rating;
    }

    /**
     * Enables.
     */
    public function enable()
    {
        $this->activation = true;
    }

    /**
     * Disables.
     */
    public function disable()
    {
        $this->activation = false;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->activation;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return $this->activation;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return $this->activation;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return $this->activation;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
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
            $this->activation,
            $this->createdAt,
            $this->roles
        ) = unserialize($serialized);
    }

    /**
     * Returns the username.
     *
     * @return string
     */
    public function __toString() : string
    {
        return (string) $this->username;
    }
}
