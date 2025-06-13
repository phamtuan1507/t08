<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $items = $cart->items()->with('product')->get();
        $cartCount = $cart->items()->sum('quantity');
        $categories = \App\Models\Category::all();

        // Tính tổng tiền trước giảm giá
        $subtotal = $items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Áp dụng mã giảm giá (nếu có)
        $discountCodeId = session('discount_code_id');
        $discountCode = $discountCodeId ? DiscountCode::find($discountCodeId) : null;
        $discountAmount = 0;
        $total = $subtotal;

        if ($discountCode) {
            $discountAmount = ($discountCode->discount_percentage / 100) * $subtotal;
            $total -= $discountAmount;
        }

        Log::info('Rendering checkout index', [
            'view' => 'user.checkout.index',
            'items_count' => $items->count(),
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'total' => $total,
        ]);

        return view('user.checkout.index', compact('items', 'cartCount', 'categories', 'subtotal', 'discountAmount', 'total', 'discountCode'));
    }

    public function applyDiscount(Request $request)
    {
        $discountCode = DiscountCode::where('code', $request->discount_code)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->first();
        if ($discountCode) {
            session(['applied_discount_code' => $request->discount_code]);
            return redirect()->back()->with('success', 'Áp dụng mã giảm giá thành công!');
        }
        return redirect()->back()->with('error', 'Mã giảm giá không hợp lệ.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'payment_method' => 'required|in:cash_on_delivery,bank_transfer',
            'discount_code' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $cart = Cart::where('user_id', Auth::id())->first();
            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->back()->with('error', 'Giỏ hàng trống.');
            }

            $subtotal = $cart->items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
            $discountAmount = 0;
            $discountCode = null;

            $appliedDiscountCode = $request->discount_code ?: session('applied_discount_code');
            if ($appliedDiscountCode) {
                $discountCode = DiscountCode::where('code', $appliedDiscountCode)
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('expires_at')
                            ->orWhere('expires_at', '>=', now());
                    })
                    ->first();
                if ($discountCode) {
                    $discountAmount = $subtotal * ($discountCode->discount_percentage / 100);
                    Log::info('Discount applied', [
                        'code' => $discountCode->code,
                        'percentage' => $discountCode->discount_percentage,
                        'discount_amount' => $discountAmount,
                        'discount_code_id' => $discountCode->id,
                    ]);
                } else {
                    Log::warning('Invalid discount code', ['code' => $appliedDiscountCode]);
                }
            }

            $total = $subtotal - $discountAmount;

            $order = Order::create([
                'user_id' => Auth::id(),
                'address' => $request->address,
                'payment_method' => $request->payment_method,
                'total' => $total,
                'discount_code_id' => $discountCode ? $discountCode->id : null,
                'status' => 'pending',
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            $user = User::find(Auth::id());
            $user->points += $total;
            $user->save();

            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            Log::info('Order created', [
                'order_id' => $order->id,
                'total' => $total,
                'discount_code_id' => $discountCode ? $discountCode->id : null,
            ]);

            return redirect()->route('checkout.success', $order)->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $order->load('orderItems'); // Tải quan hệ orderItems
        return view('user.checkout.success', compact('order'));
    }
}
