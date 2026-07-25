<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\Package;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_packages' => Package::count(),
            'active_packages' => Package::where('status', true)->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::whereIn('status', ['pending', 'waiting_payment', 'waiting_verification', 'partially_paid'])->count(),
            'total_articles' => Article::count(),
            'unread_contacts' => ContactMessage::where('is_read', false)->count(),
        ];

        $latestBookings = Booking::with(['user', 'packageSchedule.package'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestBookings'));
    }
}
