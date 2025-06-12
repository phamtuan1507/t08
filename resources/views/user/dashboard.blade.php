<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}!</h1>
        <p>This is your dashboard.</p>
        <a href="{{ route('user.orders.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 inline-block">Xem lịch sử đơn hàng</a>
        <a href="{{ route('products.list') }}" class="bg-blue-500 text-white px-4 py-2 rounded">View Products</a>
        <a href="{{ route('cart.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded ml-2 relative">
            View Cart
            @php
                $cartCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->first()?->items()->sum('quantity') ?? 0 : 0;
            @endphp
            @if ($cartCount > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full px-2">{{ $cartCount }}</span>
            @else
                <span class="text-gray-600 ml-2">Chưa có gì trong giỏ hàng</span>
            @endif
        </a>
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2">Logout</button>
        </form>
    </div>
</body>
</html>
