@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Đặt hàng thành công!</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="mb-4">Cảm ơn bạn đã đặt hàng! Mã đơn hàng của bạn là: <strong>#{{ $order->id }}</strong></p>
            @if($order->orderItems && $order->orderItems->isNotEmpty())
                <p class="mb-4">
                    Tổng tiền gốc: ${{ number_format($order->orderItems->sum(function ($item) {
                        return $item->price * $item->quantity;
                    }), 2) }}
                </p>
                @if($order->discountCode)
                    <p class="mb-4 text-green-600">Mã giảm giá: {{ $order->discountCode->code }} ({{ $order->discountCode->discount_percentage }}%)</p>
                    <p class="mb-4">
                        Số tiền giảm: ${{ number_format($order->orderItems->sum(function ($item) {
                            return $item->price * $item->quantity;
                        }) - $order->total, 2) }}
                    </p>
                @endif
            @else
                <p class="mb-4 text-red-600">Không tìm thấy chi tiết đơn hàng.</p>
            @endif
            <p class="mb-4">Tổng tiền thanh toán: ${{ number_format($order->total, 2) }}</p>
            <p class="mb-4">Địa chỉ giao hàng: {{ $order->address }}</p>
            <p class="mb-4">Phương thức thanh toán: {{ $order->payment_method === 'cash_on_delivery' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản ngân hàng' }}</p>
            <a href="{{ route('products.list') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tiếp tục mua sắm</a>
        </div>
    </div>
@endsection
