<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case Scheduled = 'scheduled';
    case Confirmed = 'confirmed';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case NoShow = 'no_show';

    public function label(): string
    {
        return match ($this) {
            self::Scheduled => 'Programada',
            self::Confirmed => 'Confirmada',
            self::InProgress => 'En Progreso',
            self::Completed => 'Completada',
            self::Cancelled => 'Cancelada',
            self::NoShow => 'No Asistió',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Scheduled => 'blue',
            self::Confirmed => 'green',
            self::InProgress => 'yellow',
            self::Completed => 'gray',
            self::Cancelled => 'red',
            self::NoShow => 'orange',
        };
    }
}
