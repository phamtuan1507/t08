@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-section">
            <!-- Hình ảnh sản phẩm -->
            <div class="w-full h-96 bg-gray-200 rounded-lg mb-4">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="w-full h-full object-cover rounded-lg">
                @else
                    <div class="w-full h-full bg-gray-300 rounded-lg flex items-center justify-center">
                        <span class="text-gray-500">No Image</span>
                    </div>
                @endif
            </div>

            <!-- Thông tin sản phẩm -->
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
                <p class="text-gray-600 mt-2">${{ number_format($product->price, 2) }}</p>
                <p class="text-gray-600 mt-2">{{ $product->description }}</p>
                <a href="{{ route('cart.add', $product->id) }}"
                    class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Thêm vào giỏ
                    hàng</a>

                <!-- Đánh giá trung bình -->
                <div class="mt-4">
                    @php
                        $averageRating = $product->reviews->where('approved', true)->avg('rating') ?? 0;
                    @endphp
                    <div class="flex items-center">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        @endfor
                        <span class="ml-2 text-sm text-gray-600">({{ $product->reviews->where('approved', true)->count() }}
                            đánh giá)</span>
                    </div>
                </div>

                <!-- Form đánh giá -->
                @auth
                    @include('user.products.review')
                @endauth


            </div>
        </div>
        <!-- Sản phẩm liên quan -->
        <h3 class="text-lg font-semibold mt-6">Sản phẩm liên quan</h3>
        @if ($relatedProducts->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                @foreach ($relatedProducts as $relatedProduct)
                    @include('user.products._partials.product-card', [
                        'product' => $relatedProduct,
                    ])
                @endforeach
            </div>
        @else
            <p class="text-gray-600">Không có sản phẩm liên quan.</p>
        @endif
    </div>
    <script>
        gsap.from(".animate-section > div", {
            opacity: 0,
            y: 50,
            duration: 1,
            stagger: 0.2,
            scrollTrigger: {
                trigger: ".animate-section",
                start: "top 80%"
            }
        });
    </script>
@endsection
