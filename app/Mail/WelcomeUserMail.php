<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public string $welcomeCoupon = 'ABSOLUTEBARU'
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $appName = config('app.name', 'Absolute Store');

        return new Envelope(
            from: new Address(
                config('mail.from.address', 'support@absolutestore.id'),
                config('mail.from.name', $appName)
            ),
            subject: "Selamat Datang di {$appName}! 🚀 Akses Akun & Voucher Spesial Anda",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-user',
            with: [
                'user' => $this->user,
                'welcomeCoupon' => $this->welcomeCoupon,
                'homeUrl' => route('home'),
                'dashboardUrl' => url('/dashboard'),
                'referralUrl' => $this->user->referral_code ? route('referral.link', $this->user->referral_code) : null,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
