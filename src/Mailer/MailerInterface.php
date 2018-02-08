<?php

namespace App\Mailer;

use App\Entity\UserInterface;
use App\Entity\UserTokenInterface;

/**
 * The mailer interface...
 *
 * @version 0.0.1
 * @package App\Mailer
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface MailerInterface
{
    /**
     * Sends an user sign up email.
     *
     * @param  App\Entity\UserInterface $user
     * @return App\Mailer\MailerInterface
     * @throws App\Exception\EntityNotValidException
     * @throws \Swift_Exception
     */
    public function sendUserSignUpEmail(UserInterface $user) : MailerInterface;

    /**
     * Sends an user activation email.
     *
     * @param  App\Entity\UserInterface $user
     * @param  App\Entity\UserTokenInterface $userToken
     * @return App\Mailer\MailerInterface
     * @throws App\Exception\EntityNotValidException
     * @throws \Swift_Exception
     */
    public function sendUserActivationEmail(UserInterface $user, UserTokenInterface $userToken) : MailerInterface;

    /**
     * Sends an user password reset email.
     *
     * @param  App\Entity\UserInterface $user
     * @param  App\Entity\UserTokenInterface $userToken
     * @return App\Mailer\MailerInterface
     * @throws App\Exception\EntityNotValidException
     * @throws \Swift_Exception
     */
    public function sendUserPasswordResetEmail(UserInterface $user, UserTokenInterface $userToken) : MailerInterface;
}

