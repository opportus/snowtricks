<?php

namespace App\Security;

use App\Entity\UserInterface;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * The user provider...
 *
 * @version 0.0.1
 * @package App\Security
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var App\Repository\UserRepositoryInterface $userRepository
     */
    protected $userRepository;

    /**
     * Constructs the user provider.
     *
     * @param App\Repository\UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $user = $this->userRepository->findOneByUsername($username);

        if ($user === null) {
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist', $username)
            );
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        if ($this->supportsClass(get_class($user)) === false) {
            throw new UnsupportedUserException(
                sprintf(
                    'Expected an instance of %s',
                    UserInterface::class
                )
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return is_subclass_of($class, UserInterface::class);
    }
}

