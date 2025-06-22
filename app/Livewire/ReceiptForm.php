<?php

namespace App\Livewire;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Receipt;
use App\Models\ReceiptItem;

class ReceiptForm extends Component
{
    public $items = [];
    public $customerName = '';
    public $receiptGenerated = false;
    public $receiptUrl = '';

    public function mount()
    {
        $this->items = [
            ['name' => '', 'quantity' => 1, 'price' => 0],
        ];
    }

    public function addItem()
    {
        $this->items[] = ['name' => '', 'quantity' => 1, 'price' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function calculateSubtotal()
    {
        return collect($this->items)->sum(fn($item) => $item['quantity'] * $item['price']);
    }

    public function calculateTax($subtotal)
    {
        return $subtotal * 0.1;
    }

    public function calculateDiscount($subtotal)
    {
        return $subtotal > 100 ? 10 : 0;
    }

    public function calculateTotal()
    {
        $subtotal = $this->calculateSubtotal();
        $tax = $this->calculateTax($subtotal);
        $discount = $this->calculateDiscount($subtotal);
        return $subtotal + $tax - $discount;
    }

    public function generateReceipt()
    {
        $this->validate([
            'customerName' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $subtotal = $this->calculateSubtotal();
        $tax = $this->calculateTax($subtotal);
        $discount = $this->calculateDiscount($subtotal);
        $total = $subtotal + $tax - $discount;

        // Save to DB
        $receipt = Receipt::create(compact('subtotal', 'tax', 'discount', 'total'));

        foreach ($this->items as $item) {
            if (!empty($item['name'])) {
                $receipt->items()->create($item);
            }
        }

        $data = [
            'customerName' => $this->customerName,
            'items' => $this->items,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('receipt.pdf', $data);
        $filename = 'receipt_' . $receipt->id . '.pdf';
        $path = storage_path('app/public/' . $filename);
        $pdf->save($path);

        $this->receiptGenerated = true;
        $this->receiptUrl = asset('storage/' . $filename);

        session()->flash('success', 'Receipt generated successfully!');
    }

    public function render()
    {
        $subtotal = $this->calculateSubtotal();
        $tax = $this->calculateTax($subtotal);
        $discount = $this->calculateDiscount($subtotal);
        $total = $this->calculateTotal();

        return view('livewire.receipt-form', compact('subtotal', 'tax', 'discount', 'total'));
    }
}
