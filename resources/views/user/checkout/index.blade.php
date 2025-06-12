@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Thanh toán</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Giỏ hàng -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Sản phẩm trong giỏ hàng</h2>
            @foreach ($items as $item)
                <div class="flex justify-between mb-2">
                    <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                    <span>${{ number_format($item->quantity * $item->product->price, 2) }}</span>
                </div>
            @endforeach
            <hr class="my-4">
            <div class="flex justify-between">
                <span>Tạm tính:</span>
                <span>${{ number_format($subtotal, 2) }}</span>
            </div>
            @if ($discountAmount > 0)
                <div class="flex justify-between text-green-600">
                    <span>Giảm giá ({{ $discountCode->discount_percentage }}%):</span>
                    <span>-${{ number_format($discountAmount, 2) }}</span>
                </div>
            @endif
            <div class="flex justify-between font-semibold">
                <span>Tổng cộng:</span>
                <span>${{ number_format($total, 2) }}</span>
            </div>

            <!-- Áp dụng mã giảm giá -->
            <form action="{{ route('checkout.apply.discount') }}" method="POST" class="mt-4">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="discount_code" placeholder="Nhập mã giảm giá"
                        class="w-full p-2 border rounded-lg">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Áp
                        dụng</button>
                </div>
            </form>
        </div>

        <!-- Form đặt hàng -->
        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <input type="hidden" name="discount_code"
                value="{{ old('discount_code', session('applied_discount_code')) }}">
            <div class="mb-4">
                <label for="address" class="block text-gray-700">Địa chỉ giao hàng:</label>
                <input type="text" name="address" id="address" class="w-full p-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="payment_method" class="block text-gray-700">Phương thức thanh toán:</label>
                <select name="payment_method" id="payment_method" class="w-full p-2 border rounded-lg" required>
                    <option value="cash_on_delivery">Thanh toán khi nhận hàng</option>
                    <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                </select>
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Đặt hàng</button>
        </form>
    </div>
@endsection
