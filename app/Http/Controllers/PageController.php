<?php

namespace App\Http\Controllers;

use App\Mail\ContactReceived;
use App\Models\Banner;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about(Request $request)
    {
        // return view('pages.about');
        try {
            $query = Product::with('category');
            if ($request->has('category')) {
                $query->where('category_id', $request->category);
            }
            $products = $query->paginate(12);
            $cartCount = Auth::check() ? Cart::firstOrCreate(['user_id' => Auth::id()])->items()->sum('quantity') : 0;
            $categories = Category::all();
            $banners = Banner::orderBy('order')->get();
            Log::info('Rendering about view', ['view' => 'pages.about']);
            return view('pages.about', compact('products', 'cartCount', 'categories', 'banners'));
        } catch (\Exception $e) {
            Log::error('Error rendering about', ['error' => $e->getMessage()]);
            abort(404, 'Page not found');
        }
    }

    public function contact(Request $request)
    {
        try {
            $query = Product::with('category');
            if ($request->has('category')) {
                $query->where('category_id', $request->category);
            }
            $products = $query->paginate(12);
            $cartCount = Auth::check() ? Cart::firstOrCreate(['user_id' => Auth::id()])->items()->sum('quantity') : 0;
            $categories = Category::all();
            $banners = Banner::orderBy('order')->get();
            Log::info('Rendering about view', ['view' => 'pages.contact']);
            return view('pages.contact', compact('products', 'cartCount', 'categories', 'banners'));
        } catch (\Exception $e) {
            Log::error('Error rendering contact', ['error' => $e->getMessage()]);
            abort(404, 'Page not found');
        }
    }

    public function news(Request $request)
    {
        try {
            $query = Product::with('category');
            if ($request->has('category')) {
                $query->where('category_id', $request->category);
            }
            $products = $query->paginate(12);
            $cartCount = Auth::check() ? Cart::firstOrCreate(['user_id' => Auth::id()])->items()->sum('quantity') : 0;
            $categories = Category::all();
            $banners = Banner::orderBy('order')->get();
            Log::info('Rendering about view', ['view' => 'pages.news']);
            return view('pages.news', compact('products', 'cartCount', 'categories', 'banners'));
        } catch (\Exception $e) {
            Log::error('Error rendering news', ['error' => $e->getMessage()]);
            abort(404, 'Page not found');
        }
    }
    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $contact = Contact::create($validated);

        Log::info('Contact request submitted', ['data' => $validated]);

        // Send email to admin
        Mail::to('pham12126@gmail.com')->send(new ContactReceived($contact));

        return redirect()->route('contact')->with('success', 'Yêu cầu của bạn đã được gửi thành công!');
    }
}
