<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminCartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('user')->paginate(10);
        return view('admin.carts.index', compact('carts'));
    }

    public function show(Cart $cart)
    {
        $cart->load(['user', 'items.product']);
        return view('admin.carts.show', compact('cart'));
    }

    public function destroy(Cart $cart)
    {
        try {
            $cart->items()->delete();
            $cart->delete();
            return redirect()->route('admin.carts.index')->with('success', 'Giỏ hàng đã được xóa.');
        } catch (\Exception $e) {
            Log::error('Error deleting cart', ['cart_id' => $cart->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể xóa giỏ hàng.');
        }
    }

    public function destroyItem(CartItem $cartItem)
    {
        try {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
        } catch (\Exception $e) {
            Log::error('Error deleting cart item', ['cart_item_id' => $cartItem->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể xóa sản phẩm.');
        }
    }
}
