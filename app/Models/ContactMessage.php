<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_code',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
        'status',
        'admin_notes',
        'reply_message',
        'replied_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'replied_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ContactMessage $contact) {
            if (empty($contact->tracking_code)) {
                do {
                    $code = 'MSG-' . strtoupper(Str::random(8));
                } while (static::where('tracking_code', $code)->exists());

                $contact->tracking_code = $code;
            }
        });
    }
}
