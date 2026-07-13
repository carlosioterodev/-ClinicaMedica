<?php

namespace App\Enums;

enum ConsultationType: string
{
    case InPerson = 'in_person';
    case Telemedicine = 'telemedicine';

    public function label(): string
    {
        return match ($this) {
            self::InPerson => 'Presencial',
            self::Telemedicine => 'Telemedicina',
        };
    }
}
