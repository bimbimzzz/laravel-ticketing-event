<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body
    style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                    style="max-width: 600px; width: 100%;">

                    {{-- Header --}}
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border-radius: 12px 12px 0 0; padding: 30px 40px; text-align: center;">
                            <h1
                                style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">
                                JagoEvent</h1>
                            <p style="margin: 5px 0 0; color: rgba(255,255,255,0.8); font-size: 13px;">Platform Tiket
                                Event Terpercaya</p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="background-color: #ffffff; padding: 40px;">

                            @if ($newStatus === 'approved')
                                {{-- Approved --}}
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center" style="padding-bottom: 25px;">
                                            <div
                                                style="display: inline-block; width: 60px; height: 60px; background: #dcfce7; border-radius: 50%; text-align: center; line-height: 60px; font-size: 28px;">
                                                &#10003;</div>
                                        </td>
                                    </tr>
                                </table>

                                <h2
                                    style="margin: 0 0 15px; color: #059669; font-size: 22px; font-weight: 700; text-align: center;">
                                    Vendor Anda Telah Disetujui!</h2>

                                <p style="margin: 0 0 5px; color: #6b7280; font-size: 14px;">Halo,</p>
                                <h3 style="margin: 0 0 20px; color: #111827; font-size: 18px; font-weight: 600;">
                                    {{ $vendor->user->name ?? $vendor->name }}</h3>
                                <p style="margin: 0 0 25px; color: #4b5563; font-size: 15px; line-height: 1.6;">Selamat!
                                    Akun vendor <strong>{{ $vendor->name }}</strong> telah berhasil diverifikasi. Anda
                                    sekarang bisa mulai membuat event dan menjual tiket di JagoEvent.</p>

                                {{-- What You Can Do --}}
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                    style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; margin-bottom: 30px;">
                                    <tr>
                                        <td style="padding: 20px 25px;">
                                            <p
                                                style="margin: 0 0 12px; color: #166534; font-size: 14px; font-weight: 700;">
                                                Yang bisa Anda lakukan sekarang:</p>
                                            <table role="presentation" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="padding: 4px 0; color: #15803d; font-size: 14px;">
                                                        &#10148; Buat event pertama Anda</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 4px 0; color: #15803d; font-size: 14px;">
                                                        &#10148; Atur tipe tiket dan harga</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 4px 0; color: #15803d; font-size: 14px;">
                                                        &#10148; Pantau penjualan dari dashboard</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 4px 0; color: #15803d; font-size: 14px;">
                                                        &#10148; Buat promo code untuk event Anda</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ url('/vendor/dashboard') }}"
                                                style="display: inline-block; padding: 16px 48px; background: linear-gradient(135deg, #059669, #10b981); color: #ffffff; text-decoration: none; border-radius: 10px; font-size: 16px; font-weight: 700;">Buka
                                                Dashboard Vendor</a>
                                        </td>
                                    </tr>
                                </table>
                            @else
                                {{-- Rejected --}}
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center" style="padding-bottom: 25px;">
                                            <div
                                                style="display: inline-block; width: 60px; height: 60px; background: #fee2e2; border-radius: 50%; text-align: center; line-height: 60px; font-size: 28px;">
                                                &#10007;</div>
                                        </td>
                                    </tr>
                                </table>

                                <h2
                                    style="margin: 0 0 15px; color: #dc2626; font-size: 22px; font-weight: 700; text-align: center;">
                                    Verifikasi Vendor Ditolak</h2>

                                <p style="margin: 0 0 5px; color: #6b7280; font-size: 14px;">Halo,</p>
                                <h3 style="margin: 0 0 20px; color: #111827; font-size: 18px; font-weight: 600;">
                                    {{ $vendor->user->name ?? $vendor->name }}</h3>
                                <p style="margin: 0 0 25px; color: #4b5563; font-size: 15px; line-height: 1.6;">Mohon
                                    maaf, akun vendor <strong>{{ $vendor->name }}</strong> belum dapat disetujui saat
                                    ini.</p>

                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                    style="margin-bottom: 25px;">
                                    <tr>
                                        <td
                                            style="background: #fef2f2; border-left: 4px solid #ef4444; border-radius: 0 8px 8px 0; padding: 15px 20px;">
                                            <p
                                                style="margin: 0 0 4px; color: #991b1b; font-size: 13px; font-weight: 600;">
                                                Apa yang bisa dilakukan?</p>
                                            <p style="margin: 0; color: #b91c1c; font-size: 13px; line-height: 1.5;">
                                                Pastikan data vendor sudah lengkap dan valid. Anda bisa mengajukan ulang
                                                atau hubungi tim support untuk informasi lebih lanjut.</p>
                                        </td>
                                    </tr>
                                </table>

                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center">
                                            <a href="mailto:support@jagon8n.com"
                                                style="display: inline-block; padding: 14px 40px; background: #f3f4f6; color: #374151; text-decoration: none; border-radius: 10px; font-size: 15px; font-weight: 600; border: 1px solid #d1d5db;">Hubungi
                                                Support</a>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td
                            style="background-color: #f9fafb; border-top: 1px solid #e5e7eb; border-radius: 0 0 12px 12px; padding: 25px 40px; text-align: center;">
                            <p style="margin: 0 0 8px; color: #6b7280; font-size: 13px;">Butuh bantuan? Hubungi kami di
                                <a href="mailto:support@jagon8n.com"
                                    style="color: #4f46e5; text-decoration: none; font-weight: 500;">support@jagon8n.com</a>
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">&copy; {{ date('Y') }}
                                JagoEvent. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
