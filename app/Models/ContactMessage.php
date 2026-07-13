<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message',
        'status', 'admin_notes',
    ];

    public function markAsRead(): void
    {
        if ($this->status === 'new') {
            $this->update(['status' => 'read']);
        }
    }
}
