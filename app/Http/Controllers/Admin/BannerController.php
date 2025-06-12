<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order')->get();
        $cartCount = 0;
        $categories = Category::all();
        Log::info('Rendering banner index', ['view' => 'admin.banners.index']);
        return view('admin.banners.index', compact('banners', 'cartCount', 'categories'));
    }

    public function create()
    {
        $banners = Banner::orderBy('order')->get();
        $cartCount = 0;
        $categories = Category::all();
        Log::info('Rendering banner create', ['view' => 'admin.banners.create']);
        return view('admin.banners.create', compact('banners', 'cartCount', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'alt_text' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
        ]);

        try {
            $imagePath = $request->file('image')->store('banners', 'public');

            Banner::create([
                'image' => $imagePath,
                'link' => $request->input('link'),
                'alt_text' => $request->input('alt_text'),
                'order' => $request->input('order'),
            ]);

            Log::info('Banner created', ['alt_text' => $request->input('alt_text')]);
            return redirect()->route('admin.banners.index')->with('success', 'Banner đã được thêm.');
        } catch (\Exception $e) {
            Log::error('Error creating banner', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    public function edit(Banner $banner)
    {
        Log::info('Rendering banner edit', ['view' => 'admin.banners.edit', 'banner_id' => $banner->id]);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'alt_text' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
        ]);

        try {
            $data = [
                'link' => $request->input('link'),
                'alt_text' => $request->input('alt_text'),
                'order' => $request->input('order'),
            ];

            if ($request->hasFile('image')) {
                if ($banner->image) {
                    Storage::disk('public')->delete($banner->image);
                }
                $data['image'] = $request->file('image')->store('banners', 'public');
            }

            $banner->update($data);

            Log::info('Banner updated', ['banner_id' => $banner->id]);
            return redirect()->route('admin.banners.index')->with('success', 'Banner đã được cập nhật.');
        } catch (\Exception $e) {
            Log::error('Error updating banner', ['banner_id' => $banner->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    public function destroy(Banner $banner)
    {
        try {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->delete();

            Log::info('Banner deleted', ['banner_id' => $banner->id]);
            return redirect()->route('admin.banners.index')->with('success', 'Banner đã được xóa.');
        } catch (\Exception $e) {
            Log::error('Error deleting banner', ['banner_id' => $banner->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra. Vui lòng thử lại.');
        }
    }
}
