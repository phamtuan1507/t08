<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserProductController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:user']);
    // }

    // public function index(Request $request)
    // {
    //     // $query = Product::query();

    //     // if ($request->has('search') && $request->search) {
    //     //     $search = $request->search;
    //     //     $query->where(function ($q) use ($search) {
    //     //         $q->where('name', 'like', "%{$search}%")
    //     //           ->orWhere('description', 'like', "%{$search}%");
    //     //     });
    //     // }

    //     // if ($request->has('category_id') && $request->category_id !== '') {
    //     //     $query->where('category_id', $request->category_id);
    //     // }

    //     // $products = Product::with('category')->paginate(12);
    //     // $cart = Auth::check() ? Cart::firstOrCreate(['user_id' => Auth::id()]) : null;
    //     // $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
    //     // $categories = Category::all();
    //     // return view('user.product', compact('products', 'cartCount','categories'));

    //     // $query = Product::with('category');
    //     // if ($request->has('category')) {
    //     //     $query->where('category_id', $request->category);
    //     // }
    //     // $products = $query->paginate(12);
    //     // $cartCount = 0;
    //     // if (Auth::check()) {
    //     //     $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
    //     //     $cartCount = $cart->items()->sum('quantity');
    //     // }
    //     // $categories = Category::all();
    //     // Log::info('Rendering product list', ['view' => 'user.products']);

    //     // return view('user.products', compact('products', 'cartCount', 'categories'));

    //     $query = Product::with('category');

    //     // Apply search filter
    //     if ($request->has('query') && $request->query) {
    //         $query->where('name', 'like', '%' . $request->query . '%');
    //     }

    //     // Apply category filter
    //     if ($request->has('category') && $request->category) {
    //         $query->where('category_id', $request->category);
    //     }

    //     // Apply sorting
    //     if ($request->has('sort') && $request->sort) {
    //         if ($request->sort === 'price_asc') {
    //             $query->orderBy('price', 'asc');
    //         } elseif ($request->sort === 'price_desc') {
    //             $query->orderBy('price', 'desc');
    //         }
    //     }

    //     $products = $query->paginate(12);

    //     // Calculate cart count
    //     $cartCount = 0;
    //     if (Auth::check()) {
    //         $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
    //         $cartCount = $cart->items()->sum('quantity');
    //     }

    //     $categories = Category::all();

    //     Log::info('Rendering product list', ['view' => 'user.products', 'query' => $request->all()]);

    //     return view('user.products', compact('products', 'cartCount', 'categories'));

    //     // return view('user.products', compact('products', 'cartCount', 'categories'));
    // }

    // public function show(Product $product)
    // {
    //     // $product->load('category');
    //     // $relatedProducts = Product::where('category_id', $product->category_id)
    //     //     ->where('id', '!=', $product->id)
    //     //     ->take(4)
    //     //     ->get();
    //     // $cartCount = Auth::check() ? Cart::firstOrCreate(['user_id' => Auth::id()])->items()->sum('quantity') : 0;
    //     // $categories = Category::all();
    //     // return view('products.show', compact('product', 'relatedProducts', 'cartCount','categories'));
    //     // $product->load('category');
    //     // $relatedProducts = Product::where('category_id', $product->category_id)
    //     //     ->where('id', '!=', $product->id)
    //     //     ->take(4)
    //     //     ->get();
    //     // $cartCount = 0;
    //     // if (Auth::check()) {
    //     //     $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
    //     //     $cartCount = $cart->items()->sum('quantity');
    //     // }
    //     // $categories = Category::all();
    //     // return view('products.show', compact('product', 'relatedProducts', 'cartCount', 'categories'));
    //     try {
    //         $product->load('category');
    //         $relatedProducts = Product::where('category_id', $product->category_id)
    //             ->where('id', '!=', $product->id)
    //             ->take(4)
    //             ->get();
    //         $cartCount = Auth::check() ? Cart::firstOrCreate(['user_id' => Auth::id()])->items()->sum('quantity') : 0;
    //         $categories = Category::all();
    //         Log::info('Rendering product detail view', ['product_id' => $product->id, 'view' => 'products.show']);
    //         return view('products.show', compact('product', 'relatedProducts', 'cartCount', 'categories'));
    //     } catch (\Exception $e) {
    //         Log::error('Error rendering product detail', ['error' => $e->getMessage(), 'product_id' => $product->id]);
    //         abort(404, 'Product not found');
    //     }
    // }

    // public function search(Request $request)
    // {
    //     $queryString = $request->input('query');
    //     $query = Product::query();

    //     if ($queryString) {
    //         $query->where('name', 'like', '%' . $queryString . '%');
    //     }

    //     if ($request->has('category')) {
    //         $query->where('category_id', $request->input('category'));
    //     }

    //     $products = $query->with('category')->paginate(9)->appends(['query' => $queryString, 'category' => $request->input('category')]);
    //     $cartCount = 0;
    //     $categories = Category::all();

    //     if (Auth::check()) {
    //         $cart = Cart::where('user_id', Auth::id())->first();
    //         $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
    //     }

    //     Log::info('Rendering product search', ['view' => 'user.products', 'query' => $queryString]);
    //     return view('user.products', compact('products', 'cartCount', 'categories'));
    // }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'reviews']);

        // Apply search filter
        if ($request->has('query') && $request->query) {
            $query->where('name', 'like', '%' . $request->query . '%');
        }

        // Apply category filter
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Apply sorting
        if ($request->has('sort') && $request->sort) {
            if ($request->sort === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'price_desc') {
                $query->orderBy('price', 'desc');
            }
        }

        // Lọc khoảng giá
        $priceMin = $request->input('price_min', 0);
        $priceMax = $request->input('price_max', 1000);
        $query->whereBetween('price', [$priceMin, $priceMax]);

        // Lọc đánh giá
        if ($ratings = $request->input('rating', [])) {
            $query->whereHas('reviews', function ($q) use ($ratings) {
                $q->whereIn('rating', $ratings);
            });
        }

        $products = $query->paginate(12);

        // Calculate cart count
        $cartCount = 0;
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cartCount = $cart->items()->sum('quantity');
        }

        $categories = Category::all();

        Log::info('Rendering product list', ['view' => 'user.products.index', 'query' => $request->all()]);

        return view('user.products.index', compact('products', 'cartCount', 'categories'));
    }

    public function show(Product $product)
    {
        try {
            $product->load(['category', 'reviews.user']);

            $relatedProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->take(4)
                ->get();

            $cartCount = Auth::check() ? Cart::firstOrCreate(['user_id' => Auth::id()])->items()->sum('quantity') : 0;
            $categories = Category::all();

            Log::info('Rendering product detail view', [
                'product_id' => $product->id,
                'view' => 'user.products.show',
            ]);

            return view('user.products.show', compact('product', 'relatedProducts', 'cartCount', 'categories'));
        } catch (\Exception $e) {
            Log::error('Error rendering product detail', [
                'error' => $e->getMessage(),
                'product_id' => $product->id ?? 'unknown',
            ]);
            abort(404, 'Product not found');
        }
    }

    public function search(Request $request)
    {
        $queryString = $request->input('query');
        $query = Product::with(['category', 'reviews']);

        if ($queryString) {
            $query->where('name', 'like', '%' . $queryString . '%');
        }

        if ($request->has('category')) {
            $query->where('category_id', $request->input('category'));
        }

        $products = $query->paginate(9)->appends(['query' => $queryString, 'category' => $request->input('category')]);
        $cartCount = 0;
        $categories = Category::all();

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
        }

        Log::info('Rendering product search', ['view' => 'user.products.index', 'query' => $queryString]);

        return view('user.products.index', compact('products', 'cartCount', 'categories'));
    }

    public function addToCart(Product $product, Request $request)
    {
        // try {
        //     if (!Auth::check()) {
        //         return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
        //     }

        //     $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        //     $cart->items()->updateOrCreate(
        //         ['product_id' => $product->id],
        //         ['quantity' => DB::raw('quantity + 1')]
        //     );

        //     Log::info('Product added to cart', [
        //         'product_id' => $product->id,
        //         'user_id' => Auth::id(),
        //     ]);

        //     return view('user.products.cart-add', compact('product'));
        // } catch (\Exception $e) {
        //     Log::error('Error adding product to cart', [
        //         'error' => $e->getMessage(),
        //         'product_id' => $product->id ?? 'unknown',
        //     ]);
        //     return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
        // }

        try {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
            }

            $quantity = $request->input('quantity', 1);
            $userId = Auth::id();

            DB::beginTransaction();

            // Kiểm tra stock trước khi thêm
            if ($product->stock !== null && $product->stock < $quantity) {
                DB::rollBack();
                return redirect()->back()->with('error', "Sản phẩm {$product->name} không đủ stock. Chỉ còn: {$product->stock}.");
            }

            $cart = Cart::firstOrCreate(['user_id' => $userId]);

            // Sử dụng lockForUpdate để tránh race condition
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

            DB::commit();

            $cartCount = $cart->items()->sum('quantity');

            Log::info('Product added to cart', [
                'product_id' => $product->id,
                'user_id' => $userId,
                'quantity' => $quantity,
            ]);

            // Trả về view chi tiết sản phẩm
            return view('user.products.cart-add', compact('product', 'cartCount'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding product to cart', [
                'error' => $e->getMessage(),
                'product_id' => $product->id ?? 'unknown',
                'user_id' => $userId ?? 'unknown',
            ]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
        }
    }
}
