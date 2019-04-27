<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

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
    private $username;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     */
    private $password;

    /**
     * @var bool $activation
     *
     * @ORM\Column(name="activation", type="boolean")
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     */
    private $activation;

    /**
     * @var array $roles
     *
     * @ORM\Column(name="roles", type="array")
     * @Assert\NotBlank()
     * @Assert\Type(type="array")
     */
    private $roles;

    /**
     * @var Doctrine\Common\Collections\Collection $tokens
     *
     * @ORM\OneToMany(targetEntity="App\Entity\UserToken", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $tokens;

    /**
     * Constructs the user.
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @param null|bool $activation
     * @param null|array $roles
     */
    public function __construct(
        string $username,
        string $email,
        string $password,
        ?bool $activation = false,
        ?array $roles = ['ROLE_USER']
    ) {
        $this->id = $this->generateId();
        $this->createdAt = new \DateTime();
        $this->username = $username;
        $this->email = $email;
        $this->password = \password_hash($password, \PASSWORD_BCRYPT);
        $this->activation = $activation ?? false;
        $this->roles = $roles ?? ['ROLE_USER'];
        $this->tokens = new ArrayCollection();
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the password.
     * 
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = \password_hash($password, \PASSWORD_BCRYPT);
        $this->updatedAt = new \DateTime();
    }

    /**
     * Gets the activation.
     *
     * @return bool
     */
    public function getActivation(): bool
    {
        return $this->activation;
    }

    /**
     * Sets the activation.
     * 
     * @param bool $activation
     */
    public function setActivation(bool $activation)
    {
        $this->activation = $activation;
        $this->updatedAt = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Gets the last activation token.
     *
     * @return null|App\Entity\UserToken
     */
    public function getLastActivationToken(): ?UserToken
    {
        $criteria = new Criteria();

        $token = $this->tokens->matching(
            $criteria->where(
                $criteria->expr()->eq('type', 'activation')
            )

        )->first();

        return $token === false ? null : $token;
    }

    /**
     * Gets the last password reset token.
     *
     * @return null|App\Entity\UserToken
     */
    public function getLastPasswordResetToken(): ?UserToken
    {
        $criteria = new Criteria();

        $token = $this->tokens->matching(
            $criteria->where(
                $criteria->expr()->eq('type', 'password_reset')
            )

        )->first();

        return $token === false ? null : $token;
    }

    /**
     * Adds the token.
     *
     * @param App\Entity\UserToken $token
     */
    public function addToken(UserToken $token)
    {
        $this->tokens->add($token);
    }

    /**
     * Removes the token.
     * 
     * @param App\Entity\UserToken $token
     */
    public function removeToken(UserToken $token)
    {
        $this->tokens->removeElement($token);
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
    public function __toString(): string
    {
        return $this->username;
    }
}
