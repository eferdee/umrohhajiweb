<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\NewPaymentSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function create(Booking $booking)
    {
        $this->authorizeOwner($booking);

        abort_if(in_array($booking->status, ['cancelled', 'paid', 'completed', 'scheduled']), 403,
            'Booking ini sudah tidak memerlukan pembayaran lagi.');

        $sisaTagihan = $booking->sisaTagihan();

        abort_if($sisaTagihan <= 0, 403, 'Tagihan untuk booking ini sudah lunas atau sedang menunggu verifikasi penuh.');

        $booking->load(['packageSchedule.package', 'payments' => fn ($q) => $q->latest()]);

        return view('customer.payments.create', compact('booking', 'sisaTagihan'));
    }

    public function store(Request $request, Booking $booking)
    {
        $this->authorizeOwner($booking);

        abort_if(in_array($booking->status, ['cancelled', 'paid', 'completed', 'scheduled']), 403,
            'Booking ini sudah tidak memerlukan pembayaran lagi.');

        $sisaTagihan = $booking->sisaTagihan();

        $validated = $request->validate([
            'payment_type' => ['required', 'in:dp,full_payment,installment'],
            'payment_method' => ['required', 'in:bank_transfer,cash,credit_card,debit_card,qris'],
            'amount' => ['required', 'numeric', 'min:100000', 'max:' . max($sisaTagihan, 100000)],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'transfer_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'amount.max' => 'Jumlah pembayaran melebihi sisa tagihan (Rp ' . number_format($sisaTagihan, 0, ',', '.') . ').',
        ]);

        // Dicek sebelum insert baris baru: kalau booking ini pernah punya pembayaran
        // yang ditolak, kiriman kali ini berarti "dikirim ulang setelah ditolak".
        $isResubmission = $booking->payments()->where('status', 'rejected')->exists();

        $validated['booking_id'] = $booking->id;
        $validated['invoice_number'] = 'INV' . now()->format('ymd') . strtoupper(Str::random(6));
        $validated['transfer_proof'] = $request->file('transfer_proof')->store('payments', 'public');
        $validated['status'] = 'pending';

        $payment = $booking->payments()->create($validated);

        // status booking pindah ke "menunggu verifikasi" sampai admin cek bukti transfernya
        $booking->update(['status' => 'waiting_verification']);

        Notification::send(User::admins(), new NewPaymentSubmitted($payment, $isResubmission));

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Tim kami akan segera memverifikasi.');
    }

    private function authorizeOwner(Booking $booking): void
    {
        abort_unless($booking->user_id === Auth::id(), 403);
    }
}
