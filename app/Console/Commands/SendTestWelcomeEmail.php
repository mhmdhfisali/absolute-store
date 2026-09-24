<?php

namespace App\Console\Commands;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestWelcomeEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email? : Alamat email tujuan pengujian}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email selamat datang (Welcome Email) percobaan untuk memverifikasi konfigurasi SMTP';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $targetEmail = $this->argument('email') ?? $this->ask('Masukkan alamat email tujuan (misal: nama@gmail.com)');

        if (! filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {
            $this->error("Format email '{$targetEmail}' tidak valid.");

            return Command::FAILURE;
        }

        $this->info('==================================================');
        $this->info('DIAGNOSTIK PENGIRIMAN EMAIL - ABSOLUTE STORE');
        $this->info('==================================================');
        $this->line('Mailer Default : '.config('mail.default'));
        $this->line('SMTP Host      : '.config('mail.mailers.smtp.host'));
        $this->line('SMTP Port      : '.config('mail.mailers.smtp.port'));
        $this->line('SMTP Username  : '.(config('mail.mailers.smtp.username') ?? '(belum diisi)'));
        $this->line('From Address   : '.config('mail.from.address'));
        $this->line('From Name      : '.config('mail.from.name'));
        $this->line("Target Email   : {$targetEmail}");
        $this->info('--------------------------------------------------');

        $user = User::where('email', $targetEmail)->first();
        if (! $user) {
            $user = new User([
                'name' => 'Member Percobaan',
                'email' => $targetEmail,
                'tier' => 'member',
                'referral_code' => 'TEST'.strtoupper(substr(md5($targetEmail), 0, 4)),
            ]);
        }

        $this->comment("Sedang mengirim Welcome Email ke {$targetEmail}...");

        try {
            Mail::to($targetEmail)->send(new WelcomeUserMail($user));
            $this->info("✔ BERHASIL! Email percobaan telah berhasil dikirim ke: {$targetEmail}");
            $this->line('Silakan periksa kotak masuk (Inbox) atau folder Spam pada email Anda.');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('✖ GAGAL MENGIRIM EMAIL!');
            $this->error('Pesan Error: '.$e->getMessage());
            $this->newLine();
            $this->warn('Panduan Penyelesaian:');
            $this->line("1. Jika menggunakan Gmail, pastikan Anda menggunakan 'App Password' 16 digit, bukan password akun Gmail biasa.");
            $this->line('2. Pastikan port SMTP sesuai: 587 (TLS) atau 465 (SSL).');
            $this->line('3. Pastikan kredensial di file .env sudah sesuai (MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD).');

            return Command::FAILURE;
        }
    }
}
