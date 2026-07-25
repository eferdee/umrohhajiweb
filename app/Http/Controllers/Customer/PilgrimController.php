<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BookingPilgrim;
use App\Models\User;
use App\Notifications\DocumentResubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PilgrimController extends Controller
{
    /**
     * Form unggah ulang dokumen — hanya bisa diakses kalau dokumen jamaah ini
     * memang sedang ditandai 'incomplete' oleh admin. Tidak dipakai untuk
     * mengedit data diri jamaah, cuma dokumen.
     */
    public function edit(BookingPilgrim $pilgrim)
    {
        $this->authorizeOwner($pilgrim);

        abort_unless($pilgrim->document_status === 'incomplete', 403,
            'Dokumen jamaah ini tidak sedang memerlukan perbaikan.');

        $pilgrim->load('booking.packageSchedule.package');

        return view('customer.pilgrims.documents', compact('pilgrim'));
    }

    public function update(Request $request, BookingPilgrim $pilgrim)
    {
        $this->authorizeOwner($pilgrim);

        abort_unless($pilgrim->document_status === 'incomplete', 403,
            'Dokumen jamaah ini tidak sedang memerlukan perbaikan.');

        $validated = $request->validate([
            'ktp_photo' => ['nullable', 'image', 'max:2048'],
            'family_card_photo' => ['nullable', 'image', 'max:2048'],
            'passport_photo' => ['nullable', 'image', 'max:2048'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ], [], [
            'ktp_photo' => 'foto KTP',
            'family_card_photo' => 'foto Kartu Keluarga',
            'passport_photo' => 'foto paspor',
            'photo' => 'pas foto',
        ]);

        if (collect($validated)->filter()->isEmpty()) {
            return back()->withErrors([
                'ktp_photo' => 'Unggah minimal satu dokumen yang diperbarui.',
            ])->withInput();
        }

        foreach (['ktp_photo', 'family_card_photo', 'passport_photo', 'photo'] as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('pilgrims', 'public');
            } else {
                unset($validated[$field]);
            }
        }

        // Dokumen baru masuk lagi ke antrean verifikasi admin — status kembali
        // ke 'pending' (bukan langsung 'verified') dan catatan lama dibersihkan.
        $validated['document_status'] = 'pending';
        $validated['document_note'] = null;

        $pilgrim->update($validated);

        Notification::send(User::admins(), new DocumentResubmitted($pilgrim));

        return redirect()->route('customer.bookings.show', $pilgrim->booking)
            ->with('success', 'Dokumen berhasil diunggah ulang. Tim kami akan segera memverifikasi kembali.');
    }

    private function authorizeOwner(BookingPilgrim $pilgrim): void
    {
        abort_unless($pilgrim->booking->user_id === Auth::id(), 403);
    }
}
