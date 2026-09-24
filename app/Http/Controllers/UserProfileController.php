<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Laravel\Jetstream\Agent;

class UserProfileController extends Controller
{
    /**
     * Tampilkan Halaman Profil & Pengaturan Lengkap
     */
    public function show(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil sesi login aktif dari tabel sessions jika driver database aktif
        $sessions = collect(
            DB::table('sessions')
                ->where('user_id', $user->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) use ($request) {
            $agent = new Agent();
            if ($session->user_agent) {
                $agent->setUserAgent($session->user_agent);
            }

            return (object) [
                'id' => $session->id,
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform' => $agent->platform() ?: 'Unknown OS',
                    'browser' => $agent->browser() ?: 'Unknown Browser',
                ],
                'ip_address' => $session->ip_address ?: '127.0.0.1',
                'is_current_device' => $session->id === $request->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });

        // Daftar Akun Game Favorit
        $savedAccounts = $user->savedAccounts()->with('product')->latest()->get();

        // Daftar Game Aktif untuk Preset Selector
        $products = Product::where('status', 'active')->orderBy('name')->get(['id', 'name', 'slug', 'category_id']);

        // Personal Access Tokens (Sanctum)
        $tokens = $user->tokens()->latest()->get();

        // Statistik Pengguna & Tier Progress
        $totalSpent = (float) $user->transactions()->where('status', 'PAID')->sum('amount');
        $totalOrders = $user->transactions()->where('status', 'PAID')->count();
        $totalReviews = $user->reviews()->count();
        $referralEarnings = (float) $user->affiliateEarnings()->sum('commission_amount');

        // Kalkulasi Progress Tier Berikutnya
        $nextTier = match ($user->tier) {
            'member' => [
                'name' => 'Reseller VIP',
                'target_spent' => 500000,
                'current_spent' => $totalSpent,
                'percent' => min(100, round(($totalSpent / 500000) * 100)),
                'benefits' => 'Harga produk lebih murah 2-5% & Akses API H2H',
            ],
            'reseller' => [
                'name' => 'Distributor VIP',
                'target_spent' => 5000000,
                'current_spent' => $totalSpent,
                'percent' => min(100, round(($totalSpent / 5000000) * 100)),
                'benefits' => 'Harga grosir tier terendah & Dedicated Account Manager',
            ],
            default => [
                'name' => 'Ultimate VIP Tier',
                'target_spent' => $totalSpent,
                'current_spent' => $totalSpent,
                'percent' => 100,
                'benefits' => 'Tingkat tertinggi tercapai dengan diskon maksimal!',
            ],
        };

        return view('profile.custom-show', compact(
            'user',
            'sessions',
            'savedAccounts',
            'products',
            'tokens',
            'totalSpent',
            'totalOrders',
            'totalReviews',
            'referralEarnings',
            'nextTier'
        ));
    }

    /**
     * Update Informasi Dasar Profil, Kontak & Foto Avatar
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:25'],
            'discord_tag' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:500'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'photo.max' => 'Ukuran foto maksimal adalah 2MB.',
            'photo.image' => 'Berkas harus berupa gambar valid (JPG, PNG, atau WEBP).',
        ]);

        if ($request->hasFile('photo')) {
            $user->updateProfilePhoto($request->file('photo'));
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'discord_tag' => $validated['discord_tag'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        return back()->with('status_profile', 'Informasi profil akun Anda berhasil diperbarui!');
    }

    /**
     * Hapus Foto Avatar Pengguna
     */
    public function deleteAvatar()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->deleteProfilePhoto();

        return back()->with('status_profile', 'Foto profil berhasil dihapus dan dikembalikan ke inisial nama.');
    }

    /**
     * Update Kata Sandi Pengguna
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.current_password' => 'Password saat ini tidak cocok dengan catatan sistem.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status_password', 'Kata sandi akun Anda berhasil diperbarui dengan aman!');
    }

    /**
     * Update Preferensi Notifikasi & Komunikasi
     */
    public function updateNotifications(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $preferences = [
            'whatsapp_orders' => $request->boolean('whatsapp_orders'),
            'email_receipts' => $request->boolean('email_receipts'),
            'promo_alerts' => $request->boolean('promo_alerts'),
            'security_alerts' => $request->boolean('security_alerts'),
        ];

        $user->update([
            'notification_preferences' => $preferences,
        ]);

        return back()->with('status_notifications', 'Preferensi notifikasi Anda berhasil disimpan!');
    }

    /**
     * Update Webhook URL Reseller / Developer
     */
    public function updateWebhook(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'webhook_url' => ['nullable', 'url', 'max:255'],
        ], [
            'webhook_url.url' => 'Format Webhook URL tidak valid. Gunakan https://...',
        ]);

        $user->update([
            'webhook_url' => $validated['webhook_url'] ?? null,
        ]);

        return back()->with('status_webhook', 'Webhook URL berhasil diperbarui!');
    }

    /**
     * Logout Sesi Browser di Perangkat Lain
     */
    public function logoutOtherBrowserSessions(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.current_password' => 'Kata sandi yang Anda masukkan tidak sesuai.',
        ]);

        Auth::logoutOtherDevices($request->password);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        DB::table('sessions')
            ->where('user_id', $user->getAuthIdentifier())
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return back()->with('status_sessions', 'Semua sesi browser di perangkat lain berhasil dinonaktifkan.');
    }

    /**
     * Buat API Token Baru (Sanctum)
     */
    public function createApiToken(Request $request)
    {
        $validated = $request->validate([
            'token_name' => ['required', 'string', 'max:50'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $token = $user->createToken($validated['token_name']);

        return back()
            ->with('status_tokens', 'API Token berhasil dibuat!')
            ->with('new_plain_token', $token->plainTextToken);
    }

    /**
     * Cabut / Hapus API Token
     */
    public function deleteApiToken($tokenId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->tokens()->where('id', $tokenId)->delete();

        return back()->with('status_tokens', 'API Token berhasil dicabut dan tidak dapat digunakan kembali.');
    }

    /**
     * Unduh Ringkasan Data Akun (Export JSON)
     */
    public function exportData()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $exportData = [
            'app' => config('app.name', 'Absolute Store'),
            'exported_at' => now()->toIso8601String(),
            'profile' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'discord_tag' => $user->discord_tag,
                'role' => $user->role,
                'tier' => $user->tier,
                'balance' => $user->balance,
                'referral_code' => $user->referral_code,
                'registered_at' => $user->created_at->toIso8601String(),
            ],
            'saved_accounts' => $user->savedAccounts()->with('product:id,name,slug')->get(),
            'recent_transactions' => $user->transactions()
                ->latest()
                ->take(50)
                ->get(['id', 'reference_no', 'amount', 'status', 'created_at']),
            'wallet_history' => $user->walletTransactions()
                ->latest()
                ->take(50)
                ->get(['id', 'type', 'amount', 'balance_after', 'description', 'created_at']),
        ];

        $json = json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $filename = 'absolute-store-data-user-'.$user->id.'-'.date('YmdHis').'.json';

        return response()->streamDownload(function () use ($json) {
            echo $json;
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Hapus / Nonaktifkan Akun Pengguna
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
            'confirmation' => ['required', 'in:HAPUS AKUN SAYA'],
        ], [
            'password.current_password' => 'Kata sandi verifikasi tidak cocok.',
            'confirmation.in' => 'Ketik frasa konfirmasi "HAPUS AKUN SAYA" secara tepat.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cabut token & hapus foto profil jika ada
        $user->tokens()->delete();
        $user->deleteProfilePhoto();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('info', 'Akun Anda berhasil dihapus dari sistem kami.');
    }
}

