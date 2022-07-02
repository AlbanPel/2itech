<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;
use App\Entity\Contact;

class ContactEvent extends Event
{
    public function __construct(Contact $contact)
    {
        $this->contact =$contact;
    }

    public function getContact(): contact
    {
        return $this->contact;
    }
}