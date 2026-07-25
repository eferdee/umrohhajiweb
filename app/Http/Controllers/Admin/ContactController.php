<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageReplied;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('subject', 'like', '%' . $request->search . '%');
            });
        }

        $contacts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(ContactMessage $contact)
    {
        $contact->update([
            'is_read' => true,
            'status' => $contact->status === 'new' ? 'read' : $contact->status,
        ]);

        return view('admin.contacts.show', compact('contact'));
    }

    public function followUp(Request $request, ContactMessage $contact)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,read,replied,closed'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
            'reply_message' => ['nullable', 'string', 'max:5000'],
            'send_email' => ['nullable', 'boolean'],
        ]);

        $contact->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $contact->admin_notes,
            'reply_message' => $validated['reply_message'] ?? $contact->reply_message,
            'is_read' => true,
            'replied_at' => $validated['status'] === 'replied' ? now() : $contact->replied_at,
        ]);

        $shouldSendEmail = $request->boolean('send_email') && filled($validated['reply_message'] ?? null);

        if ($shouldSendEmail) {
            try {
                Mail::to($contact->email)->send(new ContactMessageReplied($contact->fresh()));
            } catch (\Throwable $e) {
                report($e);
                return back()->with('error', 'Status tersimpan, tapi email balasan gagal terkirim. Cek konfigurasi MAIL di server (lihat log).');
            }

            return back()->with('success', 'Status pesan diperbarui dan email balasan terkirim ke jamaah.');
        }

        return back()->with('success', 'Status pesan berhasil diperbarui.');
    }

    public function destroy(ContactMessage $contact)
    {
        $contact->delete();
        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}
