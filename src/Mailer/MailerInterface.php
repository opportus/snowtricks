<?php

namespace App\Mailer;

use App\Entity\User;

/**
 * The mailer interface.
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
     * @param App\Entity\User $user
     */
    public function sendUserSignUpEmail(User $user);

    /**
     * Sends an user activation email.
     *
     * @param App\Entity\User $user
     */
    public function sendUserActivationEmail(User $user);

    /**
     * Sends an user password reset email.
     *
     * @param App\Entity\User $user
     */
    public function sendUserPasswordResetEmail(User $user);
}

