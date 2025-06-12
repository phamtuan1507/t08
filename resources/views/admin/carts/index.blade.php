<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý giỏ hàng</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-4 py-8">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Quản lý giỏ hàng</h1>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Quay lại Dashboard</a>
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

        @if ($carts->isEmpty())
            <p class="text-gray-600 text-lg text-center">Không có giỏ hàng nào.</p>
        @else
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Người dùng</th>
                            <th class="px-4 py-3">Số lượng sản phẩm</th>
                            <th class="px-4 py-3">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carts as $cart)
                            <tr class="border-b">
                                <td class="px-4 py-3">{{ $cart->id }}</td>
                                <td class="px-4 py-3">{{ $cart->user->name ?? 'N/A' }} ({{ $cart->user->email ?? 'N/A' }})</td>
                                <td class="px-4 py-3">{{ $cart->items->sum('quantity') }}</td>
                                <td class="px-4 py-3 flex space-x-2">
                                    <a href="{{ route('admin.carts.show', $cart) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Xem</a>
                                    <form action="{{ route('admin.carts.destroy', $cart) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa giỏ hàng này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="remove-btn">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination mt-4">
                {{ $carts->links() }}
            </div>
        @endif
    </div>
</body>
</html>
