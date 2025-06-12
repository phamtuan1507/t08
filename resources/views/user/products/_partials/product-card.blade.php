<div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <a href="{{ route('products.show', $product->id) }}">
        <div class="w-full h-48 bg-gray-200">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                    class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                    <span class="text-gray-500">No Image</span>
                </div>
            @endif
        </div>
    </a>
    <div class="p-4">
        <a href="{{ route('products.show', $product->id) }}"
            class="block text-lg font-semibold text-gray-800 hover:text-blue-600">{{ $product->name }}</a>
        <p class="text-gray-600 mt-1">${{ number_format($product->price, 2) }}</p>
        <div class="flex items-center mt-2">
            @php
                $averageRating = $product->reviews->where('approved', true)->avg('rating') ?? 0;
            @endphp
            @for ($i = 1; $i <= 5; $i++)
                <svg class="w-4 h-4 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}"
                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                    </path>
                </svg>
            @endfor
            <span class="ml-2 text-sm text-gray-600">({{ $product->reviews->where('approved', true)->count() }})</span>
        </div>
        {{-- <a href="{{ route('cart.add', $product->id) }}" class="mt-3 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">Thêm vào giỏ hàng</a> --}}
        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="add-to-cart-btn btn-hover ripple">
                Thêm vào giỏ hàng
                <span class="spinner hidden"></span>
            </button>
        </form>
    </div>
</div>
