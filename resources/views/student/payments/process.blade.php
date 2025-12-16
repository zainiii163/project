@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 800px; margin: 40px auto; padding: 20px;">
    <div class="card" style="box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-radius: 8px;">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0;">
            <h2 style="margin: 0; font-size: 24px;">
                <i class="fas fa-credit-card"></i> Complete Payment
            </h2>
        </div>
        <div class="card-body" style="padding: 30px;">
            @if(session('success'))
                <div class="alert alert-success" style="padding: 15px; margin-bottom: 20px; background: #d4edda; color: #155724; border-radius: 5px;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" style="padding: 15px; margin-bottom: 20px; background: #f8d7da; color: #721c24; border-radius: 5px;">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Order Summary -->
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                <h3 style="margin-top: 0; color: #333; font-size: 18px; margin-bottom: 15px;">Order Summary</h3>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: #666;">Order ID:</span>
                    <strong>#{{ substr($order->id, 0, 8) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: #666;">Date:</span>
                    <span>{{ $order->order_date->format('M d, Y h:i A') }}</span>
                </div>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #dee2e6;">
                
                <div style="margin-bottom: 15px;">
                    <strong style="color: #333;">Items:</strong>
                    <ul style="margin: 10px 0; padding-left: 20px;">
                        @foreach($order->items as $item)
                            <li style="margin-bottom: 8px;">
                                <div style="display: flex; justify-content: space-between;">
                                    <span>{{ $item->course->title ?? 'Course' }}</span>
                                    <strong>${{ number_format($item->price, 2) }}</strong>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if($order->coupon_code)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: #28a745;">
                        <span>Discount ({{ $order->coupon_code }}):</span>
                        <strong>-${{ number_format($order->discount_amount, 2) }}</strong>
                    </div>
                @endif

                <hr style="margin: 15px 0; border: none; border-top: 2px solid #667eea;">
                <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: bold; color: #667eea;">
                    <span>Total:</span>
                    <span>${{ number_format($order->total_price, 2) }}</span>
                </div>
            </div>

            <!-- Payment Methods -->
            <form action="{{ route('payments.process', $order) }}" method="POST" id="paymentForm">
                @csrf
                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">Payment Method</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                        <label style="display: flex; align-items: center; padding: 15px; border: 2px solid #dee2e6; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="payment_method" value="credit_card" required style="margin-right: 10px;">
                            <i class="fas fa-credit-card" style="margin-right: 8px; color: #667eea;"></i>
                            <span>Credit Card</span>
                        </label>
                        <label style="display: flex; align-items: center; padding: 15px; border: 2px solid #dee2e6; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="payment_method" value="paypal" required style="margin-right: 10px;">
                            <i class="fab fa-paypal" style="margin-right: 8px; color: #0070ba;"></i>
                            <span>PayPal</span>
                        </label>
                        <label style="display: flex; align-items: center; padding: 15px; border: 2px solid #dee2e6; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="payment_method" value="wallet" required style="margin-right: 10px;">
                            <i class="fas fa-wallet" style="margin-right: 8px; color: #28a745;"></i>
                            <span>Wallet</span>
                        </label>
                    </div>
                </div>

                <!-- Wallet Balance Display -->
                @if(auth()->user()->wallet)
                    <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #007bff;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #004085; font-weight: 600;">
                                <i class="fas fa-wallet"></i> Wallet Balance:
                            </span>
                            <strong style="color: #004085; font-size: 18px;">
                                ${{ number_format(auth()->user()->wallet->balance, 2) }}
                            </strong>
                        </div>
                    </div>
                @endif

                <div style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 8px; color: white; font-size: 16px; font-weight: 600; cursor: pointer; transition: transform 0.2s;">
                        <i class="fas fa-lock"></i> Pay ${{ number_format($order->total_price, 2) }}
                    </button>
                    <a href="{{ route('student.courses.index') }}" class="btn btn-secondary" style="padding: 15px 30px; background: #6c757d; border: none; border-radius: 8px; color: white; text-decoration: none; display: inline-flex; align-items: center;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    label:hover {
        border-color: #667eea !important;
        background: #f8f9ff;
    }
    input[type="radio"]:checked + * {
        color: #667eea;
    }
    label:has(input[type="radio"]:checked) {
        border-color: #667eea !important;
        background: #f8f9ff;
    }
    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
</style>
@endsection

