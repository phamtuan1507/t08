<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::with('category');
            if ($request->has('category')) {
                $query->where('category_id', $request->category);
            }
            $products = $query->paginate(12);
            $cartCount = Auth::check() ? Cart::firstOrCreate(['user_id' => Auth::id()])->items()->sum('quantity') : 0;
            // $categories = Category::all();
            $categories = Category::with('children')->whereNull('parent_id')->get();
            $banners = Banner::orderBy('order')->get();
            Log::info('Rendering homepage view', ['view' => 'welcome']);
            return view('welcome', compact('products', 'cartCount', 'categories', 'banners'));
        } catch (\Exception $e) {
            Log::error('Error rendering homepage', ['error' => $e->getMessage()]);
            abort(404, 'Page not found');
        }

    }
}
