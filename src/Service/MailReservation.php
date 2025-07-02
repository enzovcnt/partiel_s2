<?php

namespace App\Service;


use App\Entity\Screening;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;


class MailReservation
{
    public function __construct(
        private MailerInterface $mailer,
    ) {}

    public function sendNoReservationCancellationEmail(User $user,Screening $screening): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('app@enzovincent.org', 'AppMailgun'))
            ->to($user->getEmail())
            ->subject('Annulation de réservation non possible')
            ->htmlTemplate('reservation/cancel_email.html.twig')
            ->context([
                'user' => $user,
                'screening' => $screening,
            ]);


        $this->mailer->send($email);
    }
}
