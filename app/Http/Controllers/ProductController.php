<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        ]);

        // $data = $request->all();
        // if ($request->hasFile('image')) {
        //     $data['image'] = $request->file('image')->store('products', 'public');
        // }

        // Product::create($data);

        // return redirect()->route('products.index')->with('success', 'Product created successfully.');
        try {
            $data = $request->only(['name', 'description', 'price', 'stock', 'category_id']);
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            Product::create($data);
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm.');
        } catch (\Exception $e) {
            Log::error('Error creating product', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể thêm sản phẩm.');
        }
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
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
        ]);

        // $data = $request->all();
        // if ($request->hasFile('image')) {
        //     if ($product->image) {
        //         Storage::disk('public')->delete($product->image);
        //     }
        //     $data['image'] = $request->file('image')->store('products', 'public');
        // }

        // $product->update($data);

        // return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        try {
            $data = $request->only(['name', 'description', 'price', 'stock', 'category_id']);
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật.');
        } catch (\Exception $e) {
            Log::error('Error updating product', ['product_id' => $product->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể cập nhật sản phẩm.');
        }
    }

    public function destroy(Product $product)
    {
        //     if ($product->image) {
        //         Storage::disk('public')->delete($product->image);
        //     }
        //     $product->delete();
        //     return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        try {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa.');
        } catch (\Exception $e) {
            Log::error('Error deleting product', ['product_id' => $product->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể xóa sản phẩm.');
        }
    }
}
