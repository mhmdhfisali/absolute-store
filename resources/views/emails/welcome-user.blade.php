<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Absolute Store</title>
    <style>
        /* Base Resets */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; background-color: #0b0f19; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        
        /* Interactive Link States */
        a { color: #818cf8; text-decoration: none; }
        a:hover { text-decoration: underline; }
        
        /* Mobile Adaptations */
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; padding: 12px !important; }
            .content-box { padding: 20px 16px !important; }
            .hero-title { font-size: 22px !important; line-height: 28px !important; }
            .btn-cta { display: block !important; width: 100% !important; text-align: center !important; }
            .two-col { display: block !important; width: 100% !important; }
        }
    </style>
</head>
<body style="background-color: #0b0f19; margin: 0; padding: 24px 0; -webkit-font-smoothing: antialiased;">

    <!-- Preheader Text (Visible in Email Client Preview) -->
    <div style="display: none; font-size: 1px; color: #0b0f19; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        Selamat datang di Absolute Store, {{ $user->name }}! Akun Anda telah aktif dan siap digunakan. Klaim voucher member baru & nikmati transaksi kilat 24 jam.
    </div>

    <!-- Main Container -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 10px 12px;">
                <table class="email-container" border="0" cellpadding="0" cellspacing="0" width="600" style="max-width: 600px; width: 100%; margin: 0 auto;">
                    
                    <!-- Top Logo & Header -->
                    <tr>
                        <td align="center" style="padding: 20px 0 24px 0;">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); border-radius: 14px; width: 44px; height: 44px; text-align: center; vertical-align: middle; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);">
                                        <span style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 18px; font-weight: 900; color: #ffffff; letter-spacing: 1px; line-height: 44px;">AS</span>
                                    </td>
                                    <td style="padding-left: 12px; text-align: left;">
                                        <div style="font-size: 18px; font-weight: 900; letter-spacing: 1.5px; color: #ffffff; text-transform: uppercase;">
                                            ABSOLUTE<span style="color: #818cf8;">STORE</span>
                                        </div>
                                        <div style="font-size: 10px; font-weight: 700; letter-spacing: 1px; color: #94a3b8; text-transform: uppercase;">
                                            Official Digital Store & PPOB
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Content Card -->
                    <tr>
                        <td class="content-box" style="background-color: #111827; border: 1px solid #1f2937; border-radius: 20px; padding: 36px 32px; box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);">
                            
                            <!-- Welcome Tag -->
                            <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 18px;">
                                <tr>
                                    <td style="background-color: rgba(99, 102, 241, 0.15); border: 1px solid rgba(99, 102, 241, 0.35); border-radius: 9999px; padding: 4px 14px;">
                                        <span style="font-size: 11px; font-weight: 700; letter-spacing: 1px; color: #a5b4fc; text-transform: uppercase;">
                                            🚀 Akun Resmi Aktif
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Title -->
                            <h1 class="hero-title" style="margin: 0 0 16px 0; font-size: 26px; font-weight: 800; color: #ffffff; line-height: 34px;">
                                Halo, <span style="background: linear-gradient(135deg, #818cf8 0%, #38bdf8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; color: #818cf8;">{{ $user->name }}</span>! 🎉
                            </h1>

                            <p style="margin: 0 0 22px 0; font-size: 14px; line-height: 24px; color: #cbd5e1;">
                                Selamat datang di ekosistem <strong style="color: #ffffff;">Absolute Store</strong>! Pendaftaran akun Anda telah berhasil. Sekarang Anda siap menikmati layanan top up game kilat, voucher digital, token PLN, dan produk digital lainnya dengan harga terbaik dan proses serba otomatis 24 jam nonstop.
                            </p>

                            <!-- Account Summary Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #0f172a; border: 1px solid #1e293b; border-radius: 14px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #64748b; margin-bottom: 10px;">
                                            Informasi Kredensial Akun
                                        </div>
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="padding: 4px 0; font-size: 13px; color: #94a3b8;">Email Terdaftar:</td>
                                                <td style="padding: 4px 0; font-size: 13px; font-weight: 700; color: #ffffff; text-align: right;">{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; font-size: 13px; color: #94a3b8;">Status Pengguna:</td>
                                                <td style="padding: 4px 0; font-size: 13px; font-weight: 700; color: #34d399; text-align: right;">Aktif & Terverifikasi ✓</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0; font-size: 13px; color: #94a3b8;">Level Keanggotaan:</td>
                                                <td style="padding: 4px 0; font-size: 13px; font-weight: 700; color: #818cf8; text-align: right;">{{ ucfirst($user->tier ?? 'Member Reguler') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Special Welcome Voucher -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.12) 0%, rgba(6, 182, 212, 0.12) 100%); border: 1px dashed rgba(99, 102, 241, 0.5); border-radius: 14px; margin-bottom: 26px;">
                                <tr>
                                    <td style="padding: 18px 20px; text-align: center;">
                                        <div style="font-size: 11px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: #38bdf8; margin-bottom: 4px;">
                                            🎁 Hadiah Spesial Member Baru
                                        </div>
                                        <div style="font-size: 13px; color: #cbd5e1; margin-bottom: 10px;">
                                            Gunakan kupon berikut saat checkout untuk potongan harga ekstra di transaksi perdana Anda:
                                        </div>
                                        <div style="display: inline-block; background-color: #0b0f19; border: 1px solid #4f46e5; border-radius: 8px; padding: 6px 18px; font-family: monospace; font-size: 16px; font-weight: 800; letter-spacing: 2px; color: #38bdf8;">
                                            {{ $welcomeCoupon }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            @if(!empty($referralUrl))
                            <!-- Referral Invitation Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #0f172a; border: 1px solid #1e293b; border-radius: 14px; margin-bottom: 26px;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <div style="font-size: 12px; font-weight: 800; color: #fbbf24; margin-bottom: 4px;">
                                            💰 Bagikan & Dapatkan Cuan Komisi (Program Afiliasi)
                                        </div>
                                        <div style="font-size: 12px; line-height: 20px; color: #94a3b8; margin-bottom: 10px;">
                                            Ajak teman mendaftar melalui link afiliasi Anda dan nikmati komisi otomatis untuk setiap transaksi sukses mereka!
                                        </div>
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="background-color: #1e293b; border-radius: 8px; padding: 8px 12px; font-family: monospace; font-size: 11px; color: #cbd5e1; word-break: break-all;">
                                                    {{ $referralUrl }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- CTA Button -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="center" style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); border-radius: 12px; box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);">
                                                    <a href="{{ $homeUrl }}" class="btn-cta" target="_blank" style="display: inline-block; padding: 14px 36px; font-size: 14px; font-weight: 800; color: #ffffff; text-decoration: none; letter-spacing: 0.5px;">
                                                        Mulai Belanja & Top Up Sekarang ⚡
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <div style="text-align: center; margin-bottom: 20px;">
                                <a href="{{ $dashboardUrl }}" style="font-size: 12px; color: #94a3b8; text-decoration: underline;">
                                    Atau akses Dashboard Pengguna Anda &rarr;
                                </a>
                            </div>

                            <!-- Security Notice -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-top: 1px solid #1f2937; padding-top: 18px;">
                                <tr>
                                    <td>
                                        <div style="font-size: 11px; color: #64748b; line-height: 18px;">
                                            🔒 <strong style="color: #94a3b8;">Tips Keamanan Akun:</strong> Jangan pernah membagikan kata sandi atau kode verifikasi rahasia Anda kepada siapapun. Tim Absolute Store tidak akan pernah meminta kata sandi Anda.
                                        </div>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer & Support Links -->
                    <tr>
                        <td align="center" style="padding: 24px 12px 12px 12px; text-align: center;">
                            <div style="font-size: 12px; color: #64748b; line-height: 20px; margin-bottom: 8px;">
                                Butuh bantuan atau ada kendala transaksi? Tim Customer Care kami siap membantu 24/7.
                            </div>
                            <div style="margin-bottom: 14px;">
                                <a href="https://api.whatsapp.com/send?phone=6281234567890&text=Halo%20Admin%20Absolute%20Store,%20saya%20butuh%20bantuan" target="_blank" style="display: inline-block; font-size: 12px; font-weight: 700; color: #34d399; margin: 0 8px;">
                                    💬 WhatsApp CS 24 Jam
                                </a>
                                <span style="color: #334155;">•</span>
                                <a href="{{ $homeUrl }}" target="_blank" style="display: inline-block; font-size: 12px; font-weight: 700; color: #818cf8; margin: 0 8px;">
                                    🌐 Website Resmi
                                </a>
                            </div>
                            <div style="font-size: 10px; color: #475569; line-height: 16px;">
                                &copy; {{ date('Y') }} {{ config('app.name', 'Absolute Store') }}. Seluruh hak cipta dilindungi undang-undang.<br>
                                Email ini dikirim secara otomatis karena Anda mendaftar di sistem kami dengan alamat {{ $user->email }}.
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
