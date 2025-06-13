@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')

    <!-- Carousel Banner -->
    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
        <div class="banner-carousel owl-carousel mb-8">
            @foreach ($banners as $banner)
                <div class="item">
                    <a href="{{ $banner->product_id ? route('products.show', $banner->product) : $banner->link ?? '#' }}">
                        <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->alt_text }}"
                            class="w-full h-auto max-h-[400px] object-cover rounded-lg">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @if ($products->isEmpty())
        <p class="text-gray-600 text-lg text-center">Không có sản phẩm nào.</p>
    @else
        <div class="my-4">
            <h2 class="line-bottom text-lg font-bold">Danh sách sản phẩm</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- @foreach ($products as $product)
                <div class="product-card">
                    <a href="{{ route('products.show', $product) }}">
                        <img
                            src="{{ $product->image && Storage::disk('public')->exists($product->image) ? asset('storage/' . $product->image) : asset('images/no-image.jpg') }}">
                        <h3>{{ $product->name }}</h3>
                        <p>${{ number_format($product->price, 2) }}</p>
                    </a>
                    @if (Auth::check() && $product->stock !== null && $product->stock > 0)
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="add-to-cart-btn btn-hover ripple">
                                Thêm vào giỏ hàng
                                <span class="spinner hidden"></span>
                            </button>
                        </form>
                    @elseif (!Auth::check())
                        <p class="mt-4 text-red-500 text-sm">Vui lòng <a href="{{ route('login') }}" class="underline">đăng
                                nhập</a> để thêm vào giỏ hàng.</p>
                    @else
                        <p class="mt-4 text-red-500 text-sm">Hết hàng</p>
                    @endif
                </div>
            @endforeach --}}
            {{-- @foreach ($products as $product)
                <div class="product-card">
                    <a href="{{ route('products.show', $product) }}" class="open-modal" data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}">
                        <img src="{{ $product->image && Storage::disk('public')->exists($product->image) ? asset('storage/' . $product->image) : asset('images/no-image.jpg') }}"
                            alt="{{ $product->name }}">
                        <h3>{{ $product->name }}</h3>
                        <p>${{ number_format($product->price, 2) }}</p>
                    </a>
                    @if (Auth::check() && $product->stock !== null && $product->stock > 0)
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="add-to-cart-btn btn-hover ripple">
                                Thêm vào giỏ hàng
                                <span class="spinner hidden"></span>
                            </button>
                        </form>
                    @elseif (!Auth::check())
                        <p>Số lượng sản phẩm : {{ $product->stock }}</p>
                    @else
                        <p class="mt-4 text-red-500 text-sm">Hết hàng</p>
                    @endif
                </div>
            @endforeach --}}
            @foreach ($products as $product)
                <div class="product-card">
                    @if (Auth::check() && $product->stock !== null && $product->stock > 0)
                        <a href="{{ route('products.show', $product) }}">
                            <img src="{{ $product->image && Storage::disk('public')->exists($product->image) ? asset('storage/' . $product->image) : asset('images/no-image.jpg') }}"
                                alt="{{ $product->name }}">
                            <h3>{{ $product->name }}</h3>
                            <p>${{ number_format($product->price, 2) }}</p>
                        </a>
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="add-to-cart-btn btn-hover ripple">
                                Thêm vào giỏ hàng
                                <span class="spinner hidden"></span>
                            </button>
                        </form>
                    @else
                        <a href="#" class="open-modal" data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}">
                            <img src="{{ $product->image && Storage::disk('public')->exists($product->image) ? asset('storage/' . $product->image) : asset('images/no-image.jpg') }}"
                                alt="{{ $product->name }}">
                            <h3>{{ $product->name }}</h3>
                            <p>${{ number_format($product->price, 2) }}</p>
                        </a>
                        @if (!Auth::check())
                            {{-- <p class="mt-4 text-red-500 text-sm">Vui lòng <a href="#" class="open-modal"
                                    data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}">đăng
                                    nhập</a> để thêm vào giỏ hàng.</p> --}}
                            <p>Số lượng sản phẩm : {{ $product->stock }}</p>
                        @elseif ($product->stock === null || $product->stock <= 0)
                            <p class="mt-4 text-red-500 text-sm">Hết hàng</p>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
        <div class="pagination">
            {{ $products->links() }}
        </div>
    @endif
    <!-- Modal -->
    <div id="loginModal" class="modal hidden">
        <div class="modal-content">
            <span class="close-modal">×</span>
            <h2>Thông báo</h2>
            <p>Bạn cần đăng nhập để thêm sản phẩm <span id="modal-product-name"></span> vào giỏ hàng.</p>
            <a href="{{ route('login') }}" class="login-btn">Đăng nhập</a>
            <button class="close-btn">Đóng</button>
        </div>
    </div>
@endsection
