<?php

namespace App\Notifications;

use App\Models\BookingPilgrim;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke semua admin saat customer mengunggah ulang dokumen jamaah yang
 * sebelumnya ditandai 'incomplete'. Rute unggah ulang cuma bisa diakses saat
 * status memang 'incomplete', jadi notifikasi ini selalu berarti "dokumen
 * yang ditolak sudah diperbaiki".
 */
class DocumentResubmitted extends Notification
{
    use Queueable;

    public function __construct(
        public BookingPilgrim $pilgrim,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'category' => 'document_resubmitted',
            'title' => 'Dokumen jamaah diperbaiki',
            'message' => "{$this->pilgrim->full_name} sudah mengunggah ulang dokumen yang sebelumnya ditolak. Mohon verifikasi kembali.",
            'icon' => 'warning',
            'url' => route('admin.pilgrims.show', $this->pilgrim),
        ];
    }
}
