<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $validated['status'] = 'new';
        $validated['is_read'] = false;

        $contact = ContactMessage::create($validated);

        return back()->with('success', 'Pesan Anda berhasil dikirim. Kode pelacakan Anda: ' . $contact->tracking_code . ' — simpan kode ini untuk mengecek status & balasan admin di halaman Cek Status Pesan.');
    }

    public function statusForm()
    {
        return view('contact.status');
    }

    public function statusCheck(Request $request)
    {
        $validated = $request->validate([
            'tracking_code' => ['required', 'string'],
            'email' => ['required', 'email'],
        ]);

        $contact = ContactMessage::where('tracking_code', $validated['tracking_code'])
            ->where('email', $validated['email'])
            ->first();

        if (! $contact) {
            return back()->withInput()->with('error', 'Pesan tidak ditemukan. Pastikan kode pelacakan dan email sesuai dengan yang digunakan saat mengirim pesan.');
        }

        return view('contact.status', ['contact' => $contact]);
    }
}
