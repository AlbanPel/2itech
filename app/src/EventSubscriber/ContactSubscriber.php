<?php

namespace App\EventSubscriber;
use App\Event\ContactEvent;
use App\Service\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContactSubscriber implements EventSubscriberInterface
{
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public function sendContact(ContactEvent $event)
    {
        $contact = $event->getContact();

        $this->mailerService->send(
            "Nouveau contact",
            $contact->getMail(),
            $contact->getMessage()
        );
    }
    public static function getSubscribedEvents()
    {

        return [
            ContactEvent::class => [
                ['sendContact', 1]
            ]
        ];
    }
}