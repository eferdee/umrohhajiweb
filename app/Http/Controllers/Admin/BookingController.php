<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'packageSchedule.package']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('booking_code', 'like', '%' . $request->search . '%')
                ->orWhereHas('user', fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'packageSchedule.package', 'pilgrims', 'payments.verifiedBy']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        if (in_array($booking->status, ['completed', 'cancelled'])) {
            return redirect()->route('admin.bookings.show', $booking)
                ->with('error', 'Booking yang sudah selesai/dibatalkan tidak bisa diubah lagi.');
        }

        $booking->load(['user', 'packageSchedule.package']);

        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        if (in_array($booking->status, ['completed', 'cancelled'])) {
            return redirect()->route('admin.bookings.show', $booking)
                ->with('error', 'Booking yang sudah selesai/dibatalkan tidak bisa diubah lagi.');
        }

        $validated = $request->validate([
            'total_price' => ['required', 'numeric', 'min:' . $booking->totalTerverifikasi()],
            'payment_deadline' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'total_price.min' => 'Total harga tidak boleh lebih kecil dari nominal yang sudah terverifikasi (Rp ' . number_format($booking->totalTerverifikasi(), 0, ',', '.') . ').',
        ]);

        $booking->update($validated);

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Detail booking berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,waiting_payment,waiting_verification,partially_paid,paid,scheduled,completed,cancelled',
        ]);

        $wasCancelled = $booking->status === 'cancelled';
        $willBeCancelled = $request->status === 'cancelled';

        // kembalikan kursi ke jadwal kalau booking dibatalkan,
        // atau kurangi lagi kalau pembatalan dibatalkan (dipulihkan jadi aktif)
        if (!$wasCancelled && $willBeCancelled) {
            $booking->packageSchedule?->increment('available_seat', $booking->total_people);
        } elseif ($wasCancelled && !$willBeCancelled) {
            $booking->packageSchedule?->decrement('available_seat', $booking->total_people);
        }

        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function destroy(Booking $booking)
    {
        if (!in_array($booking->status, ['cancelled', 'pending'])) {
            return back()->with('error', 'Hanya booking berstatus pending/cancelled yang bisa dihapus. Batalkan dulu jika perlu.');
        }

        $booking->delete();

        return back()->with('success', 'Data booking berhasil dihapus.');
    }
}
