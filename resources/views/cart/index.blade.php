@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Giỏ hàng của bạn</h1>
        </div>
        @if ($items->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">Giỏ hàng của bạn đang trống.</p>
                <a href="{{ route('products.list') }}"
                    class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition sm:px-4 sm:text-sm">
                    Tiếp tục mua sắm</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Danh sách sản phẩm -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                        @foreach ($items as $item)
                            <div class="flex flex-col sm:flex-row items-center py-4 border-b last:border-b-0">
                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                    alt="{{ $item->product->name }}"
                                    class="w-20 h-20 object-cover rounded mr-0 sm:mr-4 mb-4 sm:mb-0">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                    <p class="text-gray-600">${{ number_format($item->product->price, 2) }}</p>
                                    <form action="{{ route('cart.update', $item) }}" method="POST"
                                        class="flex flex-col sm:flex-row items-center mt-2 space-y-2 sm:space-y-0 sm:space-x-2">
                                        @csrf
                                        @method('PUT')
                                        <label for="quantity-{{ $item->id }}"
                                            class="mr-2 text-gray-700 whitespace-nowrap">Số lượng:</label>
                                        <input type="number" name="quantity" id="quantity-{{ $item->id }}"
                                            value="{{ $item->quantity }}" min="1"
                                            max="{{ $item->product->stock ?? 100 }}"
                                            class="w-full sm:w-20 p-2 border rounded sm:mr-2">
                                        <button type="submit"
                                            class="add-to-cart-btn btn-hover ripple px-4 py-2 rounded-lgsm:px-3 sm:text-sm">
                                            Cập nhật
                                        </button>
                                    </form>
                                </div>
                                <div class="flex flex-col sm:flex-row items-center mt-2 sm:mt-0 sm:ml-4 w-full sm:w-auto">
                                    <p class="text-lg font-semibold text-gray-800 w-full sm:w-24 text-right sm:text-left mb-2 sm:mb-0">
                                        ${{ number_format($item->quantity * $item->product->price, 2) }}</p>
                                    <form action="{{ route('cart.destroy', $item) }}" method="POST"
                                        class="w-full sm:w-auto">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 w-full sm:w-auto sm:px-3 sm:text-sm">
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tóm tắt đơn hàng -->
                <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Tóm tắt đơn hàng</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tổng sản phẩm:</span>
                            <span class="font-semibold">{{ $cartCount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tổng tiền:</span>
                            <span
                                class="text-xl font-semibold text-gray-800">${{ number_format($items->sum(fn($item) => $item->quantity * $item->product->price), 2) }}</span>
                        </div>
                    </div>
                    <a href="{{ route('checkout.index') }}"
                        class="block mt-6 bg-green-600 text-white text-center px-6 py-3 rounded-lg hover:bg-green-700 transition sm:px-4 sm:text-sm">
                        Tiến hành thanh toán
                    </a>
                    <a href="{{ route('products.list') }}"
                        class="block mt-4 text-center text-blue-600 hover:underline sm:text-sm">
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        @endif

        <div class="mt-8 flex justify-center space-x-4 flex-col sm:flex-row">
            <a href="{{ route('dashboard') }}"
                class="bg-gray-500 text-center text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition w-full sm:w-auto sm:px-4 sm:text-sm">
                Quay lại Dashboard
            </a>
        </div>
    </div>
@endsection
