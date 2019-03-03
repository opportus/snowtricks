<?php

namespace App\Mailer;

use App\Entity\UserInterface;
use App\Validator\ValidatorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Psr\Log\LoggerInterface;
use Swift_Mailer;
use Swift_Message;
use Twig_Environment;

/**
 * The mailer...
 *
 * @version 0.0.1
 * @package App\Mailer
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class Mailer implements MailerInterface
{
    /**
     * @var array $parameters
     */
    private $parameters;

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
     * @var Symfony\Component\Routing\RouterInterface $router
     */
    private $router;

    /**
     * @var App\Validator\ValidatorInterface $validator
     */
    private $validator;

    /**
     * @var Psr\Log\LoggerInterface $logger
     */
    private $logger;

    /**
     * Constructs the mailer.
     *
     * @param array $parameters
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param Symfony\Component\Translation\TranslatorInterface $translator
     * @param Symfony\Component\Routing\RouterInterface $router
     * @param App\Validator\ValidatorInterface $validator
     * @param Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        array               $parameters,
        Swift_Mailer        $mailer,
        Twig_Environment    $twig,
        TranslatorInterface $translator,
        RouterInterface     $router,
        ValidatorInterface  $validator,
        LoggerInterface     $logger
    ) {
        $this->parameters = $parameters;
        $this->mailer     = $mailer;
        $this->twig       = $twig;
        $this->translator = $translator;
        $this->router     = $router;
        $this->validator  = $validator;
        $this->logger     = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function sendUserSignUpEmail(UserInterface $user) : MailerInterface
    {
        $this->validator->validateWithExceptionAndLog($user, null, array('user.sign_up.email'));

        $template = $this->parameters['send_user_sign_up_email']['template'];
        $from     = $this->parameters['send_user_sign_up_email']['from'];
        $subject  = $this->translator->trans(
            'user.sign_up.email.subject',
            array(
                '%username%' => $user->getUsername(),
            )
        );
        $message  = $this->translator->trans(
            'user.sign_up.email.message',
            array(
                '%username%' => $user->getUsername(),
            )
        );

        $this->sendEmail(
            $user,
            $template,
            $from,
            $subject,
            $message
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function sendUserActivationEmail(UserInterface $user) : MailerInterface
    {
        $template = $this->parameters['send_user_activation_email']['template'];
        $from     = $this->parameters['send_user_activation_email']['from'];
        $subject  = $this->translator->trans(
            'user.activation.email.subject',
            array(
                '%username%'    => $user->getUsername(),
                '%action_ttl%'  => $user->getActivationToken()->getTtl(),
            )
        );
        $message  = $this->translator->trans(
            'user.activation.email.message',
            array(
                '%username%'    => $user->getUsername(),
                '%action_ttl%'  => $user->getActivationToken()->getTtl(),
            )
        );

        $this->sendEmail(
            $user,
            $template,
            $from,
            $subject,
            $message
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function sendUserPasswordResetEmail(UserInterface $user) : MailerInterface
    {
        $template = $this->parameters['send_user_password_reset_email']['template'];
        $from     = $this->parameters['send_user_password_reset_email']['from'];
        $subject  = $this->translator->trans(
            'user.password_reset.email.subject',
            array(
                '%username%'    => $user->getUsername(),
                '%action_ttl%'  => $user->getPasswordResetToken()->getTtl(),
            )
        );
        $message  = $this->translator->trans(
            'user.password_reset.email.message',
            array(
                '%username%'    => $user->getUsername(),
                '%action_ttl%'  => $user->getPasswordResetToken()->getTtl(),
            )
        );

        $this->sendEmail(
            $user,
            $template,
            $from,
            $subject,
            $message
        );

        return $this;
    }

    /**
     * Sends an email to a user.
     *
     * @param  App\Entity\UserInterface $user
     * @param  string $template
     * @param  string $from
     * @param  string $subject
     * @param  string $message
     * @return App\Mailer\MailerInterface
     */
    private function sendEmail(
        UserInterface $user,
        string        $template,
        string        $from,
        string        $subject,
        string        $message
    ) : MailerInterface {
        try {
            $email = new Swift_Message();

            $email->setFrom($from);
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

            return $this;
        } catch (\Exception $exception) {
            $this->logger->critical(
                sprintf(
                    'Failed to send "%s" email to "%s"',
                    $subject,
                    $user->getEmail()
                ),
                array(
                    'exception' => $exception,
                    'user'      => $user,
                    'template'  => $template,
                    'from'      => $from,
                    'subject'   => $subject,
                    'message'   => $message,
                )
            );

            throw $exception;
        }

        return $this;
    }
}
