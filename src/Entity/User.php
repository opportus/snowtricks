<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
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
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity(fields={"username"}, groups={"CREATE", "UPDATE", "user.sign_up.form"})
 * @UniqueEntity(fields={"email"}, groups={"CREATE", "UPDATE", "user.sign_up.form"})
 */
class User implements UserInterface
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
     * @var null|string $username
     *
     * @ORM\Column(name="username", type="string", length=35, unique=true)
     *
     * @Assert\NotBlank(groups={"CREATE", "user.sign_up.form", "user.sign_in.form", "user.request_password_reset.form", "user.sign_up.email", "user.activate.email", "user.reset_password.email"})
     * @Assert\Type(type="string", groups={"CREATE", "UPDATE", "user.sign_up.form", "user.sign_in.form", "user.request_password_reset.form", "user.sign_up.email", "user.activate.email", "user.reset_password.email"})
     * @Assert\Length(max=35, groups={"CREATE", "UPDATE", "user.sign_up.form", "user.sign_in.form", "user.request_password_reset.form", "user.sign_up.email", "user.activate.email", "user.reset_password.email"})
     */
    protected $username;

    /**
     * @var null|string $email
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(groups={"CREATE", "user.sign_up.form", "user.sign_up.email", "user.activate.email", "user.reset_password.email"})
     * @Assert\Type(type="string", groups={"CREATE", "UPDATE", "user.sign_up.form", "user.sign_up.email", "user.activate.email", "user.reset_password.email"})
     * @Assert\Length(max=255, groups={"CREATE", "UPDATE", "user.sign_up.form", "user.sign_up.email", "user.activate.email", "user.reset_password.email"})
     * @Assert\Email(groups={"CREATE", "UPDATE", "user.sign_up.form", "user.sign_up.email", "user.activate.email", "user.reset_password.email"})
     */
    protected $email;

    /**
     * @var null|string $plainPassword
     *
     * @Assert\NotBlank(groups={"user.sign_up.form", "user.sign_in.form", "user.reset_password.form"})
     * @Assert\Type(type="string", groups={"user.sign_up.form", "user.sign_in.form", "user.reset_password.form"})
     * @Assert\Length(max=4096, groups={"user.sign_up.form", "user.sign_in.form", "user.reset_password.form"})
     */
    protected $plainPassword;

    /**
     * @var null|string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     *
     * @Assert\NotBlank(groups={"CREATE"})
     * @Assert\Type(type="string", groups={"CREATE", "UPDATE"})
     * @Assert\Length(max=255, groups={"CREATE", "UPDATE"})
     */
    protected $password;

    /**
     * @var null|string $salt
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string", groups={"CREATE", "UPDATE"})
     * @Assert\Length(max=255, groups={"CREATE", "UPDATE"})
     */
    protected $salt;

    /**
     * @var null|bool $activation
     *
     * @ORM\Column(name="activation", type="boolean")
     *
     * @Assert\NotNull(groups={"CREATE"})
     * @Assert\Type(type="bool", groups={"CREATE", "UPDATE"})
     */
    protected $activation;

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
     * @var array $roles
     *
     * @ORM\Column(name="roles", type="array")
     *
     * @Assert\NotBlank(groups={"CREATE"})
     * @Assert\Type(type="array", groups={"CREATE", "UPDATE"})
     */
    protected $roles = array();

    /**
     * @var null|Doctrine\Common\Collections\Collection $tokens
     *
     * @ORM\OneToMany(targetEntity="App\Entity\UserToken", mappedBy="user")
     */
    protected $tokens;

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
    public function setId(int $id) : UserInterface
    {
        $this->id = $id;

        return $this;
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
    public function getEmail()
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
    public function getPlainPassword()
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
    public function setSalt() : UserInterface
    {
        $this->salt = base64_encode(random_bytes(32));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getActivation()
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
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     *
     * @ORM\PrePersist
     */
    public function setCreatedAt() : UserInterface
    {
        $this->createdAt = new \Datetime();

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
    public function setRoles(array $roles) : UserInterface
    {
        $this->roles = $roles;

        return $this;
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
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * {@inheritdoc}
     */
    public function setTokens(Collection $tokens) : UserInterface
    {
        $this->tokens = $tokens;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return (bool) $this->getActivation();
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return $this->getActivation();
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return $this->getActivation();
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return $this->getActivation();
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
            $this->getId(),
            $this->getUsername(),
            $this->getEmail(),
            $this->getPassword(),
            $this->getSalt(),
            $this->getActivation(),
            $this->getCreatedAt(),
            $this->getRoles(),
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
        return (string) $this->getUsername();
    }
}

