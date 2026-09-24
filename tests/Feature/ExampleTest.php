<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\AffiliateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_locale_can_be_switched_to_english(): void
    {
        $response = $this->get('/switch-locale/en');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');
        $response->assertCookie('locale', 'en');
    }

    public function test_order_page_renders_successfully(): void
    {
        $category = Category::create(['name' => 'Games', 'slug' => 'games']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Mobile Legends',
            'slug' => 'mobile-legends',
            'input_type' => 'id_and_zone',
            'is_active' => true,
        ]);
        ProductItem::create([
            'product_id' => $product->id,
            'name' => '86 Diamonds',
            'sku_code' => 'ML86',
            'original_price' => 19000,
            'selling_price' => 20000,
            'is_available' => true,
        ]);
        PaymentMethod::create([
            'name' => 'QRIS',
            'code' => 'QRIS',
            'channel_category' => 'qris',
            'fee_flat' => 750,
            'fee_percent' => 0.7,
            'is_active' => true,
        ]);

        $response = $this->get('/order/mobile-legends');

        $response->assertStatus(200);
        $response->assertSee('Mobile Legends');
    }

    public function test_referral_link_sets_cookie_and_links_new_user(): void
    {
        $referrer = User::factory()->create([
            'email' => 'upline@absolutestore.com',
            'referral_code' => 'ASUPLINE',
        ]);

        $response = $this->get('/ref/ASUPLINE');
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('ref_code', 'ASUPLINE');
        $response->assertCookie('ref_code', 'ASUPLINE');

        // New user registers
        $regResponse = $this->withCookie('ref_code', 'ASUPLINE')->post('/register', [
            'name' => 'Downline User',
            'email' => 'downline@absolutestore.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $newUser = User::where('email', 'downline@absolutestore.com')->first();
        $this->assertNotNull($newUser);
        $this->assertEquals($referrer->id, $newUser->referred_by_id);
    }

    public function test_affiliate_commission_distribution_double_entry(): void
    {
        $referrer = User::factory()->create(['balance' => 0]);
        $buyer = User::factory()->create([
            'referred_by_id' => $referrer->id,
            'balance' => 100000,
        ]);

        $category = Category::create(['name' => 'Voucher', 'slug' => 'voucher']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Garena Shells',
            'slug' => 'garena-shells',
            'input_type' => 'id_only',
            'is_active' => true,
        ]);
        $item = ProductItem::create([
            'product_id' => $product->id,
            'name' => '100 Shells',
            'sku_code' => 'GS100',
            'original_price' => 50000,
            'selling_price' => 50000,
            'is_available' => true,
        ]);
        $payment = PaymentMethod::create([
            'name' => 'BCA Virtual Account',
            'code' => 'BCAVA',
            'channel_category' => 'virtual_account',
            'fee_flat' => 1000,
            'fee_percent' => 0,
            'is_active' => true,
        ]);

        $trx = Transaction::create([
            'user_id' => $buyer->id,
            'invoice_number' => 'INV-TEST-001',
            'product_item_id' => $item->id,
            'payment_method_id' => $payment->id,
            'target_account' => 'buyer123',
            'contact_email_or_phone' => 'buyer@example.com',
            'amount' => 50000,
            'fee_amount' => 1000,
            'discount_amount' => 0,
            'total_amount' => 51000,
            'payment_status' => 'paid',
            'delivery_status' => 'pending',
            'checkout_source' => 'direct',
        ]);

        $earning = AffiliateService::processCommission($trx);

        $this->assertNotNull($earning);
        $this->assertEquals(255.00, (float) $earning->commission_amount); // 0.5% dari 51000
        $this->assertEquals(255.00, (float) $referrer->fresh()->balance);

        // Verify Double-Entry Ledger
        $ledger = WalletTransaction::where('reference_id', 'COMM-INV-TEST-001')->first();
        $this->assertNotNull($ledger);
        $this->assertEquals('credit', $ledger->type);
        $this->assertEquals(255.00, (float) $ledger->amount);
    }

    public function test_user_dashboard_renders_successfully_even_if_referral_code_was_null(): void
    {
        $user = User::factory()->create([
            'email' => 'legacy@example.com',
            'referral_code' => null,
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Wishlist');
        $response->assertSee('Program Afiliasi');
        $this->assertNotNull($user->fresh()->referral_code);
    }
}
