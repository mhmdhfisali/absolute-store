<?php

namespace App\Livewire;

use App\Models\Transaction;
use Livewire\Component;

class InvoiceTracker extends Component
{
    public string $invoiceNumber;

    public function mount(string $invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function render()
    {
        $transaction = Transaction::where('invoice_number', $this->invoiceNumber)
            ->with(['productItem.product', 'paymentMethod', 'promoCode'])
            ->firstOrFail();

        return view('livewire.invoice-tracker', compact('transaction'));
    }
}
