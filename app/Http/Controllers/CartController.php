<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $items = $cart->items()->with('product')->get();
            $cartCount = $cart->items()->sum('quantity');
        } else {
            $sessionCart = session()->get('cart', []);
            $items = Product::whereIn('id', array_keys($sessionCart))
                ->get()
                ->map(function ($product) use ($sessionCart) {
                    return (object) [
                        'product' => $product,
                        'quantity' => $sessionCart[$product->id],
                    ];
                });
            $cartCount = array_sum($sessionCart);
        }
        return view('cart.index', compact('items', 'cartCount'));
    }

    public function add(Request $request, Product $product)
    {
        // try {
        //     $quantity = $request->input('quantity', 1);
        //     if ($product->stock !== null && $product->stock < $quantity) {
        //         return redirect()->back()->with('error', "Sản phẩm {$product->name} không đủ stock. Chỉ còn: {$product->stock}.");
        //     }

        //     $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        //     $cartItem = $cart->items()->where('product_id', $product->id)->first();

        //     if ($cartItem) {
        //         $newQuantity = $cartItem->quantity + $quantity;
        //         if ($product->stock !== null && $product->stock < $newQuantity) {
        //             return redirect()->back()->with('error', "Sản phẩm {$product->name} không đủ stock. Chỉ còn: {$product->stock}.");
        //         }
        //         $cartItem->update(['quantity' => $newQuantity]);
        //     } else {
        //         CartItem::create([
        //             'cart_id' => $cart->id,
        //             'product_id' => $product->id,
        //             'quantity' => $quantity,
        //         ]);
        //     }

        //     Log::info('Item added to cart', ['product_id' => $product->id, 'quantity' => $quantity]);
        //     return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
        // } catch (\Exception $e) {
        //     Log::error('Error adding to cart', ['error' => $e->getMessage()]);
        //     return redirect()->back()->with('error', 'Đã có lỗi xảy ra. Vui lòng thử lại.');
        // }

        try {
            $quantity = $request->input('quantity', 1);
            $userId = Auth::id();

            // Bắt đầu transaction
            DB::beginTransaction();

            // Kiểm tra stock trước khi thêm
            if ($product->stock !== null && $product->stock < $quantity) {
                DB::rollBack();
                return redirect()->back()->with('error', "Sản phẩm {$product->name} không đủ stock. Chỉ còn: {$product->stock}.");
            }

            // Lấy hoặc tạo giỏ hàng
            $cart = Cart::firstOrCreate(['user_id' => $userId]);

            // Kiểm tra và cập nhật hoặc thêm mới
            $cartItem = $cart->items()->where('product_id', $product->id)->lockForUpdate()->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $quantity;
                if ($product->stock !== null && $product->stock < $newQuantity) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Sản phẩm {$product->name} không đủ stock. Chỉ còn: {$product->stock}.");
                }
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }

            // Giảm stock nếu cần
            if ($product->stock !== null) {
                $product->decrement('stock', $quantity);
            }

            // Commit transaction
            DB::commit();

            Log::info('Item added to cart', [
                'user_id' => $userId,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);

            return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding to cart', ['error' => $e->getMessage(), 'user_id' => Auth::id()]);
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra. Vui lòng thử lại.');
        }

        // try {
        //     $quantity = $request->input('quantity', 1);
        //     $userId = Auth::id();

        //     DB::beginTransaction();

        //     // Kiểm tra stock trước khi thêm
        //     if ($product->stock !== null && $product->stock < $quantity) {
        //         DB::rollBack();
        //         return response()->json([
        //             'error' => "Sản phẩm {$product->name} không đủ stock. Chỉ còn: {$product->stock}.",
        //         ], 400);
        //     }

        //     $cart = Cart::firstOrCreate(['user_id' => $userId]);

        //     // Kiểm tra và khóa bản ghi để tránh race condition
        //     $cartItem = $cart->items()->where('product_id', $product->id)->lockForUpdate()->first();

        //     if ($cartItem) {
        //         $newQuantity = $cartItem->quantity + $quantity;
        //         if ($product->stock !== null && $product->stock < $newQuantity) {
        //             DB::rollBack();
        //             return response()->json([
        //                 'error' => "Sản phẩm {$product->name} không đủ stock. Chỉ còn: {$product->stock}.",
        //             ], 400);
        //         }
        //         $cartItem->update(['quantity' => $newQuantity]);
        //     } else {
        //         CartItem::create([
        //             'cart_id' => $cart->id,
        //             'product_id' => $product->id,
        //             'quantity' => $quantity,
        //         ]);
        //     }

        //     // Giảm stock nếu cần
        //     if ($product->stock !== null) {
        //         $product->decrement('stock', $quantity);
        //     }

        //     DB::commit();

        //     $cartCount = Cart::where('user_id', $userId)->with('items')->first()->items->sum('quantity');

        //     Log::info('Item added to cart', [
        //         'user_id' => $userId,
        //         'product_id' => $product->id,
        //         'quantity' => $quantity,
        //     ]);

        //     // return response()->json([
        //     //     'success' => 'Sản phẩm đã được thêm vào giỏ hàng.',
        //     //     'cart_count' => $cartCount,
        //     // ]);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     Log::error('Error adding to cart', ['error' => $e->getMessage(), 'user_id' => $userId]);
        //     return response()->json([
        //         'error' => 'Đã có lỗi xảy ra. Vui lòng thử lại.',
        //     ], 500);
        // }
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $quantity = $request->input('quantity');
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        if ($cartItem->product->stock !== null && $cartItem->product->stock < $quantity) {
            return redirect()->back()->with('error', "Sản phẩm {$cartItem->product->name} không đủ stock. Chỉ còn: {$cartItem->product->stock}.");
        }

        $cartItem->update(['quantity' => $quantity]);
        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được cập nhật.');
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }
        $cartItem->delete();
        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }
}
