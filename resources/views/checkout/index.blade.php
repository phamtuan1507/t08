@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Thông tin đơn hàng</h2>
        @if ($items->isEmpty())
            <p class="text-gray-600">Giỏ hàng của bạn trống.</p>
        @else
            <div class="mb-6">
                @foreach ($items as $item)
                    <div class="flex justify-between py-2">
                        <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                        <span>${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                    </div>
                @endforeach
                <div class="border-t pt-2 mt-2">
                    <strong>Tổng cộng: ${{ number_format($items->sum(fn($item) => $item->product->price * $item->quantity), 2) }}</strong>
                </div>
            </div>

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="address" class="block text-gray-700">Địa chỉ giao hàng</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" class="w-full p-2 border rounded @error('address') border-red-500 @enderror">
                    @error('address')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Phương thức thanh toán</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="payment_method" value="cash_on_delivery" {{ old('payment_method') == 'cash_on_delivery' ? 'checked' : '' }} class="form-radio">
                            <span class="ml-2">Thanh toán khi nhận hàng</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" name="payment_method" value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }} class="form-radio">
                            <span class="ml-2">Chuyển khoản ngân hàng</span>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">Xác nhận đặt hàng</button>
            </form>
        @endif
    </div>
@endsection
