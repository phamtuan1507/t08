<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        // $request->validate([
        //     'name' => 'required|string|max:255|unique:categories',
        //     'description' => 'nullable|string',
        // ]);

        // Category::create($request->all());

        // return redirect()->route('categories.index')->with('success', 'Category created successfully.');
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        try {
            Category::create($request->only('name'));
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
        // $request->validate([
        //     'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        //     'description' => 'nullable|string',
        // ]);

        // $category->update($request->all());

        // return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        try {
            $category->update($request->only('name'));
            return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật.');
        } catch (\Exception $e) {
            Log::error('Error updating category', ['category_id' => $category->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể cập nhật danh mục.');
        }
    }

    public function destroy(Category $category)
    {
        // $category->delete();
        // return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        try {
            $category->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa.');
        } catch (\Exception $e) {
            Log::error('Error deleting category', ['category_id' => $category->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Không thể xóa danh mục.');
        }
    }
}
