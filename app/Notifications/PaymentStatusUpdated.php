<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke customer saat admin memverifikasi atau menolak bukti pembayaran.
 */
class PaymentStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public Payment $payment,
        public string $status, // 'verified' | 'rejected'
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
            'category' => 'payment_status',
            'status' => $this->status,
            'title' => $isVerified ? 'Pembayaran terverifikasi' : 'Pembayaran ditolak',
            'message' => $isVerified
                ? "Pembayaran {$this->payment->invoice_number} sudah diverifikasi oleh admin."
                : "Pembayaran {$this->payment->invoice_number} ditolak. " . ($this->payment->notes ? "Alasan: {$this->payment->notes}" : 'Silakan unggah ulang bukti pembayaran.'),
            'icon' => $isVerified ? 'success' : 'danger',
            'url' => route('customer.bookings.show', $this->payment->booking_id),
        ];
    }
}
