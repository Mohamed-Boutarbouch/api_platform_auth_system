<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerService
{
    private const BASE_URL = 'http://127.0.0.1:8000/api';
    private const ADDRESS = 'example@example.com';
    private const NAME = 'My App';

    public function __construct(private MailerInterface $mailer)
    {
    }

    public function createVerificationEmail(User $user): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from(new Address(self::ADDRESS, self::NAME))
            ->to($user->getEmail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('emails/confirmation_email.html.twig');
    }

    public function sendForgotPassword(User $user, string $token): void
    {
        $prefixForgotPassword = '/forgot-password';
        $email = (new TemplatedEmail())
            ->from(new Address(self::ADDRESS, self::NAME))
            ->to($user->getEmail())
            ->subject('Password Reset')
            ->htmlTemplate('emails/reset_password.html.twig')
            ->context([
                'user' => $user,
                'reset_password_url' => sprintf('%s%s/%s', self::BASE_URL, $prefixForgotPassword, $token),
            ]);

        $this->mailer->send($email);
    }
}
