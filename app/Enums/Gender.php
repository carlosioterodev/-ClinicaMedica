<?php

namespace App\Enums;

enum Gender: string
{
    case Male = 'M';
    case Female = 'F';
    case Other = 'O';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Masculino',
            self::Female => 'Femenino',
            self::Other => 'Otro',
        };
    }
}
