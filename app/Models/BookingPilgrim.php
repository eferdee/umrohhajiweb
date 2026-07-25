<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPilgrim extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'full_name',
        'gender',
        'birth_place',
        'birth_date',
        'nik',
        'passport_number',
        'passport_expired',
        'passport_photo',
        'ktp_photo',
        'family_card_photo',
        'phone',
        'address',
        'emergency_contact',
        'relationship',
        'photo',
        'document_status',
        'document_note',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'passport_expired' => 'date',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
