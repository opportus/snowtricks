<?php

namespace App\Entity;

use App\Entity\Data\EntityDataInterface;
use App\Entity\Data\UserDataInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * {@inheritdoc}
     */
    public static function createFromData(EntityDataInterface $data) : EntityInterface
    {
        if (! $data instanceof UserDataInterface) {
            throw new \InvalidArgumentException();
        }

        $self = get_called_class();

        return new $self(
            $data->getUsername(),
            $data->getEmail(),
            $data->getPlainPassword(),
            $data->getActivation(),
            $data->getRoles()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function updateFromData(EntityDataInterface $data) : EntityInterface
    {
        if (! $data instanceof UserDataInterface) {
            throw new \InvalidArgumentException();
        }

        $this->username      = $data->getUsername();
        $this->email         = $data->getEmail();
        $this->plainPassword = $data->getPlainPassword();
        $this->activation    = $data->getActivation();
        $this->roles         = $data->getRoles();

        if ($this->plainPassword !== null) {
            $this->password = \password_hash($this->plainPassword, PASSWORD_BCRYPT);
        }

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
    public function getEmail() : string
    {
        return $this->email;
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
    public function getPassword()
    {
        return $this->password;
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

        $token = $this->tokens->matching(
            $criteria->where(
                $criteria->expr()->eq('type', $type)
            )

        )->last();

        return $token === false ? null : $token;
    }

    /**
     * {@inheritdoc}
     */
    public function createActivationToken(int $ttl = 24) : UserTokenInterface
    {
        if ($token = $this->getActivationToken()) {
            $this->tokens->removeElement($token);
        }

        $token = new UserToken($this, 'activation', $ttl);

        $this->addToken($token);

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function createPasswordResetToken(int $ttl = 24) : UserTokenInterface
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
     * @param  App\Entity\UserTokenInterface
     * @return App\Entity\UserInterface
     */
    protected function addToken(UserTokenInterface $token) : UserInterface
    {
        $this->tokens->add($token);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGravatar(?int $size = 80, ?string $imageSet = 'mm', ?string $rating = 'g') : string
    {
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
     * {@inheritdoc}
     */
    public function __toString() : string
    {
        return (string) $this->username;
    }
}

