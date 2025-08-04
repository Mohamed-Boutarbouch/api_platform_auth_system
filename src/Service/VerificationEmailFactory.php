<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class VerificationEmailFactory
{
    public function __construct()
    {
    }

    public function createVerificationEmail(User $user): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from(new Address('example@example.com', 'My App'))
            ->to($user->getEmail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('emails/confirmation_email.html.twig');
    }
}
