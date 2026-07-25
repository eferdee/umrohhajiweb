<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_code',
        'user_id',
        'package_schedule_id',
        'booking_date',
        'payment_deadline',
        'total_people',
        'total_price',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'payment_deadline' => 'datetime',
            'total_price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function packageSchedule(): BelongsTo
    {
        return $this->belongsTo(PackageSchedule::class);
    }

    public function pilgrims(): HasMany
    {
        return $this->hasMany(BookingPilgrim::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Dipakai di daftar booking (dashboard) supaya tidak perlu memuat seluruh
     * riwayat pembayaran hanya untuk menandai "perlu tindakan".
     */
    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    /**
     * Sisa tagihan = total harga - (pembayaran yang sudah verified atau masih pending verifikasi).
     * Pending ikut dihitung supaya customer tidak double-bayar sambil menunggu verifikasi admin.
     */
    public function sisaTagihan(): float
    {
        $terpakai = $this->payments()->whereIn('status', ['verified', 'pending'])->sum('amount');

        return max((float) $this->total_price - (float) $terpakai, 0);
    }

    /**
     * Total nominal yang statusnya sudah diverifikasi admin (tidak termasuk pending/rejected).
     */
    public function totalTerverifikasi(): float
    {
        return (float) $this->payments()->where('status', 'verified')->sum('amount');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }
}
