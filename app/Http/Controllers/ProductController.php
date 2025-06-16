<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('min_price') && $request->min_price !== '') {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price !== '') {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('created_at') && $request->created_at) {
            $query->whereDate('created_at', $request->created_at);
        }

        $products = Product::with('category')->paginate(10);
        Log::info('Rendering admin products index', ['view' => 'admin.products.index']);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        Log::info('Rendering admin products create', ['view' => 'admin.products.create']);
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $data = $request->only(['name', 'description', 'price', 'stock', 'category_id']);
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            } else {
                $data['image'] = null; // Đặt giá trị mặc định nếu không upload ảnh chính
            }

            $product = Product::create($data);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $product->images()->create([
                        'image_path' => $image->store('products', 'public'),
                        'is_primary' => false,
                    ]);
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm.');
        } catch (\Exception $e) {
            Log::error('Error creating product', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể thêm sản phẩm.');
        }
    }

    public function show(Product $product)
    {
        $product->load('category', 'images');
        $cartCount = Auth::check() ? Cart::firstOrCreate(['user_id' => Auth::id()])->items()->sum('quantity') : 0;
        return view('admin.products.show', compact('product', 'cartCount'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $data = $request->only(['name', 'description', 'price', 'stock', 'category_id']);
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);

            if ($request->hasFile('images')) {
                // Xóa các ảnh phụ cũ
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $product->images()->delete();

                // Thêm ảnh phụ mới
                foreach ($request->file('images') as $image) {
                    $product->images()->create([
                        'image_path' => $image->store('products', 'public'),
                        'is_primary' => false,
                    ]);
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật.');
        } catch (\Exception $e) {
            Log::error('Error updating product', ['product_id' => $product->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể cập nhật sản phẩm.');
        }
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            $product->images()->delete();
            $product->delete();
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa.');
        } catch (\Exception $e) {
            Log::error('Error deleting product', ['product_id' => $product->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể xóa sản phẩm.');
        }
    }
    public function list(Request $request, Category $category)
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

        Log::info('Rendering product list', ['view' => 'user.categories.products.list', 'query' => $request->all()]);
        // $products = $category->products()->filter(request()->all())->paginate(12); // Example filtering
        return view('user.categories.products.list', compact('category', 'products', 'categories'));
    }
}
