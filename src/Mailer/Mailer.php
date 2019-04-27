<?php

namespace App\Mailer;

use App\Entity\User;
use Symfony\Component\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Swift_Mailer;
use Swift_Message;
use Twig_Environment;

/**
 * The mailer.
 *
 * @version 0.0.1
 * @package App\Mailer
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class Mailer implements MailerInterface
{
    /**
     * @var \Swift_Mailer $mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment $twig
     */
    private $twig;

    /**
     * @var Symfony\Component\Translation\TranslatorInterface $translator
     */
    private $translator;

    /**
     * @var Psr\Log\LoggerInterface $logger
     */
    private $logger;

    /**
     * @var string $from
     */
    private $from;

    /**
     * Constructs the mailer.
     *
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param Symfony\Component\Translation\TranslatorInterface $translator
     * @param Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Swift_Mailer        $mailer,
        Twig_Environment    $twig,
        TranslatorInterface $translator,
        LoggerInterface     $logger
    ) {
        $this->mailer     = $mailer;
        $this->twig       = $twig;
        $this->translator = $translator;
        $this->logger     = $logger;

        $this->from = 'snowtricks@example.com';
    }

    /**
     * {@inheritdoc}
     */
    public function sendUserSignUpEmail(User $user)
    {
        $subject = $this->translator->trans(
            'user.sign_up.email.subject',
            array(
                '%username%' => $user->getUsername(),
            )
        );
        $message = $this->translator->trans(
            'user.sign_up.email.message',
            array(
                '%username%' => $user->getUsername(),
            )
        );

        $this->sendEmail(
            $user,
            $subject,
            $message,
            'user/sign_up_email.html.twig'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function sendUserActivationEmail(User $user)
    {
        $subject = $this->translator->trans(
            'user.activation.email.subject',
            array(
                '%username%'    => $user->getUsername(),
                '%action_ttl%'  => $user->getLastActivationToken()->getTtl(),
            )
        );
        $message = $this->translator->trans(
            'user.activation.email.message',
            array(
                '%username%'    => $user->getUsername(),
                '%action_ttl%'  => $user->getLastActivationToken()->getTtl(),
            )
        );

        $this->sendEmail(
            $user,
            $subject,
            $message,
            'user/activation_email.html.twig'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function sendUserPasswordResetEmail(User $user)
    {
        $subject = $this->translator->trans(
            'user.password_reset.email.subject',
            array(
                '%username%'    => $user->getUsername(),
                '%action_ttl%'  => $user->getLastPasswordResetToken()->getTtl(),
            )
        );
        $message = $this->translator->trans(
            'user.password_reset.email.message',
            array(
                '%username%'    => $user->getUsername(),
                '%action_ttl%'  => $user->getLastPasswordResetToken()->getTtl(),
            )
        );

        $this->sendEmail(
            $user,
            $subject,
            $message,
            'user/password_reset_email.html.twig'
        );
    }

    /**
     * Sends an email to a user.
     *
     * @param App\Entity\User $user
     * @param string $subject
     * @param string $message
     * @param string $template
     */
    private function sendEmail(
        User   $user,
        string $subject,
        string $message,
        string $template
    ) {
        try {
            $email = new Swift_Message();

            $email->setFrom($this->from);
            $email->setSubject($subject);
            $email->setTo($user->getEmail());
            $email->setBody($this->twig->render(
                $template,
                array(
                    'subject' => $subject,
                    'message' => $message,
                    'user'    => $user
                )
            ), 'text/html');

            $this->mailer->send($email);
        } catch (\Exception $exception) {
            $this->logger->critical(
                sprintf(
                    'Failed to send "%s" email to "%s"',
                    $subject,
                    $user->getEmail()
                ),
                array(
                    'user'      => $user,
                    'template'  => $template,
                    'from'      => $this->from,
                    'subject'   => $subject,
                    'message'   => $message,
                    'exception' => $exception,
                )
            );

            throw $exception;
        }
    }
}
