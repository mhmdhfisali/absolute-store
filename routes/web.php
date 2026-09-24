<?php

use App\Http\Controllers\UserProfileController;
use App\Livewire\Admin\AuditLogsViewer;
use App\Livewire\Admin\CustomerServicePanel;
use App\Livewire\Admin\FinancialLedger;
use App\Livewire\Admin\ReviewModeration;
use App\Livewire\Admin\SkuManagement;
use App\Livewire\Admin\SupplierManagement;
use App\Livewire\Admin\SystemDiagnostics;
use App\Livewire\Admin\SystemSettings;
use App\Livewire\Admin\UserManagement;
use App\Mail\WelcomeUserMail;
use App\Models\Announcement;
use App\Models\AuditLog;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\PromoCode;
use App\Models\SavedAccount;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\WalletTransaction;
use App\Models\Wishlist;
use App\Services\DigiflazzService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/*
|--------------------------------------------------------------------------
| Absolute Store - Web & API Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// STOREFRONT PUBLIK & API TRANSAKSI
// Switch Localization Locale (ID / EN)
Route::match(['get', 'post'], '/switch-locale/{locale}', function (string $locale) {
    if (! in_array($locale, ['id', 'en'], true)) {
        $locale = 'id';
    }

    session()->put('locale', $locale);
    cookie()->queue(cookie()->forever('locale', $locale));
    app()->setLocale($locale);

    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'locale' => $locale,
            'message' => $locale === 'en' ? 'Language switched to English' : 'Bahasa berhasil dialihkan ke Indonesia',
        ]);
    }

    return back();
})->name('locale.switch');

// Affiliate Referral Route: Menyimpan kode referral ke cookie (30 hari) & session
Route::get('/ref/{code}', function (string $code) {
    $referrer = User::where('referral_code', strtoupper(trim($code)))->first();
    if ($referrer) {
        session()->put('ref_code', $referrer->referral_code);
        cookie()->queue(cookie('ref_code', $referrer->referral_code, 60 * 24 * 30));
    }

    return redirect()->route('home');
})->name('referral.link');

Route::get('/', function () {
    $categories = Category::all();
    $products = Product::where('is_active', true)->with(['category', 'items'])->get();
    $banners = Banner::where('is_active', true)->latest()->get();
    $announcements = Announcement::where('is_active', true)->latest()->get();

    return view('welcome', compact('categories', 'products', 'banners', 'announcements'));
})->name('home');

Route::get('/order/{slug}', function ($slug) {
    // 1. Cari produk aktif berdasarkan slug
    $product = Product::where('slug', $slug)
        ->where('is_active', true)
        ->with(['category', 'items' => function ($q) {
            $q->where('is_available', true)->orderBy('selling_price', 'asc');
        }])
        ->first();

    // 2. Fallback case-insensitive jika slug berbeda kapitalisasi
    if (! $product) {
        $product = Product::whereRaw('LOWER(slug) = ?', [strtolower($slug)])
            ->where('is_active', true)
            ->with(['category', 'items' => function ($q) {
                $q->where('is_available', true)->orderBy('selling_price', 'asc');
            }])
            ->first();

        if ($product) {
            return redirect()->route('order.show', $product->slug);
        }

        return redirect()->route('home')->with('error_alert', 'Produk atau game tidak ditemukan atau sedang dinonaktifkan.');
    }

    $paymentMethods = PaymentMethod::where('is_active', true)->get();
    $allProducts = Product::where('is_active', true)->with('category')->get();

    return view('order', compact('product', 'paymentMethods', 'allProducts'));
})->name('order.show');

// Cek Validasi Kode Promo via API/AJAX
Route::post('/api/check-promo', function (Request $request) {
    $code = strtoupper(trim($request->input('code', '')));
    $amount = (float) $request->input('amount', 0);

    $promo = PromoCode::where('code', $code)->where('is_active', true)->first();

    if (! $promo) {
        return response()->json(['success' => false, 'message' => 'Kode promo tidak ditemukan atau sudah tidak aktif.']);
    }

    if ($promo->valid_until && now()->gt($promo->valid_until)) {
        return response()->json(['success' => false, 'message' => 'Kode promo sudah kadaluarsa.']);
    }

    if ($promo->usage_limit && $promo->used_count >= $promo->usage_limit) {
        return response()->json(['success' => false, 'message' => 'Kuota penggunaan kode promo telah habis.']);
    }

    if ($amount < $promo->min_transaction) {
        return response()->json(['success' => false, 'message' => 'Minimal transaksi untuk promo ini adalah Rp '.number_format($promo->min_transaction, 0, ',', '.')]);
    }

    $discount = 0;
    if ($promo->type === 'flat') {
        $discount = $promo->discount_amount;
    } else {
        $discount = ($amount * $promo->discount_amount) / 100;
        if ($promo->max_discount && $discount > $promo->max_discount) {
            $discount = $promo->max_discount;
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Kode promo berhasil dipasang!',
        'discount' => $discount,
        'promo_id' => $promo->id,
    ]);
})->name('api.promo.check');

Route::get('/invoice/{invoice}', function ($invoice) {
    $transaction = Transaction::where('invoice_number', $invoice)
        ->with(['productItem.product', 'paymentMethod', 'promoCode'])
        ->firstOrFail();

    return view('invoice', compact('transaction'));
})->name('order.invoice');

Route::get('/invoice/{invoice}/print', function ($invoice) {
    $transaction = Transaction::where('invoice_number', $invoice)
        ->with(['productItem.product', 'paymentMethod', 'promoCode'])
        ->firstOrFail();

    return view('receipt', compact('transaction'));
})->name('order.invoice.print');

Route::post('/invoice/{invoice}/simulate', function ($invoice, WhatsAppService $waService, DigiflazzService $digiflazzService) {
    $trx = Transaction::with(['productItem.product', 'paymentMethod'])
        ->where('invoice_number', $invoice)
        ->firstOrFail();

    $trx->update([
        'payment_status' => 'paid',
        'delivery_status' => 'processing',
    ]);

    if (env('DIGIFLAZZ_USERNAME')) {
        $digiflazzService->processTransaction($trx);
    } else {
        $trx->update([
            'delivery_status' => 'success',
            'serial_number' => 'AS-SN-'.strtoupper(Str::random(16)),
        ]);
        $waService->sendPaymentSuccess($trx->fresh());
    }

    return back();
})->name('payment.simulate');

Route::get('/tracking', function (Request $request) {
    $searchInvoice = trim($request->query('invoice', ''));
    $transaction = null;

    if (! empty($searchInvoice)) {
        $transaction = Transaction::where('invoice_number', $searchInvoice)
            ->with(['productItem.product', 'paymentMethod', 'promoCode'])
            ->first();
    }

    return view('tracking', compact('transaction', 'searchInvoice'));
})->middleware('throttle:invoice-search')->name('order.tracking');

// ==========================================
// USER / MEMBER AUTH AREA
// ==========================================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Member Dashboard: Riwayat Pesanan, Saldo, Akun Favorit, Wishlist & Program Afiliasi
    Route::get('/dashboard', function () {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $user = Auth::user();

        $myTransactions = Transaction::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('contact_email_or_phone', $user->email);
        })
            ->with(['productItem.product', 'paymentMethod'])
            ->latest()
            ->paginate(10);

        $savedAccounts = $user->savedAccounts()->with('product')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $myDeposits = $user->deposits()->with('paymentMethod')->latest()->take(10)->get();

        $myWishlists = $user->wishlists()->with(['product.items' => fn ($q) => $q->where('is_available', true)])->latest()->get();

        if (empty($user->referral_code)) {
            $user->referral_code = 'AS'.strtoupper(Str::random(6));
            $user->saveQuietly();
        }

        $referralCode = $user->referral_code;
        $referralLink = route('referral.link', ['code' => $referralCode]);
        $totalDownlines = $user->downlines()->count();
        $totalAffiliateEarnings = (float) $user->affiliateEarnings()->sum('commission_amount');
        $recentAffiliateEarnings = $user->affiliateEarnings()->with(['buyer', 'transaction'])->latest()->take(10)->get();
        $walletMutations = $user->walletTransactions()->latest()->take(25)->get();

        return view('user-dashboard', compact(
            'myTransactions',
            'savedAccounts',
            'products',
            'paymentMethods',
            'myDeposits',
            'myWishlists',
            'referralCode',
            'referralLink',
            'totalDownlines',
            'totalAffiliateEarnings',
            'recentAffiliateEarnings',
            'walletMutations'
        ));
    })->name('dashboard');

    // Upgrade Tier Kemitraan Reseller / VIP
    Route::post('/user/upgrade-tier', function (Request $request) {
        $user = Auth::user();
        $targetTier = $request->input('tier', 'reseller');

        if (! in_array($targetTier, ['reseller', 'vip'], true)) {
            $targetTier = 'reseller';
        }

        if ($user->tier === $targetTier) {
            return back()->with('info_tier', 'Akun Anda sudah berada pada tier '.strtoupper($targetTier));
        }

        $minBalance = $targetTier === 'vip' ? 1000000 : 250000;
        if ((float) $user->balance < $minBalance) {
            return back()->with('error_tier', 'Saldo dompet minimal untuk upgrade ke '.strtoupper($targetTier).' adalah Rp '.number_format($minBalance, 0, ',', '.').' (Saldo saat ini: Rp '.number_format($user->balance, 0, ',', '.').'). Silakan lakukan deposit saldo terlebih dahulu.');
        }

        $user->update(['tier' => $targetTier]);

        // Catat Log Notifikasi
        UserNotification::notify(
            $user->id,
            'Selamat! Upgrade Tier Berhasil',
            "Akun Anda kini telah aktif sebagai mitra resmi Tier {$targetTier}. Nikmati potongan harga khusus reseller untuk setiap pembelian!",
            'system',
            route('dashboard')
        );

        return back()->with('success_tier', 'Selamat! Akun Anda berhasil di-upgrade ke tier '.strtoupper($targetTier).'. Harga grosir reseller telah aktif.');
    })->name('user.tier.upgrade');

    // CRUD Wishlist Member
    Route::delete('/user/wishlist/{id}', function ($id) {
        $wishlist = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlist->delete();

        return back()->with('success_wishlist', 'Produk berhasil dihapus dari daftar Wishlist.');
    })->name('user.wishlist.destroy');

    // Pengaturan Akun & Profil Pengguna
    Route::get('/user/settings', [UserProfileController::class, 'show'])->name('user.profile.show');
    Route::put('/user/settings/profile', [UserProfileController::class, 'updateProfile'])->name('user.profile.update');
    Route::delete('/user/settings/avatar', [UserProfileController::class, 'deleteAvatar'])->name('user.profile.delete-avatar');
    Route::put('/user/settings/password', [UserProfileController::class, 'updatePassword'])->name('user.profile.update-password');
    Route::put('/user/settings/notifications', [UserProfileController::class, 'updateNotifications'])->name('user.profile.update-notifications');
    Route::put('/user/settings/webhook', [UserProfileController::class, 'updateWebhook'])->name('user.profile.update-webhook');
    Route::post('/user/settings/other-browser-sessions', [UserProfileController::class, 'logoutOtherBrowserSessions'])->name('user.profile.logout-other-sessions');
    Route::post('/user/settings/api-tokens', [UserProfileController::class, 'createApiToken'])->name('user.profile.tokens.store');
    Route::delete('/user/settings/api-tokens/{id}', [UserProfileController::class, 'deleteApiToken'])->name('user.profile.tokens.destroy');
    Route::get('/user/settings/export-data', [UserProfileController::class, 'exportData'])->name('user.profile.export-data');
    Route::delete('/user/settings/account', [UserProfileController::class, 'deleteAccount'])->name('user.profile.delete-account');

    // CRUD Akun Game Favorit Member
    Route::post('/user/saved-accounts', function (Request $request, DigiflazzService $digiflazz) {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'account_name' => 'nullable|string|max:50',
            'target_account' => 'required|string|max:100',
            'target_zone' => 'nullable|string|max:50',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $inquiry = $digiflazz->checkAccount($product->slug, $validated['target_account'], $validated['target_zone'] ?? null);
        $nickname = $inquiry['status'] ? $inquiry['username'] : null;

        SavedAccount::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'target_account' => $validated['target_account'],
                'target_zone' => $validated['target_zone'] ?: null,
            ],
            [
                'account_name' => $validated['account_name'] ?: ($product->name.' Account'),
                'nickname' => $nickname,
            ]
        );

        return back()->with('success_account', 'Akun game favorit berhasil disimpan!');
    })->name('user.saved-accounts.store');

    Route::delete('/user/saved-accounts/{id}', function ($id) {
        $account = SavedAccount::where('user_id', Auth::id())->findOrFail($id);
        $account->delete();

        return back()->with('success_account', 'Akun berhasil dihapus dari daftar tersimpan.');
    })->name('user.saved-accounts.destroy');

    // Deposit Saldo Member
    Route::post('/user/deposit', function (Request $request) {
        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:10000|max:10000000',
        ], [
            'amount.min' => 'Minimal deposit saldo adalah Rp 10.000',
        ]);

        $pm = PaymentMethod::findOrFail($validated['payment_method_id']);
        $fee = $pm->fee_flat + (int) round(($validated['amount'] * $pm->fee_percent) / 100);
        $total = $validated['amount'] + $fee;

        $depoNumber = 'DEP-'.date('Ymd').'-'.strtoupper(Str::random(6));

        DB::transaction(function () use ($validated, $pm, $fee, $total, $depoNumber) {
            $user = User::where('id', Auth::id())->lockForUpdate()->first();

            Deposit::create([
                'deposit_number' => $depoNumber,
                'user_id' => $user->id,
                'payment_method_id' => $pm->id,
                'amount' => $validated['amount'],
                'fee_amount' => $fee,
                'total_amount' => $total,
                'status' => 'paid', // Instant dev mode
            ]);

            $balanceBefore = (float) $user->balance;
            $balanceAfter = $balanceBefore + (float) $validated['amount'];

            $user->update(['balance' => $balanceAfter]);

            WalletTransaction::create([
                'user_id' => $user->id,
                'reference_id' => $depoNumber,
                'type' => 'credit',
                'amount' => $validated['amount'],
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'category' => 'deposit',
                'description' => 'Deposit Saldo Member via '.$pm->name.' ('.$depoNumber.')',
            ]);
        });

        return back()->with('success_deposit', 'Deposit sebesar Rp '.number_format($validated['amount'], 0, ',', '.').' berhasil ditambahkan ke saldo akun!');
    })->name('user.deposit.store');

    // In-App Notification Center Endpoints
    Route::post('/user/notifications/{id}/read', function ($id) {
        $notification = UserNotification::where('user_id', Auth::id())->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    })->name('user.notifications.read');

    Route::post('/user/notifications/read-all', function () {
        UserNotification::where('user_id', Auth::id())->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    })->name('user.notifications.read-all');
});

// ==========================================
// ALIAS & REDIRECT UNTUK MENCEGAH ROUTENOTFOUNDEXCEPTION
// ==========================================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin',
])->group(function () {
    Route::get('/admin/barang', fn () => redirect()->route('admin.products.index'))->name('barang.index');
    Route::get('/admin/sku', fn () => redirect()->route('admin.sku-management'))->name('sku.index');
    Route::get('/admin/mutasi', fn () => redirect()->route('admin.financial-ledger'))->name('mutasi.index');
    Route::get('/admin/laporan', fn () => redirect()->route('admin.financial-ledger'))->name('laporan.index');
    Route::get('/admin/supplier', fn () => redirect()->route('admin.supplier-management'))->name('supplier.index');
    Route::get('/admin/pelanggan', fn () => redirect()->route('admin.user-management'))->name('pelanggan.index');

    // Alias Ekspor CSV agar route('dashboard.export') tidak error
    Route::get('/admin/export-csv-alias', function () {
        return redirect()->route('admin.dashboard.export');
    })->name('dashboard.export');
});

// ==========================================
// ADMIN DASHBOARD & MANAGEMENT CONSOLE (AUTH + ADMIN ROLE GUARD)
// ==========================================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin',
])->prefix('admin')->group(function () {

    // 1. Dashboard Monitoring & Metrik
    Route::get('/dashboard', function (Request $request, DigiflazzService $digiflazzService) {
        $selectedStatus = $request->query('status', 'all');
        $searchQuery = trim($request->query('q', ''));

        $totalRevenue = Transaction::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Transaction::count();
        $successfulOrders = Transaction::where('payment_status', 'paid')->count();
        $pendingOrders = Transaction::where('payment_status', 'unpaid')->count();

        $digiflazzBalance = env('DIGIFLAZZ_USERNAME') ? $digiflazzService->checkBalance() : 0;

        $statusCounts = [
            'all' => Transaction::count(),
            'paid' => Transaction::where('payment_status', 'paid')->count(),
            'unpaid' => Transaction::where('payment_status', 'unpaid')->count(),
            'expired' => Transaction::where('payment_status', 'expired')->count(),
            'failed' => Transaction::where('payment_status', 'failed')->count(),
        ];

        $transactionsQuery = Transaction::with(['productItem.product', 'paymentMethod', 'promoCode'])->latest();

        if ($selectedStatus !== 'all') {
            $transactionsQuery->where('payment_status', $selectedStatus);
        }

        if (! empty($searchQuery)) {
            $transactionsQuery->where(function ($query) use ($searchQuery) {
                $query->where('invoice_number', 'like', "%{$searchQuery}%")
                    ->orWhere('target_account', 'like', "%{$searchQuery}%")
                    ->orWhere('contact_email_or_phone', 'like', "%{$searchQuery}%");
            });
        }

        $recentTransactions = $transactionsQuery->paginate(10)->withQueryString();

        return view('dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'successfulOrders',
            'pendingOrders',
            'recentTransactions',
            'selectedStatus',
            'statusCounts',
            'searchQuery',
            'digiflazzBalance'
        ));
    })->name('admin.dashboard');

    Route::post('/transactions/{invoice}/retry', function ($invoice, DigiflazzService $digiflazzService, WhatsAppService $waService) {
        $trx = Transaction::with(['productItem.product', 'paymentMethod'])
            ->where('invoice_number', $invoice)
            ->firstOrFail();

        if ($trx->payment_status !== 'paid') {
            return back()->with('error', "Transaksi {$trx->invoice_number} belum dibayar, tidak dapat dikirim.");
        }

        if ($trx->delivery_status === 'success') {
            return back()->with('error', "Transaksi {$trx->invoice_number} sudah berhasil terkirim sebelumnya.");
        }

        $trx->update(['delivery_status' => 'processing']);

        AuditLog::log('RETRY_TRANSACTION', "Memproses ulang pengiriman transaksi {$trx->invoice_number}");

        if (env('DIGIFLAZZ_USERNAME')) {
            $result = $digiflazzService->processTransaction($trx);
            $status = strtolower($result['status'] ?? '');

            if ($status === 'sukses') {
                return back()->with('success', "Order {$trx->invoice_number} berhasil dipproses ulang! SN: ".($trx->fresh()->serial_number ?? '-'));
            } elseif ($status === 'pending') {
                return back()->with('success', "Order {$trx->invoice_number} sedang dipproses oleh provider.");
            } else {
                return back()->with('error', "Gagal memproses ulang order {$trx->invoice_number}: ".($result['data']['message'] ?? 'Ditolak provider'));
            }
        } else {
            $trx->update([
                'delivery_status' => 'success',
                'serial_number' => 'AS-RETRY-'.strtoupper(Str::random(12)),
            ]);
            $waService->sendPaymentSuccess($trx->fresh());

            return back()->with('success', "Simulasi: Order {$trx->invoice_number} berhasil di-retry!");
        }
    })->name('admin.transactions.retry');

    Route::get('/digiflazz/balance', function (DigiflazzService $digiflazzService) {
        if (! env('DIGIFLAZZ_USERNAME')) {
            return response()->json([
                'success' => false,
                'message' => 'Kredensial Digiflazz belum dikonfigurasi di .env',
                'balance' => 0,
                'formatted' => 'Dev Off',
            ]);
        }

        $balance = $digiflazzService->checkBalance();

        return response()->json([
            'success' => true,
            'balance' => $balance,
            'formatted' => 'Rp '.number_format($balance, 0, ',', '.'),
        ]);
    })->name('admin.digiflazz.balance');

    Route::post('/digiflazz/sync', function (Request $request, DigiflazzService $digiflazzService) {
        $marginFlat = (int) $request->input('margin_flat', 1500);
        $marginPercent = (float) $request->input('margin_percent', 0.0);

        $result = $digiflazzService->syncPriceList($marginFlat, $marginPercent);

        AuditLog::log('SYNC_PRICE_LIST', 'Melakukan sinkronisasi daftar harga SKU dari Digiflazz');

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    })->name('admin.digiflazz.sync');

    // 2. Export Laporan Transaksi CSV
    Route::get('/dashboard/export-csv', function (): StreamedResponse {
        $fileName = 'absolute-store-transactions-'.date('Y-m-d_H-i-s').'.csv';

        AuditLog::log('EXPORT_CSV', 'Mengunduh rekapitulasi data transaksi dalam format CSV');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Invoice Number',
                'Tanggal',
                'Layanan',
                'Item SKU',
                'Tujuan / User ID',
                'Zone ID',
                'Kontak',
                'Metode Pembayaran',
                'Kupon Promo',
                'Potongan Diskon',
                'Total Bayar',
                'Status Pembayaran',
                'Status Pengiriman',
                'Serial Number / Token',
            ]);

            Transaction::with(['productItem.product', 'paymentMethod', 'promoCode'])
                ->latest()
                ->chunk(100, function ($transactions) use ($handle) {
                    foreach ($transactions as $t) {
                        fputcsv($handle, [
                            $t->invoice_number,
                            $t->created_at->format('Y-m-d H:i:s'),
                            $t->productItem?->product?->name ?? '-',
                            $t->productItem?->name ?? '-',
                            $t->target_account,
                            $t->target_zone ?? '-',
                            $t->contact_email_or_phone,
                            $t->paymentMethod?->code ?? '-',
                            $t->promoCode?->code ?? '-',
                            $t->discount_amount,
                            $t->total_amount,
                            $t->payment_status,
                            $t->delivery_status,
                            $t->serial_number ?? '-',
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    })->name('admin.dashboard.export');

    // 3. Manajemen Produk & SKU
    Route::get('/products', function () {
        $products = Product::with(['category', 'items'])->latest()->paginate(10);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    })->name('admin.products.index');

    Route::post('/products', function (Request $request) {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'input_type' => 'required|in:id_only,id_and_zone,phone_number,meter_number',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $slug = Str::slug($validated['name']);
        if (Product::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::lower(Str::random(4));
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('products', 'public');
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'input_type' => $validated['input_type'],
            'thumbnail' => $thumbnailPath,
            'is_active' => true,
        ]);

        AuditLog::log('CREATE_PRODUCT', "Menambahkan produk baru: {$product->name}");

        return back()->with('success', 'Produk baru berhasil ditambahkan!');
    })->name('admin.products.store');

    Route::get('/products/{id}/edit', function ($id) {
        $product = Product::with(['category', 'items'])->findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    })->name('admin.products.edit');

    Route::put('/products/{id}', function (Request $request, $id) {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'input_type' => 'required|in:id_only,id_and_zone,phone_number,meter_number',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'input_type' => $validated['input_type'],
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && ! filter_var($product->thumbnail, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        $product->update($data);

        AuditLog::log('UPDATE_PRODUCT', "Memperbarui produk: {$product->name}");

        return back()->with('success', 'Data produk berhasil diperbarui!');
    })->name('admin.products.update');

    Route::patch('/products/{id}/toggle', function ($id) {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => ! $product->is_active]);

        AuditLog::log('TOGGLE_PRODUCT', "Mengubah status aktif produk {$product->name}");

        return back()->with('success', "Status {$product->name} berhasil diubah.");
    })->name('admin.products.toggle');

    Route::delete('/products/{id}', function ($id) {
        $product = Product::findOrFail($id);

        if ($product->thumbnail && ! filter_var($product->thumbnail, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $productName = $product->name;
        $product->items()->delete();
        $product->delete();

        AuditLog::log('DELETE_PRODUCT', "Menghapus produk: {$productName}");

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    })->name('admin.products.destroy');

    // CRUD SKU Item
    Route::post('/products/{id}/items', function (Request $request, $id) {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku_code' => 'required|string|max:100|unique:product_items,sku_code',
            'original_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'reseller_price' => 'nullable|numeric|min:0',
        ]);

        ProductItem::create([
            'product_id' => $product->id,
            'name' => $validated['name'],
            'sku_code' => strtoupper($validated['sku_code']),
            'original_price' => $validated['original_price'],
            'selling_price' => $validated['selling_price'],
            'reseller_price' => $validated['reseller_price'] ?? null,
            'is_available' => true,
        ]);

        AuditLog::log('CREATE_SKU', "Menambahkan item SKU {$validated['name']} untuk {$product->name}");

        return back()->with('success', 'Item SKU baru berhasil ditambahkan!');
    })->name('admin.items.store');

    Route::put('/items/{id}', function (Request $request, $id) {
        $item = ProductItem::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku_code' => 'required|string|max:100|unique:product_items,sku_code,'.$item->id,
            'original_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'reseller_price' => 'nullable|numeric|min:0',
            'is_available' => 'nullable|boolean',
        ]);

        $item->update([
            'name' => $validated['name'],
            'sku_code' => strtoupper($validated['sku_code']),
            'original_price' => $validated['original_price'],
            'selling_price' => $validated['selling_price'],
            'reseller_price' => $validated['reseller_price'] ?? null,
            'is_available' => $request->has('is_available'),
        ]);

        AuditLog::log('UPDATE_SKU', "Mengubah harga/konfigurasi SKU: {$item->name}");

        return back()->with('success', "Harga {$item->name} berhasil diperbarui.");
    })->name('admin.items.update');

    Route::delete('/items/{id}', function ($id) {
        $item = ProductItem::findOrFail($id);
        $name = $item->name;
        $item->delete();

        AuditLog::log('DELETE_SKU', "Menghapus SKU: {$name}");

        return back()->with('success', 'Item berhasil dihapus.');
    })->name('admin.items.destroy');

    // 4. Payment Methods
    Route::get('/payment-methods', function () {
        $paymentMethods = PaymentMethod::all();

        return view('admin.payment-methods.index', compact('paymentMethods'));
    })->name('admin.payments.index');

    Route::put('/payment-methods/{id}', function (Request $request, $id) {
        $payment = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'fee_flat' => 'required|numeric|min:0',
            'fee_percent' => 'required|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $payment->update([
            'name' => $validated['name'],
            'fee_flat' => $validated['fee_flat'],
            'fee_percent' => $validated['fee_percent'],
            'is_active' => $request->has('is_active'),
        ]);

        AuditLog::log('UPDATE_PAYMENT_METHOD', "Memperbarui biaya {$payment->name}");

        return back()->with('success', "Konfigurasi {$payment->name} berhasil diperbarui.");
    })->name('admin.payments.update');

    Route::patch('/payment-methods/{id}/toggle', function ($id) {
        $payment = PaymentMethod::findOrFail($id);
        $payment->update(['is_active' => ! $payment->is_active]);

        AuditLog::log('TOGGLE_PAYMENT_METHOD', "Mengubah status aktif channel {$payment->name}");

        return back()->with('success', "Status {$payment->name} berhasil diubah.");
    })->name('admin.payments.toggle');

    // 5. Banner Promo & Announcement
    Route::get('/promotions', function () {
        $banners = Banner::latest()->get();
        $announcements = Announcement::latest()->get();

        return view('admin.promotions.index', compact('banners', 'announcements'));
    })->name('admin.promotions.index');

    Route::post('/promotions/banners', function (Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'target_url' => 'nullable|url',
        ]);

        $path = $request->file('banner_image')->store('banners', 'public');

        Banner::create([
            'title' => $validated['title'],
            'image_url' => $path,
            'target_url' => $validated['target_url'] ?? null,
            'is_active' => true,
        ]);

        AuditLog::log('CREATE_BANNER', "Mengunggah banner promosi: {$validated['title']}");

        return back()->with('success', 'Banner promo baru berhasil diunggah!');
    })->name('admin.banners.store');

    Route::patch('/promotions/banners/{id}/toggle', function ($id) {
        $banner = Banner::findOrFail($id);
        $banner->update(['is_active' => ! $banner->is_active]);

        return back()->with('success', "Status banner {$banner->title} berhasil diubah.");
    })->name('admin.banners.toggle');

    Route::delete('/promotions/banners/{id}', function ($id) {
        $banner = Banner::findOrFail($id);

        if (! filter_var($banner->image_url, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($banner->image_url);
        }

        $banner->delete();

        return back()->with('success', 'Banner dan file gambar berhasil dihapus.');
    })->name('admin.banners.destroy');

    Route::post('/promotions/announcements', function (Request $request) {
        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        Announcement::create([
            'content' => $validated['content'],
            'is_active' => true,
        ]);

        AuditLog::log('CREATE_ANNOUNCEMENT', 'Membuat pengumuman teks berjalan baru');

        return back()->with('success', 'Teks pengumuman berjalan berhasil disimpan!');
    })->name('admin.announcements.store');

    Route::patch('/promotions/announcements/{id}/toggle', function ($id) {
        $announcement = Announcement::findOrFail($id);
        $announcement->update(['is_active' => ! $announcement->is_active]);

        return back()->with('success', 'Status pengumuman berhasil diubah.');
    })->name('admin.announcements.toggle');

    Route::delete('/promotions/announcements/{id}', function ($id) {
        Announcement::findOrFail($id)->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    })->name('admin.announcements.destroy');

    // 6. Manajemen Kode Promo / Kupon Diskon (CRUD)
    Route::get('/promo-codes', function () {
        $promoCodes = PromoCode::latest()->paginate(10);

        return view('admin.promo-codes.index', compact('promoCodes'));
    })->name('admin.promocodes.index');

    Route::post('/promo-codes', function (Request $request) {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code',
            'type' => 'required|in:flat,percentage',
            'discount_amount' => 'required|numeric|min:1',
            'max_discount' => 'nullable|numeric|min:0',
            'min_transaction' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_until' => 'nullable|date',
        ]);

        PromoCode::create([
            'code' => strtoupper(trim($validated['code'])),
            'type' => $validated['type'],
            'discount_amount' => $validated['discount_amount'],
            'max_discount' => $validated['max_discount'] ?? null,
            'min_transaction' => $validated['min_transaction'] ?? 0,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'valid_until' => $validated['valid_until'] ?? null,
            'is_active' => true,
        ]);

        AuditLog::log('CREATE_PROMO', "Menerbitkan kode promo diskon: {$validated['code']}");

        return back()->with('success', "Kode kupon {$validated['code']} berhasil diterbitkan!");
    })->name('admin.promocodes.store');

    Route::patch('/promo-codes/{id}/toggle', function ($id) {
        $promo = PromoCode::findOrFail($id);
        $promo->update(['is_active' => ! $promo->is_active]);

        return back()->with('success', "Status kupon {$promo->code} berhasil diubah.");
    })->name('admin.promocodes.toggle');

    Route::delete('/promo-codes/{id}', function ($id) {
        $promo = PromoCode::findOrFail($id);
        $code = $promo->code;
        $promo->delete();

        AuditLog::log('DELETE_PROMO', "Menghapus kode kupon: {$code}");

        return back()->with('success', "Kode kupon {$code} berhasil dihapus.");
    })->name('admin.promocodes.destroy');

    // 7. Manajemen Pengguna & Role (Khusus Superadmin)
    Route::get('/users', function (Request $request) {
        if (! Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses Terbatas: Hanya Superadmin yang berhak mengelola akun dan role pengguna.');
        }

        $search = trim($request->query('q', ''));
        $usersQuery = User::latest();

        if (! empty($search)) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    })->name('admin.users.index');

    Route::put('/users/{id}/update-full', function (Request $request, $id) {
        if (! Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $targetUser = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:user,admin,superadmin',
            'tier' => 'required|in:member,reseller,vip',
            'balance' => 'required|numeric|min:0',
        ]);

        if ($targetUser->id === Auth::id() && $validated['role'] !== 'superadmin') {
            return back()->with('error', 'Anda tidak dapat menurunkan role superadmin akun Anda sendiri.');
        }

        $targetUser->update($validated);

        AuditLog::log('UPDATE_USER', "Mengubah data, role, dan saldo akun {$targetUser->email}", $validated);

        return back()->with('success', "Data pengguna {$targetUser->name} berhasil diperbarui!");
    })->name('admin.users.update-full');

    Route::delete('/users/{id}', function ($id) {
        if (! Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $targetUser = User::findOrFail($id);

        if ($targetUser->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $email = $targetUser->email;
        $targetUser->delete();

        AuditLog::log('DELETE_USER', "Menghapus akun pengguna: {$email}");

        return back()->with('success', "Pengguna {$targetUser->name} berhasil dihapus.");
    })->name('admin.users.destroy');

    // 8. Halaman Audit Activity Logs (Khusus Superadmin)
    Route::get('/audit-logs', function (Request $request) {
        if (! Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses Terbatas: Hanya Superadmin yang berhak melihat log audit sistem.');
        }

        $search = trim($request->query('q', ''));
        $logsQuery = AuditLog::with('user')->latest();

        if (! empty($search)) {
            $logsQuery->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $logsQuery->paginate(15)->withQueryString();

        return view('admin.audit-logs.index', compact('logs', 'search'));
    })->name('admin.audit-logs.index');

    // ==========================================
    // ENTERPRISE ADVANCED ADMIN LIVEWIRE MODULES
    // ==========================================
    Route::get('/sku-management', SkuManagement::class)->name('admin.sku-management');
    Route::get('/supplier-management', SupplierManagement::class)->name('admin.supplier-management');
    Route::get('/users-management', UserManagement::class)->name('admin.user-management');
    Route::get('/financial-ledger', FinancialLedger::class)->name('admin.financial-ledger');
    Route::get('/customer-service', CustomerServicePanel::class)->name('admin.customer-service');
    Route::get('/audit-logs-viewer', AuditLogsViewer::class)->name('admin.audit-logs-viewer');
    Route::get('/system-settings', SystemSettings::class)->name('admin.system-settings');
    Route::get('/reviews', ReviewModeration::class)->name('admin.reviews.index');
    Route::get('/diagnostics', SystemDiagnostics::class)->name('admin.diagnostics.index');
});

// Live Preview Email Template
Route::get('/mail/preview/welcome', function () {
    $user = User::first() ?? new User([
        'name' => 'Alexander Lie',
        'email' => 'user@example.com',
        'tier' => 'member',
        'referral_code' => 'AS-VIP88',
    ]);

    return new WelcomeUserMail($user);
})->name('mail.preview.welcome');
