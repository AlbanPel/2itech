<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;


    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send($subject, $from, $text)
    {
        try {

            $mail =(new Email())
                ->subject($subject)
                ->to('noreply@exemple.com')
                ->from($from)
                ->text($text);

            $this->mailer->send($mail);


        }
        catch (TransportException $e) {
            print $e->getMessage() . "\n";
            throw $e;
        }
    }
}