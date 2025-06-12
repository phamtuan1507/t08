<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,processing,shipped,delivered,cancelled']);
        try {
            $order->update(['status' => $request->status]);
            return redirect()->route('admin.orders.show', $order)->with('success', 'Cập nhật trạng thái thành công.');
        } catch (\Exception $e) {
            Log::error('Error updating order status', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể cập nhật trạng thái.');
        }
    }

    public function destroy(Order $order)
    {
        try {
            $order->items()->delete();
            $order->delete();
            return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa.');
        } catch (\Exception $e) {
            Log::error('Error deleting order', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.orders.index')->with('error', 'Không thể xóa đơn hàng.');
        }
    }
}
