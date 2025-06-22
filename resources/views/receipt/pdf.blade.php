<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .customer-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        th { background-color: #f1f5f9; }
        .summary { margin-top: 20px; text-align: right; }
        .total { font-weight: bold; font-size: 18px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RECEIPT</h1>
        <p>Date: {{ date('F j, Y') }}</p>
    </div>

    @if(isset($customerName) && $customerName)
    <div class="customer-info">
        <strong>Customer:</strong> {{ $customerName }}
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th><th>Item</th><th>Qty</th><th>Price</th><th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
                @if(!empty($item['name']))
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>${{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Subtotal:</strong> ${{ number_format($subtotal, 2) }}</p>
        <p><strong>Tax (10%):</strong> ${{ number_format($tax, 2) }}</p>
        <p><strong>Discount:</strong> -${{ number_format($discount, 2) }}</p>
        <p class="total"><strong>Total:</strong> ${{ number_format($total, 2) }}</p>
    </div>
    
    <p style="margin-top: 40px; text-align: center; font-style: italic;">Thank you for your business!</p>
</body>
</html>
