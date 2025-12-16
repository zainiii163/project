<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Order::class);

        $orders = Order::with(['user', 'items.course'])
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('user', 'items.course', 'transaction');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled,refunded',
        ]);

        $order->update($validated);

        return back()->with('success', 'Order status updated successfully!');
    }
}

