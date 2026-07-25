@php
    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Balasan Pesan Anda</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family: Arial, Helvetica, sans-serif; color:#1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5; padding: 32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width:560px; background:#ffffff; border-radius:12px; overflow:hidden;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="background:#0f2f4f; padding:24px 32px;">
                            <span style="color:#ffffff; font-size:18px; font-weight:bold;">{{ $siteName }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 8px; font-size:14px; color:#6b7280;">Halo {{ $contact->name }},</p>
                            <p style="margin:0 0 20px; font-size:14px; line-height:1.6;">
                                Tim kami telah membalas pesan Anda dengan subjek <strong>"{{ $contact->subject }}"</strong>. Berikut balasannya:
                            </p>

                            <div style="background:#f9fafb; border-left:3px solid #0f2f4f; padding:16px 20px; border-radius:6px; font-size:14px; line-height:1.6; white-space:pre-line;">
                                {{ $contact->reply_message }}
                            </div>

                            <p style="margin:24px 0 0; font-size:13px; color:#6b7280;">
                                Kode pelacakan pesan Anda: <strong style="color:#1f2937;">{{ $contact->tracking_code }}</strong><br>
                                Anda bisa mengecek status pesan ini kapan saja melalui halaman
                                <a href="{{ route('contact.status') }}" style="color:#0f2f4f;">Cek Status Pesan</a> di situs kami.
                            </p>

                            <p style="margin:24px 0 0; font-size:13px; color:#6b7280;">
                                Ada pertanyaan lanjutan? Cukup balas email ini atau hubungi kami kembali melalui halaman kontak.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 32px; background:#f9fafb; font-size:11px; color:#9ca3af;">
                            Email ini dikirim otomatis oleh sistem {{ $siteName }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
