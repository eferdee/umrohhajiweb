<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Dikirim ke semua admin saat customer mengirim bukti pembayaran baru.
 * $isResubmission true kalau booking ini sebelumnya punya pembayaran yang
 * ditolak — dipakai untuk memberi label "dikirim ulang" di notifikasi.
 */
class NewPaymentSubmitted extends Notification
{
    use Queueable;

    public function __construct(
        public Payment $payment,
        public bool $isResubmission = false,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $customerName = $this->payment->booking->user->name ?? 'Customer';

        return [
            'category' => 'payment_submitted',
            'title' => $this->isResubmission ? 'Bukti pembayaran dikirim ulang' : 'Bukti pembayaran baru',
            'message' => $this->isResubmission
                ? "{$customerName} mengirim ulang bukti pembayaran untuk invoice {$this->payment->invoice_number} setelah sebelumnya ditolak."
                : "{$customerName} mengirim bukti pembayaran baru untuk invoice {$this->payment->invoice_number}.",
            'icon' => $this->isResubmission ? 'warning' : 'info',
            'url' => route('admin.payments.show', $this->payment),
        ];
    }
}
