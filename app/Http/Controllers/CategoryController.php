<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        try {
            $data = $request->only(['name', 'parent_id']);
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
                $data['image'] = $imagePath;
            }

            Category::create($data);
            return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được thêm.');
        } catch (\Exception $e) {
            Log::error('Error creating category', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể thêm danh mục.');
        }
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        try {
            $data = $request->only(['name', 'parent_id']);
            if ($request->hasFile('image')) {
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $data['image'] = $request->file('image')->store('categories', 'public');
            }
            $category->update($data);
            return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật.');
        } catch (\Exception $e) {
            Log::error('Error updating category', ['category_id' => $category->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể cập nhật danh mục.');
        }
    }

    public function destroy(Category $category)
    {
        try {
            // Xóa ảnh nếu tồn tại
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $category->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa.');
        } catch (\Exception $e) {
            Log::error('Error deleting category', ['category_id' => $category->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể xóa danh mục.');
        }
    }
    public function show(Request $request,Category $category)
    {
        $query = $category->products()->with(['category', 'reviews']);

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

        $products = $query->paginate(12);
        $cartCount = 0;
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cartCount = $cart->items()->sum('quantity');
        }

        $categories = Category::all();

        Log::info('Rendering product list', ['view' => 'user.categories.products.list', 'query' => $request->all()]);
        return view('user.categories.show', compact('category','categories', 'products'));
    }
}
