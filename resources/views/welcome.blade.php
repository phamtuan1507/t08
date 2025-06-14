@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <section class="container py-5">
        <div class="bg-demo">
            <div class="bg-demo__content">
                <h2 class="text-lg font-bold text-white pb-3">
                    CHÀO MỪNG QUÝ KHÁCH ĐẾN VỚI T-Shop!
                </h2>
                <a href="/about" class="flex gap-1 relative cart-btn ripple px-4 py-2 rounded-md text-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" fill="#4FD1C5" />
                        <path d="M12 8V12L14 14" stroke="#2D3748" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <circle cx="12" cy="16" r="1" fill="#2D3748" />
                    </svg>
                    Tìm hiểu về chúng tôi
                </a>
            </div>
        </div>
    </section>

    {{-- <section>
        <!-- Carousel Banner -->
        <div class="col-span-1 sm:col-span-2 lg:col-span-3">
            <div class="banner-carousel owl-carousel mb-8">
                @foreach ($banners as $banner)
                    <div class="item">
                        <a
                            href="{{ $banner->product_id ? route('products.show', $banner->product) : $banner->link ?? '#' }}">
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->alt_text }}"
                                class="w-full h-auto max-h-[400px] object-cover rounded-lg">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section> --}}

    <section class="category py-5">
        <h2 class="line-bottom text-lg font-bold mb-5">Danh mục sản phẩm</h2>
        <div class="category-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($categories->take(3) as $category)
                <a href="{{ route('user.categories.show', $category) }}">
                    <div class="category-card">
                        <img src="{{ $category->image && Storage::disk('public')->exists($category->image) ? asset('storage/' . $category->image) : asset('images/no-image.jpg') }}"
                            alt="{{ $category->name }}">
                        <h3>{{ Str::upper($category->name) }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section>
        @if ($products->isEmpty())
            <p class="text-gray-600 text-lg text-center">Không có sản phẩm nào.</p>
        @else
            <div class="my-4">
                <h2 class="line-bottom text-lg font-bold">Menu</h2>
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
    </section>

    <section>
        <h2 class="text-2xl sm:text-3xl font-bold text-center text-yellow-800 mb-4 sm:mb-6">cheer BÁNH - chill HẾ CÙNG
            ARTEMIS!</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4">
            <!-- Main Section (2x2 on lg, full-width on sm) -->
            @if ($categories->count() > 0)
                <a href="{{ route('user.categories.show', $categories->first()) }}"
                    class="col-span-1 lg:col-span-2 lg:row-span-2 rounded-lg shadow-lg overflow-hidden">
                    <div class="w-full aspect-square">
                        <img src="{{ $categories->first()->image ? asset('storage/' . $categories->first()->image) : 'https://via.placeholder.com/400x400' }}"
                            alt="{{ $categories->first()->name }}" class="w-full h-full object-cover">
                    </div>
                </a>
            @else
                <a href="#" class="col-span-1 lg:col-span-2 lg:row-span-2 rounded-lg shadow-lg overflow-hidden">
                    <div class="w-full aspect-square">
                        <img src="https://via.placeholder.com/400x400" alt="No Category" class="w-full h-full object-cover">
                    </div>
                </a>
            @endif

            <!-- Top Right Section (1x1) -->
            @if ($categories->skip(1)->first())
                <a href="{{ route('user.categories.show', $categories->skip(1)->first()) }}"
                    class="col-span-1 rounded-lg shadow-lg overflow-hidden">
                    <div class="w-full aspect-square">
                        <img src="{{ $categories->skip(1)->first()->image ? asset('storage/' . $categories->skip(1)->first()->image) : 'https://via.placeholder.com/300x300' }}"
                            alt="{{ $categories->skip(1)->first()->name }}" class="w-full h-full object-cover">
                    </div>
                </a>
            @else
                <a href="#" class="col-span-1 rounded-lg shadow-lg overflow-hidden">
                    <div class="w-full aspect-square">
                        <img src="https://via.placeholder.com/300x300" alt="No Category" class="w-full h-full object-cover">
                    </div>
                </a>
            @endif

            <!-- Bottom Left Section (1x1) -->
            @if ($categories->skip(2)->first())
                <a href="{{ route('user.categories.show', $categories->skip(2)->first()) }}"
                    class="col-span-1 rounded-lg shadow-lg overflow-hidden">
                    <div class="w-full aspect-square">
                        <img src="{{ $categories->skip(2)->first()->image ? asset('storage/' . $categories->skip(2)->first()->image) : 'https://via.placeholder.com/300x300' }}"
                            alt="{{ $categories->skip(2)->first()->name }}" class="w-full h-full object-cover">
                    </div>
                </a>
            @else
                <a href="#" class="col-span-1 rounded-lg shadow-lg overflow-hidden">
                    <div class="w-full aspect-square">
                        <img src="https://via.placeholder.com/300x300" alt="No Category"
                            class="w-full h-full object-cover">
                    </div>
                </a>
            @endif

            <!-- Bottom Middle Section (1x1) -->
            @if ($categories->skip(3)->first())
                <a href="{{ route('user.categories.show', $categories->skip(3)->first()) }}"
                    class="col-span-1 rounded-lg shadow-lg overflow-hidden">
                    <div class="w-full aspect-square">
                        <img src="{{ $categories->skip(3)->first()->image ? asset('storage/' . $categories->skip(3)->first()->image) : 'https://via.placeholder.com/300x300' }}"
                            alt="{{ $categories->skip(3)->first()->name }}" class="w-full h-full object-cover">
                    </div>
                </a>
            @else
                <a href="#" class="col-span-1 rounded-lg shadow-lg overflow-hidden">
                    <div class="w-full aspect-square">
                        <img src="https://via.placeholder.com/300x300" alt="No Category"
                            class="w-full h-full object-cover">
                    </div>
                </a>
            @endif

            <!-- Bottom Right Section (1x1) -->
            @if ($categories->skip(4)->first())
                <a href="{{ route('user.categories.show', $categories->skip(4)->first()) }}"
                    class="col-span-1 rounded-lg shadow-lg overflow-hidden">
                    <div class="w-full aspect-square">
                        <img src="{{ $categories->skip(4)->first()->image ? asset('storage/' . $categories->skip(4)->first()->image) : 'https://via.placeholder.com/300x300' }}"
                            alt="{{ $categories->skip(4)->first()->name }}" class="w-full h-full object-cover">
                    </div>
                </a>
            @else
                <a href="#" class="col-span-1 rounded-lg shadow-lg overflow-hidden">
                    <div class="w-full aspect-square">
                        <img src="https://via.placeholder.com/300x300" alt="No Category"
                            class="w-full h-full object-cover">
                    </div>
                </a>
            @endif

            <!-- Bottom Right Section (1x1) -->
            @if ($categories->skip(5)->first())
                <a href="{{ route('user.categories.show', $categories->skip(5)->first()) }}"
                    class="col-span-1 rounded-lg shadow-lg overflow-hidden">
                    <div class="w-full aspect-square">
                        <img src="{{ $categories->skip(5)->first()->image ? asset('storage/' . $categories->skip(5)->first()->image) : 'https://via.placeholder.com/300x300' }}"
                            alt="{{ $categories->skip(5)->first()->name }}" class="w-full h-full object-cover">
                    </div>
                </a>
            @else
                <a href="#" class="col-span-1 rounded-lg shadow-lg overflow-hidden">
                    <div class="w-full aspect-square">
                        <img src="https://via.placeholder.com/300x300" alt="No Category"
                            class="w-full h-full object-cover">
                    </div>
                </a>
            @endif
        </div>
    </section>

    <section class="pt-5 choose">
        <h1 class="title text-center">TẠI SAO NÊN CHỌN CHÚNG TÔI?</h1>
        <div class="icon-group">
            <div class="icon-card">
                <div class="icon-circle">
                    <svg width="50" height="50" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2L15.09 8.26L22 9.27L17.91 13.73L18.5 20.73L12 17.77L5.5 20.73L6.09 13.73L2 9.27L8.91 8.26L12 2Z"
                            fill="#000000" />
                    </svg>
                </div>
                <p class="icon-text">Sản phẩm luân chuyển ngon, đảm bảo an toàn chất lượng</p>
            </div>
            <div class="icon-card">
                <div class="icon-circle">
                    <svg width="50" height="50" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM11 16H13V18H11V16ZM11 8H13V14H11V8Z"
                            fill="#000000" />
                    </svg>
                </div>
                <p class="icon-text">Hỗ trợ đặt online nhanh chóng, dễ dàng</p>
            </div>
            <div class="icon-card">
                <div class="icon-circle">
                    <svg width="50" height="50" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2C8.13 2 5 5.13 5 9C5 11.38 6.19 13.47 8 14.74V17C8 17.55 8.45 18 9 18H15C15.55 18 16 17.55 16 17V14.74C17.81 13.47 19 11.38 19 9C19 5.13 15.87 2 12 2ZM17 13H15V15H9V13H7V9C7 6.24 9.24 4 12 4C14.76 4 17 6.24 17 9V13ZM12 13C10.9 13 10 12.1 10 11C10 9.9 10.9 9 12 9C13.1 9 14 9.9 14 11C14 12.1 13.1 13 12 13Z"
                            fill="#000000" />
                    </svg>
                </div>
                <p class="icon-text">Có giao hàng tận nơi vô cùng tiện lợi</p>
            </div>
            {{-- <div class="icon-card">
                <div class="icon-circle">
                    <svg width="50" height="50" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M16 11C17.66 11 19 9.66 19 8C19 6.34 17.66 5 16 5C14.34 5 13 6.34 13 8C13 9.66 14.34 11 16 11ZM8 11C9.66 11 11 9.66 11 8C11 6.34 9.66 5 8 5C6.34 5 5 6.34 5 8C5 9.66 6.34 11 8 11ZM8 13C5.67 13 1 14.17 1 16.5V19H15V16.5C15 14.17 10.33 13 8 13ZM16 13C15.71 13 15.38 13.02 15.03 13.05C16.19 13.89 17 15.02 17 16.5V19H23V16.5C23 14.17 19.33 13 16 13Z"
                            fill="#000000" />
                    </svg>
                </div>
                <p class="icon-text">Có các phòng lớn tổ chức hội nghị với sự kiện</p>
            </div> --}}
        </div>
    </section>
@endsection
