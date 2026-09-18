<?php

namespace App\Livewire;

use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\PromoCode;
use App\Models\SavedAccount;
use App\Models\Transaction;
use App\Services\DigiflazzService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class OrderForm extends Component
{
    public Product $product;

    public string $userId = '';
    public string $zoneId = '';
    public ?int $selectedItemId = null;
    public ?int $selectedPaymentId = null;
    public string $contactNumber = '';

    // State Validasi Nickname
    public ?string $validatedUsername = null;
    public ?string $usernameError = null;

    // State Kode Promo
    public string $promoInput = '';
    public ?PromoCode $appliedPromo = null;
    public int $discountValue = 0;
    public ?string $promoMessage = null;
    public ?string $promoError = null;

    // State Akun Tersimpan
    public $userSavedAccounts = [];

    public function mount(Product $product)
    {
        $this->product = $product->load('items');
        $this->selectedItemId = $this->product->items->first()?->id;
        $this->selectedPaymentId = PaymentMethod::where('is_active', true)->first()?->id;

        // Ambil akun tersimpan milik user yang sedang login untuk produk ini
        if (Auth::check()) {
            $this->userSavedAccounts = SavedAccount::where('user_id', Auth::id())
                ->where('product_id', $this->product->id)
                ->get();

            if (empty($this->contactNumber)) {
                $this->contactNumber = Auth::user()->email;
            }
        }

        // Tangkap parameter query dari dashboard member jika ada
        if (request()->has('saved_id')) {
            $this->userId = (string) request()->query('saved_id');
            $this->zoneId = (string) request()->query('saved_zone', '');
            $this->checkUsername();
        }
    }

    public function applySavedAccount(int $savedAccountId)
    {
        if (!Auth::check()) {
            return;
        }

        $acc = SavedAccount::where('user_id', Auth::id())->find($savedAccountId);

        if ($acc) {
            $this->userId = $acc->target_account;
            $this->zoneId = $acc->target_zone ?? '';
            $this->checkUsername();
        }
    }

    public function updatedUserId()
    {
        $this->checkUsername();
    }

    public function updatedZoneId()
    {
        $this->checkUsername();
    }

    public function checkUsername()
    {
        $this->validatedUsername = null;
        $this->usernameError = null;

        $id = trim($this->userId);
        $zone = trim($this->zoneId);

        if (empty($id)) {
            return;
        }

        if ($this->product->input_type === 'id_and_zone' && empty($zone)) {
            return;
        }

        $digiflazz = app(DigiflazzService::class);
        $res = $digiflazz->checkAccount($this->product->slug, $id, $zone);

        if ($res['status'] && !empty($res['username'])) {
            $this->validatedUsername = $res['username'];
            $this->usernameError = null;
        } else {
            $this->validatedUsername = null;
            $this->usernameError = $res['message'] ?? 'ID Akun tidak ditemukan.';
        }
    }

    public function selectItem(int $id)
    {
        $this->selectedItemId = $id;
        $this->recalculateDiscount();
    }

    public function selectPayment(int $id)
    {
        $this->selectedPaymentId = $id;
    }

    public function applyPromo()
    {
        $this->promoMessage = null;
        $this->promoError = null;

        $code = strtoupper(trim($this->promoInput));

        if (empty($code)) {
            $this->promoError = 'Masukkan kode promo terlebih dahulu.';
            return;
        }

        $promo = PromoCode::where('code', $code)->where('is_active', true)->first();

        if (!$promo) {
            $this->promoError = 'Kode promo tidak valid atau tidak ditemukan.';
            $this->resetPromo();
            return;
        }

        if ($promo->valid_until && now()->gt($promo->valid_until)) {
            $this->promoError = 'Masa berlaku kode promo ini sudah habis.';
            $this->resetPromo();
            return;
        }

        if ($promo->usage_limit !== null && $promo->used_count >= $promo->usage_limit) {
            $this->promoError = 'Kuota penggunaan kode promo ini sudah habis.';
            $this->resetPromo();
            return;
        }

        $item = ProductItem::find($this->selectedItemId);
        $subtotal = $this->getItemPrice($item);

        if ($subtotal < $promo->min_transaction) {
            $this->promoError = 'Minimal belanja untuk kupon ini adalah Rp ' . number_format($promo->min_transaction, 0, ',', '.');
            $this->resetPromo();
            return;
        }

        $this->appliedPromo = $promo;
        $this->recalculateDiscount();
        $this->promoMessage = 'Kupon ' . $promo->code . ' berhasil dipasang!';
    }

    public function removePromo()
    {
        $this->resetPromo();
        $this->promoInput = '';
        $this->promoMessage = null;
        $this->promoError = null;
    }

    protected function resetPromo()
    {
        $this->appliedPromo = null;
        $this->discountValue = 0;
    }

    /**
     * Mendapatkan harga sesuai tier (User biasa / Reseller)
     */
    protected function getItemPrice(?ProductItem $item): int
    {
        if (!$item) return 0;

        if (Auth::check() && Auth::user()->isReseller() && !empty($item->reseller_price)) {
            return (int) $item->reseller_price;
        }

        return (int) $item->selling_price;
    }

    protected function recalculateDiscount()
    {
        if (!$this->appliedPromo) {
            $this->discountValue = 0;
            return;
        }

        $item = ProductItem::find($this->selectedItemId);
        if (!$item) {
            $this->resetPromo();
            return;
        }

        $price = $this->getItemPrice($item);

        if ($price < $this->appliedPromo->min_transaction) {
            $this->promoError = 'Nominal item tidak memenuhi syarat minimum kupon.';
            $this->resetPromo();
            return;
        }

        $this->discountValue = (int) round($this->appliedPromo->calculateDiscount($price));
    }

    public function getTotalPayProperty(): int
    {
        $item = ProductItem::find($this->selectedItemId);
        $payment = PaymentMethod::find($this->selectedPaymentId);

        if (!$item || !$payment) {
            return 0;
        }

        $base = $this->getItemPrice($item);
        $fee = $payment->fee_flat + (int) round(($base * $payment->fee_percent) / 100);
        $total = ($base + $fee) - $this->discountValue;

        return max(0, $total);
    }

    /**
     * Checkout Reguler via Payment Gateway / Simulasi
     */
    public function checkout(WhatsAppService $waService)
    {
        $this->validate([
            'userId'            => 'required|string|max:100',
            'zoneId'            => $this->product->input_type === 'id_and_zone' ? 'required|string|max:50' : 'nullable|string|max:50',
            'selectedItemId'    => 'required|exists:product_items,id',
            'selectedPaymentId' => 'required|exists:payment_methods,id',
            'contactNumber'     => 'required|string|min:9|max:100',
        ], [
            'userId.required'        => 'User ID / Nomor Tujuan wajib diisi.',
            'zoneId.required'        => 'Zone ID wajib diisi untuk game ini.',
            'contactNumber.required' => 'Nomor WhatsApp atau Email wajib diisi untuk bukti transaksi.',
        ]);

        $item = ProductItem::findOrFail($this->selectedItemId);
        $payment = PaymentMethod::findOrFail($this->selectedPaymentId);

        $basePrice = $this->getItemPrice($item);
        $fee = $payment->fee_flat + (int) round(($basePrice * $payment->fee_percent) / 100);
        $total = max(0, ($basePrice + $fee) - $this->discountValue);

        $invoice = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $trx = Transaction::create([
            'invoice_number'         => $invoice,
            'product_item_id'        => $item->id,
            'payment_method_id'      => $payment->id,
            'promo_code_id'          => $this->appliedPromo?->id,
            'target_account'         => $this->userId,
            'target_zone'            => $this->zoneId ?: null,
            'contact_email_or_phone' => $this->contactNumber,
            'amount'                 => $basePrice,
            'fee_amount'             => $fee,
            'discount_amount'        => $this->discountValue,
            'total_amount'           => $total,
            'payment_status'         => 'unpaid',
            'delivery_status'        => 'pending',
            'checkout_source'        => 'direct',
            'provider_response'      => $this->validatedUsername ? ['account_name' => $this->validatedUsername] : null,
        ]);

        if ($this->appliedPromo) {
            $this->appliedPromo->increment('used_count');
        }

        $waService->sendInvoiceCreated($trx);

        return redirect()->route('order.invoice', $invoice);
    }

    /**
     * Checkout Instan Menggunakan Saldo Dompet Akun Member
     */
    public function payWithBalance(WhatsAppService $waService, DigiflazzService $digiflazz)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'userId'         => 'required|string|max:100',
            'zoneId'         => $this->product->input_type === 'id_and_zone' ? 'required|string|max:50' : 'nullable|string|max:50',
            'selectedItemId' => 'required|exists:product_items,id',
        ], [
            'userId.required' => 'User ID / Nomor Tujuan wajib diisi.',
            'zoneId.required' => 'Zone ID wajib diisi untuk game ini.',
        ]);

        $user = Auth::user();
        $item = ProductItem::findOrFail($this->selectedItemId);
        $price = $this->getItemPrice($item);
        $finalAmount = max(0, $price - $this->discountValue);

        if ($user->balance < $finalAmount) {
            $this->addError('balance', 'Saldo dompet Anda tidak cukup (Sisa: Rp ' . number_format($user->balance, 0, ',', '.') . ').');
            return;
        }

        // Potong Saldo Akun Member
        $user->decrement('balance', $finalAmount);

        $invoice = 'INV-BAL-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $trx = Transaction::create([
            'invoice_number'         => $invoice,
            'product_item_id'        => $item->id,
            'payment_method_id'      => PaymentMethod::where('is_active', true)->first()?->id ?? 1,
            'promo_code_id'          => $this->appliedPromo?->id,
            'target_account'         => $this->userId,
            'target_zone'            => $this->zoneId ?: null,
            'contact_email_or_phone' => $user->email,
            'amount'                 => $price,
            'fee_amount'             => 0,
            'discount_amount'        => $this->discountValue,
            'total_amount'           => $finalAmount,
            'payment_status'         => 'paid',
            'delivery_status'        => 'processing',
            'checkout_source'        => 'balance',
            'provider_response'      => $this->validatedUsername ? ['account_name' => $this->validatedUsername] : null,
        ]);

        if ($this->appliedPromo) {
            $this->appliedPromo->increment('used_count');
        }

        // Teruskan otomatis ke Digiflazz
        if (env('DIGIFLAZZ_USERNAME')) {
            $digiflazz->processTransaction($trx);
        } else {
            $trx->update([
                'delivery_status' => 'success',
                'serial_number'   => 'AS-BAL-' . strtoupper(Str::random(12)),
            ]);
            $waService->sendPaymentSuccess($trx->fresh());
        }

        return redirect()->route('order.invoice', $invoice);
    }

    public function render()
    {
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        return view('livewire.order-form', compact('paymentMethods'));
    }
}
