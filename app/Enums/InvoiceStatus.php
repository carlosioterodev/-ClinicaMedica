<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case Draft = 'draft';
    case Pending = 'pending';
    case Paid = 'paid';
    case Overdue = 'overdue';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Borrador',
            self::Pending => 'Pendiente',
            self::Paid => 'Pagada',
            self::Overdue => 'Vencida',
            self::Cancelled => 'Cancelada',
        };
    }
}
