<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingPilgrim;
use App\Notifications\DocumentStatusUpdated;
use Illuminate\Http\Request;

class PilgrimController extends Controller
{
    public function index(Request $request)
    {
        $query = BookingPilgrim::with(['booking.packageSchedule.package']);

        if ($request->filled('document_status')) {
            $query->where('document_status', $request->document_status);
        }

        if ($request->filled('search')) {
            $query->where('full_name', 'like', '%' . $request->search . '%')
                ->orWhere('nik', 'like', '%' . $request->search . '%');
        }

        $pilgrims = $query->latest()->paginate(15)->withQueryString();

        return view('admin.pilgrims.index', compact('pilgrims'));
    }

    public function show(BookingPilgrim $pilgrim)
    {
        $pilgrim->load('booking.packageSchedule.package');
        return view('admin.pilgrims.show', compact('pilgrim'));
    }

    public function updateDocumentStatus(Request $request, BookingPilgrim $pilgrim)
    {
        $validated = $request->validate([
            'document_status' => 'required|in:incomplete,pending,verified',
            'document_note' => ['nullable', 'string', 'max:1000', 'required_if:document_status,incomplete'],
        ], [
            'document_note.required_if' => 'Jelaskan dokumen mana yang bermasalah supaya jamaah tahu apa yang perlu diperbaiki.',
        ]);

        // Catatan hanya relevan selama status 'incomplete' — bersihkan begitu status berubah
        // supaya tidak ada catatan lama yang nyangkut dan membingungkan di kunjungan berikutnya.
        $pilgrim->update([
            'document_status' => $validated['document_status'],
            'document_note' => $validated['document_status'] === 'incomplete' ? $validated['document_note'] : null,
        ]);

        // Cuma keputusan final (verified/incomplete) yang perlu diberitahukan ke customer —
        // 'pending' bukan keputusan, jadi tidak perlu bikin notifikasi baru.
        if (in_array($validated['document_status'], ['verified', 'incomplete'])) {
            $pilgrim->loadMissing('booking.user')->booking->user
                ?->notify(new DocumentStatusUpdated($pilgrim, $validated['document_status']));
        }

        return back()->with('success', 'Status dokumen jamaah berhasil diperbarui.');
    }
}
