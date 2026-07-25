<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PackageSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Form pendaftaran: jadwal sudah dipilih dari halaman detail paket,
     * di sini jamaah mengisi data diri (bisa lebih dari satu orang) + dokumen.
     */
    public function create(PackageSchedule $schedule)
    {
        $schedule->load('package');

        abort_unless($schedule->status && $schedule->available_seat > 0, 404);

        return view('booking.create', compact('schedule'));
    }

    public function store(Request $request, PackageSchedule $schedule)
    {
        abort_unless($schedule->status && $schedule->available_seat > 0, 404);

        $validated = $request->validate([
            'pilgrims' => ['required', 'array', 'min:1', 'max:' . $schedule->available_seat],
            'pilgrims.*.full_name' => ['required', 'string', 'max:255'],
            'pilgrims.*.gender' => ['required', 'in:male,female'],
            'pilgrims.*.birth_place' => ['required', 'string', 'max:255'],
            'pilgrims.*.birth_date' => ['required', 'date', 'before:today'],
            'pilgrims.*.nik' => ['required', 'digits:16', 'distinct'],
            'pilgrims.*.passport_number' => ['nullable', 'string', 'max:50'],
            'pilgrims.*.passport_expired' => ['nullable', 'date', 'after:today'],
            'pilgrims.*.phone' => ['nullable', 'string', 'max:20'],
            'pilgrims.*.address' => ['required', 'string'],
            'pilgrims.*.emergency_contact' => ['nullable', 'string', 'max:255'],
            'pilgrims.*.relationship' => ['nullable', 'string', 'max:100'],
            'pilgrims.*.ktp_photo' => ['required', 'image', 'max:2048'],
            'pilgrims.*.family_card_photo' => ['required', 'image', 'max:2048'],
            'pilgrims.*.passport_photo' => ['nullable', 'image', 'max:2048'],
            'pilgrims.*.photo' => ['nullable', 'image', 'max:2048'],
        ], [
            'pilgrims.max' => 'Jumlah jamaah melebihi sisa kursi yang tersedia (:max kursi).',
            'pilgrims.*.nik.distinct' => 'NIK tidak boleh sama antar jamaah dalam satu pendaftaran ini.',
        ]);

        $booking = DB::transaction(function () use ($request, $validated, $schedule) {
            // kunci baris jadwal supaya aman dari race condition rebutan kursi terakhir
            $lockedSchedule = PackageSchedule::whereKey($schedule->id)->lockForUpdate()->first();

            $totalPeople = count($validated['pilgrims']);

            if (!$lockedSchedule->status || $lockedSchedule->available_seat < $totalPeople) {
                abort(422, 'Maaf, kursi yang tersisa tidak mencukupi untuk jumlah jamaah yang Anda daftarkan.');
            }

            $booking = Booking::create([
                'booking_code' => 'BK' . now()->format('ymd') . strtoupper(Str::random(5)),
                'user_id' => $request->user()->id,
                'package_schedule_id' => $lockedSchedule->id,
                'booking_date' => now()->toDateString(),
                'payment_deadline' => now()->addDays(3),
                'total_people' => $totalPeople,
                'total_price' => $lockedSchedule->price * $totalPeople,
                'status' => 'waiting_payment',
                'notes' => $request->input('notes'),
            ]);

            foreach ($validated['pilgrims'] as $index => $data) {
                foreach (['ktp_photo', 'family_card_photo', 'passport_photo', 'photo'] as $field) {
                    if ($request->hasFile("pilgrims.$index.$field")) {
                        $data[$field] = $request->file("pilgrims.$index.$field")->store('pilgrims', 'public');
                    } else {
                        unset($data[$field]);
                    }
                }

                $data['document_status'] = 'pending';

                $booking->pilgrims()->create($data);
            }

            $lockedSchedule->decrement('available_seat', $totalPeople);

            return $booking;
        });

        return redirect()->route('booking.success', $booking)
            ->with('success', 'Pendaftaran berhasil dikirim! Silakan lanjutkan pembayaran sebelum batas waktu.');
    }

    public function success(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);

        $booking->load(['packageSchedule.package', 'pilgrims']);

        return view('booking.success', compact('booking'));
    }
}
