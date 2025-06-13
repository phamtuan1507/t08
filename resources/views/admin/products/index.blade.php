<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Quản lý sản phẩm</h1>
        <a href="{{ route('admin.products.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg mb-4 inline-block">Thêm sản phẩm</a>
        <a href="{{ route('admin.dashboard') }}"
            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Quay lại Dashboard</a>
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 shadow">
                {{ session('success') }}
            </div>
        @endif
        @if ($products->isEmpty())
            <p class="text-gray-600">Không có sản phẩm nào.</p>
        @else
            <table class="w-full bg-white rounded-lg shadow">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-4 text-left">Tên</th>
                        <th class="p-4 text-left">Ảnh</th>
                        <th class="p-4 text-left">Giá</th>
                        <th class="p-4 text-left">Số lượng</th>
                        <th class="p-4 text-left">Danh mục</th>
                        <th class="p-4 text-left">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td class="p-4">{{ $product->name }}</td>
                            <td class="p-4">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="w-32 h-32 object-cover mt-2">
                                @endif
                            </td>
                            <td class="p-4">${{ number_format($product->price, 2) }}</td>
                            <td class="p-4">{{ $product->stock }}</td>
                            <td class="p-4">{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="p-4">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="text-blue-600 hover:underline">Sửa</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $products->links() }}</div>
        @endif
    </div>
</body>

</html>
