<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with([
                'packageSchedule.package',
                'pilgrims:id,booking_id,document_status',
                'latestPayment:payments.id,payments.booking_id,payments.status',
            ])
            ->latest()
            ->paginate(10);

        return view('customer.dashboard', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_unless($booking->user_id === Auth::id(), 403);

        $booking->load(['packageSchedule.package', 'pilgrims', 'payments' => function ($q) {
            $q->latest();
        }]);

        $terpakai = $booking->payments()->whereIn('status', ['verified', 'pending'])->sum('amount');
        $sisaTagihan = max((float) $booking->total_price - (float) $terpakai, 0);

        return view('customer.booking-show', compact('booking', 'sisaTagihan'));
    }
}
