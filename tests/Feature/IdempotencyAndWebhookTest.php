<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class IdempotencyAndWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected ProductItem $productItem;

    protected PaymentMethod $paymentMethod;

    protected string $privateKey = 'test_tripay_secret_key_12345';

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.tripay.private_key', $this->privateKey);

        $this->user = User::factory()->create([
            'balance' => 50000,
        ]);

        $category = Category::create([
            'name' => 'Games',
            'slug' => 'games',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Mobile Legends',
            'slug' => 'mobile-legends',
            'is_active' => true,
        ]);

        $this->productItem = ProductItem::create([
            'product_id' => $product->id,
            'name' => '86 Diamonds',
            'sku_code' => 'ML-86',
            'selling_price' => 20000,
            'original_price' => 18000,
            'is_available' => true,
        ]);

        $this->paymentMethod = PaymentMethod::create([
            'name' => 'QRIS Realtime',
            'code' => 'QRIS',
            'channel_category' => 'qris',
            'fee_flat' => 750,
            'fee_percent' => 0.7,
            'is_active' => true,
        ]);
    }

    /**
     * Helper to generate valid HMAC SHA256 signature for Tripay callback.
     */
    protected function generateTripaySignature(array $payload): string
    {
        return hash_hmac('sha256', json_encode($payload), $this->privateKey);
    }

    public function test_valid_tripay_webhook_processes_order_successfully(): void
    {
        $invoice = 'INV-'.date('Ymd').'-TEST01';

        $trx = Transaction::create([
            'invoice_number' => $invoice,
            'product_item_id' => $this->productItem->id,
            'payment_method_id' => $this->paymentMethod->id,
            'target_account' => '12345678',
            'target_zone' => '1234',
            'contact_email_or_phone' => 'customer@example.com',
            'amount' => 20000,
            'fee_amount' => 750,
            'total_amount' => 20750,
            'payment_status' => 'unpaid',
            'delivery_status' => 'pending',
            'checkout_source' => 'direct',
        ]);

        $payload = [
            'merchant_ref' => $invoice,
            'status' => 'PAID',
            'total_amount' => 20750,
        ];
        $jsonPayload = json_encode($payload);
        $signature = hash_hmac('sha256', $jsonPayload, $this->privateKey);

        $response = $this->call(
            'POST',
            '/api/webhook/tripay',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_CALLBACK_SIGNATURE' => $signature,
            ],
            $jsonPayload
        );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals('paid', $trx->fresh()->payment_status);
    }

    public function test_invalid_signature_webhook_is_rejected_with_403(): void
    {
        $payload = [
            'merchant_ref' => 'INV-FAKE-999',
            'status' => 'PAID',
        ];
        $jsonPayload = json_encode($payload);

        $response = $this->call(
            'POST',
            '/api/webhook/tripay',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_CALLBACK_SIGNATURE' => 'invalid_tampered_hmac_signature',
            ],
            $jsonPayload
        );

        $response->assertStatus(403);
    }

    public function test_duplicate_webhook_callbacks_are_idempotent(): void
    {
        $invoice = 'INV-'.date('Ymd').'-IDEMP1';

        $trx = Transaction::create([
            'invoice_number' => $invoice,
            'product_item_id' => $this->productItem->id,
            'payment_method_id' => $this->paymentMethod->id,
            'target_account' => '12345678',
            'target_zone' => '1234',
            'contact_email_or_phone' => 'customer@example.com',
            'amount' => 20000,
            'fee_amount' => 750,
            'total_amount' => 20750,
            'payment_status' => 'unpaid',
            'delivery_status' => 'pending',
            'checkout_source' => 'direct',
        ]);

        $payload = [
            'merchant_ref' => $invoice,
            'status' => 'PAID',
            'total_amount' => 20750,
        ];
        $jsonPayload = json_encode($payload);
        $signature = hash_hmac('sha256', $jsonPayload, $this->privateKey);

        // First callback
        $res1 = $this->call(
            'POST',
            '/api/webhook/tripay',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_CALLBACK_SIGNATURE' => $signature,
            ],
            $jsonPayload
        );
        $res1->assertStatus(200);
        $this->assertEquals('paid', $trx->fresh()->payment_status);

        // Second duplicate callback (e.g. gateway retry)
        $res2 = $this->call(
            'POST',
            '/api/webhook/tripay',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_CALLBACK_SIGNATURE' => $signature,
            ],
            $jsonPayload
        );
        $res2->assertStatus(200);

        // Status remains paid, no duplicate side-effects
        $this->assertEquals('paid', $trx->fresh()->payment_status);
    }

    public function test_webhook_deposit_credits_user_wallet_exactly_once(): void
    {
        $depoNumber = 'DEP-'.date('Ymd').'-IDEMPD';
        $initialBalance = $this->user->balance;
        $depositAmount = 100000;

        $depo = Deposit::create([
            'deposit_number' => $depoNumber,
            'user_id' => $this->user->id,
            'payment_method_id' => $this->paymentMethod->id,
            'amount' => $depositAmount,
            'fee_amount' => 1000,
            'total_amount' => 101000,
            'status' => 'unpaid',
        ]);

        $payload = [
            'merchant_ref' => $depoNumber,
            'status' => 'PAID',
            'total_amount' => 101000,
        ];
        $jsonPayload = json_encode($payload);
        $signature = hash_hmac('sha256', $jsonPayload, $this->privateKey);

        // First call
        $res1 = $this->call(
            'POST',
            '/api/webhook/tripay',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_CALLBACK_SIGNATURE' => $signature,
            ],
            $jsonPayload
        );
        $res1->assertStatus(200);

        $this->assertEquals($initialBalance + $depositAmount, (float) $this->user->fresh()->balance);
        $this->assertEquals(1, WalletTransaction::where('reference_id', $depoNumber)->count());

        // Second call with same invoice
        $res2 = $this->call(
            'POST',
            '/api/webhook/tripay',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_CALLBACK_SIGNATURE' => $signature,
            ],
            $jsonPayload
        );
        $res2->assertStatus(200);

        // Balance MUST NOT increase twice
        $this->assertEquals($initialBalance + $depositAmount, (float) $this->user->fresh()->balance);
        $this->assertEquals(1, WalletTransaction::where('reference_id', $depoNumber)->count());
    }

    public function test_concurrent_webhook_requests_are_locked_by_cache_lock(): void
    {
        $invoice = 'INV-LOCKED-001';

        // Acquire lock beforehand simulating concurrent worker processing
        $lock = Cache::lock("tripay_webhook_{$invoice}", 15);
        $lock->get();

        $payload = [
            'merchant_ref' => $invoice,
            'status' => 'PAID',
        ];
        $jsonPayload = json_encode($payload);
        $signature = hash_hmac('sha256', $jsonPayload, $this->privateKey);

        $response = $this->call(
            'POST',
            '/api/webhook/tripay',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_CALLBACK_SIGNATURE' => $signature,
            ],
            $jsonPayload
        );

        $lock->release();

        $response->assertStatus(429);
        $response->assertJson(['success' => false]);
    }
}
