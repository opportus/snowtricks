<?php

namespace App\Form\Data;

use App\Entity\Data\UserDataInterface;
use App\Entity\Data\EntityDataInterface;
use App\Entity\EntityInterface;
use App\Entity\UserInterface;
use App\Validator\Constraints\UniqueData;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The user data...
 *
 * @version 0.0.1
 * @package App\Form\Data
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 *
 * @AppAssert\UniqueEntity(
 *     entityClass="App\Entity\User",
 *     primaryKey="username",
 *     message="user.sign_up.form.message.username_conflict",
 *     groups={"sign_up_form"}
 * )
 * @AppAssert\UniqueEntity(
 *     entityClass="App\Entity\User",
 *     primaryKey="email",
 *     message="user.sign_up.form.message.email_conflict",
 *     groups={"sign_up_form"}
 * )
 * @AppAssert\PersistedEntity(
 *     entityClass="App\Entity\User",
 *     primaryKey="username",
 *     message="user.password_reset_request.form.message.username_not_found",
 *     groups={"password_reset_request_form"}
 * )
 */
class UserData implements UserDataInterface
{
    /**
     * @var null|string $username
     *
     * @Assert\NotBlank(groups={"user.form.sign_up", "user.form.sign_in", "user.form.password_reset_request"})
     * @Assert\Type(type="string", groups={"user.form.sign_up", "user.form.sign_in", "user.form.password_reset_request"})
     * @Assert\Length(max=35, groups={"user.form.sign_up", "user.form.sign_in", "user.form.password_reset_request"})
     */
    protected $username;

    /**
     * @var null|string $email
     *
     * @Assert\NotBlank(groups={"user.form.sign_up"})
     * @Assert\Type(type="string", groups={"user.form.sign_up"})
     * @Assert\Length(max=255, groups={"user.form.sign_up"})
     * @Assert\Email(groups={"user.form.sign_up"})
     */
    protected $email;

    /**
     * @var null|string $plainPassword
     *
     * @Assert\Type(type="string", groups={"user.form.sign_up", "user.form.password_reset"})
     * @Assert\Length(max=4096, groups={"user.form.sign_up", "user.form.password_reset"})
     */
    protected $plainPassword;

    /**
     * @var null|bool $activation
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     */
    protected $activation;

    /**
     * @var null|array $roles
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="array")
     */
    protected $roles;

    /**
     * Constructs the user data.
     *
     * @param null|string $username
     * @param null|string $email
     * @param null|string $plainPassword
     * @param null|bool   $activation
     * @param null|array  $roles
     */
    public function __construct(
        ?string $username      = null,
        ?string $email         = null,
        ?string $plainPassword = null,
        ?bool   $activation    = null,
        ?array  $roles         = null
    )
    {
        $this->username      = $username;
        $this->email         = $email;
        $this->plainPassword = $plainPassword;
        $this->activation    = $activation;
        $this->roles         = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public static function createFromEntity(EntityInterface $entity) : EntityDataInterface
    {
        if (! $entity instanceof UserInterface) {
            throw new \InvalidArgumentException();
        }

        $self = get_called_class();

        return new $self(
            $entity->getUsername(),
            $entity->getEmail(),
            $entity->getPlainPassword(),
            $entity->getActivation(),
            $entity->getRoles()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername() : ?string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername(string $username) : UserDataInterface
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
    public function setEmail(string $email) : UserDataInterface
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
    public function setPlainPassword(string $plainPassword) : UserDataInterface
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getActivation() : ?bool
    {
        return $this->activation;
    }

    /**
     * {@inheritdoc}
     */
    public function setActivation(bool $activation) : UserDataInterface
    {
        $this->activation = $activation;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles() : ?array
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles) : UserDataInterface
    {
        $this->roles = $roles;

        return $this;
    }
}

