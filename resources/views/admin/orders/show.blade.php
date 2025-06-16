@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Chi tiết đơn hàng #{{ $order->id }}</h1>
            <div class="flex space-x-4">
                <a href="{{ route('admin.orders.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Quay lại danh
                    sách</a>
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                    onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="remove-btn">Xóa đơn hàng</button>
                </form>
            </div>
        </div>

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
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Thông tin đơn hàng</h2>
            <p class="text-gray-600">Người dùng: {{ $order->user->name ?? 'N/A' }} ({{ $order->user->email ?? 'N/A' }})
            </p>
            <p class="text-gray-600">Tổng tiền: ${{ number_format($order->total, 2) }}</p>
            <p class="text-gray-600">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="mt-4">
                @csrf
                @method('PUT')
                <label for="status" class="text-gray-700">Trạng thái:</label>
                <select name="status" id="status" class="p-2 border rounded">
                    @foreach (['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="ml-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Cập
                    nhật</button>
            </form>

            <h2 class="text-xl font-semibold text-gray-800 mt-6 mb-4">Sản phẩm trong đơn hàng</h2>
            @if ($order->items->isEmpty())
                <p class="text-gray-600">Không có sản phẩm nào.</p>
            @else
                <div class="flex flex-col">
                    @foreach ($order->items as $item)
                        <div class="flex items-center py-4 border-b last:border-b-0">
                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                alt="{{ $item->product->name }}" class="w-20 h-20 object-cover rounded mr-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                <p class="text-gray-600">Giá: ${{ number_format($item->price, 2) }}</p>
                                <p class="text-gray-600">Số lượng: {{ $item->quantity }}</p>
                            </div>
                            <p class="text-lg font-semibold text-gray-800 w-24 text-right">
                                ${{ number_format($item->quantity * $item->price, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
