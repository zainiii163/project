<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            color: #333;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .invoice-title {
            font-size: 32px;
            font-weight: bold;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-body {
            margin-bottom: 40px;
        }
        .billing-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .billing-section {
            width: 48%;
        }
        .billing-section h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }
        .billing-section p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table th {
            background: #f5f5f5;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #333;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        .text-right {
            text-align: right;
        }
        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #333;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        .total-row {
            display: flex;
            justify-content: flex-end;
            margin: 5px 0;
        }
        .total-label {
            width: 200px;
            text-align: right;
            padding-right: 20px;
        }
        .total-amount {
            width: 150px;
            text-align: right;
            font-weight: bold;
        }
        .grand-total {
            font-size: 20px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #333;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <div>
            <div class="invoice-title">INVOICE</div>
            <div style="margin-top: 10px; color: #666;">SmartLearn LMS</div>
        </div>
        <div class="invoice-info">
            <div><strong>Invoice #:</strong> {{ $order->id }}</div>
            <div><strong>Date:</strong> {{ $order->order_date->format('F d, Y') }}</div>
            <div><strong>Status:</strong> {{ ucfirst($order->status) }}</div>
        </div>
    </div>

    <div class="invoice-body">
        <div class="billing-info">
            <div class="billing-section">
                <h3>Bill To:</h3>
                <p><strong>{{ $order->user->name }}</strong></p>
                <p>{{ $order->user->email }}</p>
            </div>
            <div class="billing-section">
                <h3>Payment Information:</h3>
                @if($order->transaction)
                    <p><strong>Payment Method:</strong> {{ $order->transaction->payment_method ?? 'N/A' }}</p>
                    <p><strong>Transaction ID:</strong> {{ $order->transaction->transaction_id ?? 'N/A' }}</p>
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->course->title ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->price, 2) }}</td>
                        <td class="text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            @php
                $subtotal = $order->items->sum(function($item) {
                    return $item->price * $item->quantity;
                });
            @endphp
            
            <div class="total-row">
                <div class="total-label">Subtotal:</div>
                <div class="total-amount">${{ number_format($subtotal, 2) }}</div>
            </div>
            
            @if($order->discount_amount)
                <div class="total-row">
                    <div class="total-label">Discount:</div>
                    <div class="total-amount" style="color: #28a745;">-${{ number_format($order->discount_amount, 2) }}</div>
                </div>
            @endif
            
            <div class="total-row grand-total">
                <div class="total-label">Total:</div>
                <div class="total-amount">${{ number_format($order->total_price, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="invoice-footer">
        <p>Thank you for your purchase!</p>
        <p>This is a computer-generated invoice and does not require a signature.</p>
    </div>
</body>
</html>

