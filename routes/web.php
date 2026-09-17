<?php

use App\Models\Announcement;
use App\Models\Banner;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\Transaction;
use App\Services\DigiflazzService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

// ==========================================
// STOREFRONT PUBLIK
// ==========================================

// Katalog Depan
Route::get('/', function () {
    $categories = Category::all();
    $products = Product::where('is_active', true)->with(['category', 'items'])->get();
    $banners = Banner::where('is_active', true)->latest()->get();
    $announcements = Announcement::where('is_active', true)->latest()->get();

    return view('welcome', compact('categories', 'products', 'banners', 'announcements'));
})->name('home');

// Form Order Dinamis
Route::get('/order/{slug}', function ($slug) {
    $product = Product::where('slug', $slug)->with('items')->firstOrFail();
    return view('order', compact('product'));
})->name('order.show');

// Halaman Status Invoice & QRIS
Route::get('/invoice/{invoice}', function ($invoice) {
    $transaction = Transaction::where('invoice_number', $invoice)
        ->with(['productItem.product', 'paymentMethod'])
        ->firstOrFail();
    return view('invoice', compact('transaction'));
})->name('order.invoice');

// Simulasi Pembayaran Berhasil (Dev Mode)
Route::post('/invoice/{invoice}/simulate', function ($invoice, WhatsAppService $waService, DigiflazzService $digiflazzService) {
    $trx = Transaction::with(['productItem.product', 'paymentMethod'])
        ->where('invoice_number', $invoice)
        ->firstOrFail();

    $trx->update([
        'payment_status'  => 'paid',
        'delivery_status' => 'processing',
    ]);

    // Jalankan transaksi ke Digiflazz jika mode dev / testing aktif
    if (env('DIGIFLAZZ_USERNAME')) {
        $digiflazzService->processTransaction($trx);
    } else {
        $trx->update([
            'delivery_status' => 'success',
            'serial_number'   => 'AS-SN-' . strtoupper(Str::random(16)),
        ]);
    }

    $waService->sendPaymentSuccess($trx->fresh());

    return back();
})->name('payment.simulate');

// Lacak Pesanan / Cek Transaksi Publik
Route::get('/tracking', function (Request $request) {
    $searchInvoice = trim($request->query('invoice', ''));
    $transaction = null;

    if (!empty($searchInvoice)) {
        $transaction = Transaction::where('invoice_number', $searchInvoice)
            ->with(['productItem.product', 'paymentMethod'])
            ->first();
    }

    return view('tracking', compact('transaction', 'searchInvoice'));
})->name('order.tracking');

// ==========================================
// ADMIN DASHBOARD & MANAGEMENT CONSOLE (AUTH)
// ==========================================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('admin')->group(function () {

    // 1. Dashboard Monitoring & Metrik
    Route::get('/dashboard', function (Request $request, DigiflazzService $digiflazzService) {
        $selectedStatus = $request->query('status', 'all');
        $searchQuery = trim($request->query('q', ''));

        $totalRevenue = Transaction::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Transaction::count();
        $successfulOrders = Transaction::where('payment_status', 'paid')->count();
        $pendingOrders = Transaction::where('payment_status', 'unpaid')->count();

        // Ambil saldo deposit Digiflazz
        $digiflazzBalance = env('DIGIFLAZZ_USERNAME') ? $digiflazzService->checkBalance() : 0;

        $statusCounts = [
            'all'     => Transaction::count(),
            'paid'    => Transaction::where('payment_status', 'paid')->count(),
            'unpaid'  => Transaction::where('payment_status', 'unpaid')->count(),
            'expired' => Transaction::where('payment_status', 'expired')->count(),
            'failed'  => Transaction::where('payment_status', 'failed')->count(),
        ];

        $transactionsQuery = Transaction::with(['productItem.product', 'paymentMethod'])->latest();

        if ($selectedStatus !== 'all') {
            $transactionsQuery->where('payment_status', $selectedStatus);
        }

        if (!empty($searchQuery)) {
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
    })->name('dashboard');

    // Endpoint AJAX Pengecekan Saldo Real-Time Digiflazz
    Route::get('/digiflazz/balance', function (DigiflazzService $digiflazzService) {
        if (!env('DIGIFLAZZ_USERNAME')) {
            return response()->json([
                'success'   => false,
                'message'   => 'Kredensial Digiflazz belum dikonfigurasi di .env',
                'balance'   => 0,
                'formatted' => 'Dev Off',
            ]);
        }

        $balance = $digiflazzService->checkBalance();

        return response()->json([
            'success'   => true,
            'balance'   => $balance,
            'formatted' => 'Rp ' . number_format($balance, 0, ',', '.'),
        ]);
    })->name('admin.digiflazz.balance');

    // 2. Export Laporan Transaksi CSV
    Route::get('/dashboard/export-csv', function (): StreamedResponse {
        $fileName = 'absolute-store-transactions-' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
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
                'Harga Pokok',
                'Biaya Admin',
                'Total Bayar',
                'Status Pembayaran',
                'Status Pengiriman',
                'Serial Number / Token',
            ]);

            Transaction::with(['productItem.product', 'paymentMethod'])
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
                            $t->amount,
                            $t->fee_amount,
                            $t->total_amount,
                            $t->payment_status,
                            $t->delivery_status,
                            $t->serial_number ?? '-',
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    })->name('dashboard.export');

    // 3. Manajemen Produk & SKU
    Route::get('/products', function () {
        $products = Product::with(['category', 'items'])->latest()->paginate(10);
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    })->name('admin.products.index');

    Route::post('/products', function (Request $request) {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'input_type'  => 'required|in:id_only,id_and_zone,phone_number,meter_number',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $slug = Str::slug($validated['name']);
        if (Product::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::lower(Str::random(4));
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('products', 'public');
        }

        Product::create([
            'category_id' => $validated['category_id'],
            'name'        => $validated['name'],
            'slug'        => $slug,
            'input_type'  => $validated['input_type'],
            'thumbnail'   => $thumbnailPath,
            'is_active'   => true,
        ]);

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
            'name'        => 'required|string|max:255',
            'input_type'  => 'required|in:id_only,id_and_zone,phone_number,meter_number',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'   => 'nullable|boolean',
        ]);

        $data = [
            'category_id' => $validated['category_id'],
            'name'        => $validated['name'],
            'input_type'  => $validated['input_type'],
            'is_active'   => $request->has('is_active'),
        ];

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && !filter_var($product->thumbnail, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        $product->update($data);

        return back()->with('success', 'Data produk berhasil diperbarui!');
    })->name('admin.products.update');

    Route::patch('/products/{id}/toggle', function ($id) {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        return back()->with('success', "Status {$product->name} berhasil diubah.");
    })->name('admin.products.toggle');

    Route::delete('/products/{id}', function ($id) {
        $product = Product::findOrFail($id);

        if ($product->thumbnail && !filter_var($product->thumbnail, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->items()->delete();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    })->name('admin.products.destroy');

    // CRUD SKU Item
    Route::post('/products/{id}/items', function (Request $request, $id) {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'sku_code'       => 'required|string|max:100|unique:product_items,sku_code',
            'original_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
        ]);

        ProductItem::create([
            'product_id'     => $product->id,
            'name'           => $validated['name'],
            'sku_code'       => strtoupper($validated['sku_code']),
            'original_price' => $validated['original_price'],
            'selling_price'  => $validated['selling_price'],
            'is_available'   => true,
        ]);

        return back()->with('success', 'Item SKU baru berhasil ditambahkan!');
    })->name('admin.items.store');

    Route::put('/items/{id}', function (Request $request, $id) {
        $item = ProductItem::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'sku_code'       => 'required|string|max:100|unique:product_items,sku_code,' . $item->id,
            'original_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'is_available'   => 'nullable|boolean',
        ]);

        $item->update([
            'name'           => $validated['name'],
            'sku_code'       => strtoupper($validated['sku_code']),
            'original_price' => $validated['original_price'],
            'selling_price'  => $validated['selling_price'],
            'is_available'   => $request->has('is_available'),
        ]);

        return back()->with('success', "Harga {$item->name} berhasil diperbarui.");
    })->name('admin.items.update');

    Route::delete('/items/{id}', function ($id) {
        $item = ProductItem::findOrFail($id);
        $item->delete();
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
            'name'        => 'required|string|max:255',
            'fee_flat'    => 'required|numeric|min:0',
            'fee_percent' => 'required|numeric|min:0|max:100',
            'is_active'   => 'nullable|boolean',
        ]);

        $payment->update([
            'name'        => $validated['name'],
            'fee_flat'    => $validated['fee_flat'],
            'fee_percent' => $validated['fee_percent'],
            'is_active'   => $request->has('is_active'),
        ]);

        return back()->with('success', "Konfigurasi {$payment->name} berhasil diperbarui.");
    })->name('admin.payments.update');

    Route::patch('/payment-methods/{id}/toggle', function ($id) {
        $payment = PaymentMethod::findOrFail($id);
        $payment->update(['is_active' => !$payment->is_active]);

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
            'title'        => 'required|string|max:255',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'target_url'   => 'nullable|url',
        ]);

        $path = $request->file('banner_image')->store('banners', 'public');

        Banner::create([
            'title'      => $validated['title'],
            'image_url'  => $path,
            'target_url' => $validated['target_url'] ?? null,
            'is_active'  => true,
        ]);

        return back()->with('success', 'Banner promo baru berhasil diunggah!');
    })->name('admin.banners.store');

    Route::patch('/promotions/banners/{id}/toggle', function ($id) {
        $banner = Banner::findOrFail($id);
        $banner->update(['is_active' => !$banner->is_active]);
        return back()->with('success', "Status banner {$banner->title} berhasil diubah.");
    })->name('admin.banners.toggle');

    Route::delete('/promotions/banners/{id}', function ($id) {
        $banner = Banner::findOrFail($id);

        if (!filter_var($banner->image_url, FILTER_VALIDATE_URL)) {
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
            'content'   => $validated['content'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Teks pengumuman berjalan berhasil disimpan!');
    })->name('admin.announcements.store');

    Route::patch('/promotions/announcements/{id}/toggle', function ($id) {
        $announcement = Announcement::findOrFail($id);
        $announcement->update(['is_active' => !$announcement->is_active]);
        return back()->with('success', 'Status pengumuman berhasil diubah.');
    })->name('admin.announcements.toggle');

    Route::delete('/promotions/announcements/{id}', function ($id) {
        Announcement::findOrFail($id)->delete();
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    })->name('admin.announcements.destroy');
});
