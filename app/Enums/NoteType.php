<?php

namespace App\Enums;

enum NoteType: string
{
    case Triage = 'triage';
    case Consultation = 'consultation';
    case FollowUp = 'follow_up';
    case General = 'general';

    public function label(): string
    {
        return match ($this) {
            self::Triage => 'Triage',
            self::Consultation => 'Consulta',
            self::FollowUp => 'Seguimiento',
            self::General => 'General',
        };
    }
}
