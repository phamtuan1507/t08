<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $data = $request->only('name');
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
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $data = $request->only('name');
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
}
