<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Notifications\PaymentStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('booking.user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load('booking.user', 'booking.packageSchedule.package', 'verifiedBy');
        return view('admin.payments.show', compact('payment'));
    }

    public function verify(Payment $payment)
    {
        $payment->update([
            'status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $booking = $payment->booking;
        $totalVerified = $booking->payments()->where('status', 'verified')->sum('amount');

        // kalau total pembayaran terverifikasi sudah menutupi total harga, tandai booking sebagai lunas.
        // kalau baru sebagian (DP/cicilan awal), tandai 'partially_paid' — bukan langsung 'paid'.
        if ($booking->status !== 'cancelled') {
            if ($totalVerified >= $booking->total_price) {
                $booking->update(['status' => 'paid']);
            } elseif ($totalVerified > 0) {
                $booking->update(['status' => 'partially_paid']);
            }
        }

        $booking->user?->notify(new PaymentStatusUpdated($payment, 'verified'));

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $payment->update([
            'status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'notes' => $request->notes,
        ]);

        $payment->loadMissing('booking.user')->booking->user?->notify(new PaymentStatusUpdated($payment, 'rejected'));

        return back()->with('success', 'Pembayaran ditandai ditolak.');
    }

    public function destroy(Payment $payment)
    {
        if ($payment->status === 'verified') {
            return back()->with('error', 'Pembayaran yang sudah terverifikasi tidak bisa dihapus.');
        }

        $payment->delete();

        return back()->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
