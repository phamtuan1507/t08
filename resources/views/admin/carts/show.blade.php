<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết giỏ hàng #{{ $cart->id }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-4 py-8">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Chi tiết giỏ hàng #{{ $cart->id }}</h1>
            <div class="flex space-x-4">
                <a href="{{ route('admin.carts.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Quay lại danh sách</a>
                <form action="{{ route('admin.carts.destroy', $cart) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa giỏ hàng này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="remove-btn">Xóa giỏ hàng</button>
                </form>
            </div>
        </header>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 shadow">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Thông tin giỏ hàng</h2>
            <p class="text-gray-600">Người dùng: {{ $cart->user->name ?? 'N/A' }} ({{ $cart->user->email ?? 'N/A' }})</p>
            <p class="text-gray-600">Tổng số lượng: {{ $cart->items->sum('quantity') }}</p>

            <h2 class="text-xl font-semibold text-gray-800 mt-6 mb-4">Sản phẩm trong giỏ hàng</h2>
            @if ($cart->items->isEmpty())
                <p class="text-gray-600">Không có sản phẩm nào.</p>
            @else
                <div class="flex flex-col">
                    @foreach ($cart->items as $item)
                        <div class="flex items-center py-4 border-b last:border-b-0">
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-20 h-20 object-cover rounded mr-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                <p class="text-gray-600">Giá: ${{ number_format($item->product->price, 2) }}</p>
                                <p class="text-gray-600">Số lượng: {{ $item->quantity }}</p>
                            </div>
                            <form action="{{ route('admin.carts.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-btn">Xóa</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>
