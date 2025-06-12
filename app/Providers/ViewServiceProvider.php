<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Category;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // View::composer(['user.dashboard', 'user.products', 'cart.index', 'welcome', 'checkout.index', 'products.show'], function ($view) {
        //     try {
        //         $cartCount = 0;
        //         if (Auth::check()) {
        //             $cart = Cart::where('user_id', Auth::id())->first();
        //             $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
        //         }
        //         // Fetch all categories
        //         $categories = Category::all();

        //         Log::info('View data calculated', [
        //             'user_id' => Auth::id() ?? 'guest',
        //             'cartCount' => $cartCount,
        //             'categories_count' => $categories->count(),
        //         ]);

        //         $view->with([
        //             'cartCount' => $cartCount,
        //             'categories' => $categories,
        //         ]);
        //     } catch (\Exception $e) {
        //         Log::error('Error in ViewServiceProvider', ['error' => $e->getMessage()]);
        //         $view->with([
        //             'cartCount' => 0,
        //             'categories' => collect([])
        //         ]);
        //     }
        // });
        View::composer(['user.dashboard', 'user.products', 'cart.index', 'welcome', 'checkout.index', 'products.show'], function ($view) {
            try {
                $cartCount = 0;
                if (Auth::check()) {
                    $cart = Cart::where('user_id', Auth::id())->first();
                    $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
                }
                $categories = Category::all();
                Log::info('View data calculated', [
                    'user_id' => Auth::id() ?? 'guest',
                    'cartCount' => $cartCount,
                    'categories_count' => $categories->count()
                ]);
                $view->with([
                    'cartCount' => $cartCount,
                    'categories' => $categories
                ]);
            } catch (\Exception $e) {
                Log::error('Error in ViewServiceProvider', ['error' => $e->getMessage()]);
                $view->with([
                    'cartCount' => 0,
                    'categories' => collect([])
                ]);
            }
        });
    }
}
