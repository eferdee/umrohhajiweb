<?php

namespace App\Notifications;

use App\Models\BookingPilgrim;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke customer saat admin memverifikasi atau menandai dokumen jamaah
 * 'incomplete' (perlu diperbaiki).
 */
class DocumentStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public BookingPilgrim $pilgrim,
        public string $status, // 'verified' | 'incomplete'
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $isVerified = $this->status === 'verified';

        return [
            'category' => 'document_status',
            'status' => $this->status,
            'title' => $isVerified ? 'Dokumen terverifikasi' : 'Dokumen perlu diperbaiki',
            'message' => $isVerified
                ? "Dokumen jamaah {$this->pilgrim->full_name} sudah diverifikasi oleh admin."
                : "Dokumen jamaah {$this->pilgrim->full_name} ditolak. " . ($this->pilgrim->document_note ? "Catatan: {$this->pilgrim->document_note}" : 'Silakan unggah ulang dokumen yang diperlukan.'),
            'icon' => $isVerified ? 'success' : 'danger',
            'url' => $isVerified
                ? route('customer.bookings.show', $this->pilgrim->booking_id)
                : route('customer.pilgrims.documents.edit', $this->pilgrim),
        ];
    }
}
