@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('messages.product_list') }}</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <div class="bg-white rounded-lg shadow p-4">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-48 object-cover rounded-t-lg">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                        <p class="text-gray-600">${{ number_format($product->price, 2) }}</p>
                        <p class="text-sm text-gray-500">Stock: {{ $product->stock ?? 'Unlimited' }}</p>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="mb-4">
                                <label for="quantity-{{ $product->id }}"
                                    class="block text-sm font-medium text-gray-700">{{ __('messages.quantity') }}</label>
                                <input type="number" name="quantity" id="quantity-{{ $product->id }}" value="1"
                                    min="1" max="{{ $product->stock ?? 100 }}" class="w-20 p-2 border rounded-lg">
                            </div>
                            <button type="submit" class="add-to-cart-btn btn-hover ripple">
                                Thêm vào giỏ hàng
                                <span class="spinner hidden"></span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
