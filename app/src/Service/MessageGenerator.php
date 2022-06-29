<?php

namespace App\Service;

class MessageGenerator
{
    public function getAdvertisingMessage(): string
    {
        $messages = [
            'Promotion sur les montres',
            'Plus que deux jours pour profiter de nos Promos',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }

}