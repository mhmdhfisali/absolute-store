<?php

namespace App\Livewire;

use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Livewire\Component;

class OrderForm extends Component
{
    public Product $product;
    public $userId = '';
    public $zoneId = '';
    public $selectedItemId = null;
    public $selectedPaymentId = null;
    public $contactNumber = '';

    public function mount(Product $product)
    {
        $this->product = $product->load('items');
        $this->selectedPaymentId = PaymentMethod::where('is_active', true)->first()?->id;
    }

    public function selectItem($itemId)
    {
        $this->selectedItemId = $itemId;
    }

    public function selectPayment($paymentId)
    {
        $this->selectedPaymentId = $paymentId;
    }

    public function calculateTotal()
    {
        $item = $this->product->items->firstWhere('id', $this->selectedItemId);
        $payment = PaymentMethod::find($this->selectedPaymentId);

        if (!$item) return 0;
        $fee = $payment ? ($payment->fee_flat + ($item->selling_price * ($payment->fee_percent / 100))) : 0;
        return $item->selling_price + $fee;
    }

    public function checkout()
    {
        $this->validate([
            'userId' => 'required|string|min:3',
            'selectedItemId' => 'required|exists:product_items,id',
            'selectedPaymentId' => 'required|exists:payment_methods,id',
            'contactNumber' => 'required|string|min:10',
        ]);

        $item = $this->product->items->firstWhere('id', $this->selectedItemId);
        $payment = PaymentMethod::find($this->selectedPaymentId);
        $fee = $payment ? ($payment->fee_flat + ($item->selling_price * ($payment->fee_percent / 100))) : 0;
        $total = $item->selling_price + $fee;

        $trx = Transaction::create([
            'invoice_number' => 'AS-' . strtoupper(Str::random(10)),
            'product_item_id' => $item->id,
            'payment_method_id' => $payment->id,
            'target_account' => $this->userId,
            'target_zone' => $this->zoneId,
            'contact_email_or_phone' => $this->contactNumber,
            'amount' => $item->selling_price,
            'fee_amount' => $fee,
            'total_amount' => $total,
            'payment_status' => 'unpaid',
            'delivery_status' => 'pending',
        ]);

        return redirect()->route('order.invoice', ['invoice' => $trx->invoice_number]);
    }

    public function render()
    {
        return view('livewire.order-form', [
            'product' => $this->product,
            'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
            'totalPay' => $this->calculateTotal(),
        ]);
    }
}
