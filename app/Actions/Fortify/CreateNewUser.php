<?php

namespace App\Actions\Fortify;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $refCode = request()->cookie('ref_code') ?? session('ref_code') ?? ($input['referral_code'] ?? null);
        $referrerId = null;
        if ($refCode) {
            $referrer = User::where('referral_code', strtoupper(trim($refCode)))->first();
            if ($referrer) {
                $referrerId = $referrer->id;
            }
        }

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'referred_by_id' => $referrerId,
        ]);

        // Kirim email selamat datang resmi dengan template mewah & voucher diskon
        try {
            Mail::to($user->email)->send(new WelcomeUserMail($user));
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim welcome email ke {$user->email}: ".$e->getMessage());
        }

        // Kirim notifikasi internal in-app
        try {
            UserNotification::notify(
                $user->id,
                'Selamat Datang di Absolute Store! 🚀',
                'Akun Anda berhasil dibuat. Gunakan kupon diskon ABSOLUTEBARU untuk transaksi perdana Anda.',
                'success',
                route('home')
            );
        } catch (\Throwable $e) {
            Log::warning('Gagal membuat notifikasi selamat datang: '.$e->getMessage());
        }

        return $user;
    }
}
