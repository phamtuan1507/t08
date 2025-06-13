<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Shop')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans">
    <!-- Header -->
    <header class="header bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <!-- Mobile Menu Toggle -->

            <div class="md:hidden">
                <button id="menuToggle" class="md:hidden text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
                <ul id="menuList" class="hidden mt-2 space-y-2 bg-gray-200 p-2 rounded">
                    {{-- <li><a href="{{ route('home') }}" class="block text-gray-800 hover:text-blue-600">Trang chủ</a></li>
                    <li><a href="{{ route('products.list') }}" class="block text-gray-800 hover:text-blue-600">Sản
                            phẩm</a>
                    </li>
                    <li><a href="{{ route('about') }}" class="block text-gray-800 hover:text-blue-600">Giới thiệu</a>
                    </li>
                    <li><a href="{{ route('contact') }}" class="block text-gray-800 hover:text-blue-600">Liên hệ</a>
                    </li>
                    <li><a href="{{ route('news') }}" class="block text-gray-800 hover:text-blue-600">Tin tức</a></li> --}}
                    @foreach ($menus as $menu)
                        <li><a href="{{ $menu->url ?? '#' }}" class="hover:text-blue-600">{{ $menu->name }}</a></li>
                    @endforeach
                    <!-- User Icon -->
                    @auth
                        <div class="relative w-full">
                            <button id="userDropdown" class="px-4 py-2 rounded-md user-btn w-full text-center">
                                {{ Auth::user()->name }}
                            </button>
                            <div id="dropdownMenu"
                                class="absolute right-0 z-50 mt-2 w-48 bg-white rounded-lg shadow-lg hidden">
                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>
                                <a href="{{ route('profile.index') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Hồ sơ</a>
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 w-full text-center">Đăng
                            nhập</a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 w-full text-center">Đăng
                            ký</a>
                    @endauth
                </ul>
            </div>
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/logo.jpg') }}" alt="Shop Logo" class="h-10 w-auto">
                <span class="ml-2 text-2xl font-bold text-gray-800">Shop</span>
            </a>
            <!-- Cart Icon -->
            <div class="relative md:hidden">
                <a href="{{ route('cart.index') }}" class="relative cart-btn ripple px-4 py-2 rounded-md text-center">
                    <svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    @if (isset($cartCount) && $cartCount > 0)
                        <span class="absolute -top-2 -right-2 cart-count" id="cart-count">{{ $cartCount }}</span>
                    @else
                        <span class="text-gray-300 ml-2 text-sm hidden md:inline">0</span>
                    @endif
                </a>
            </div>
            <!-- Search Bar -->
            <form action="{{ route('products.search') }}" method="GET" class="flex-1 mx-8 mb-0 search-none">
                <div class="relative">
                    <input type="text" name="query" placeholder="Tìm kiếm sản phẩm..."
                        value="{{ request()->query('query') }}"
                        class="w-full p-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-500" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </form>

            <!-- Navigation -->
            <div class="flex items-center space-x-4">
                <div class="language-switcher">
                    <form action="{{ url()->current() }}" method="GET">
                        <select name="locale" onchange="this.form.submit()" class="p-2 border rounded-lg">
                            <option value="vi" {{ session('locale') == 'vi' ? 'selected' : '' }}>Tiếng Việt
                            </option>
                            <option value="en" {{ session('locale') == 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </form>
                </div>
                {{-- <a href="{{ route('cart.index') }}" class="relative cart-btn ripple px-4 py-2 rounded-md">
                    Giỏ hàng
                    @if (isset($cartCount) && $cartCount > 0)
                        <span class="absolute -top-2 -right-2 cart-count" id="cart-count">{{ $cartCount }}</span>
                    @else
                        <span class="text-gray-300 ml-2 text-sm">0</span>
                    @endif
                </a> --}}
                <a href="{{ route('cart.index') }}" class="relative cart-btn ripple px-4 py-2 rounded-md text-center">
                    <svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    @if (isset($cartCount) && $cartCount > 0)
                        <span class="absolute -top-2 -right-2 cart-count" id="cart-count">{{ $cartCount }}</span>
                    @else
                        <span class="text-gray-300 ml-2 text-sm hidden md:inline">0</span>
                    @endif
                </a>
                @auth
                    <div class="relative">
                        <button id="userDropdown1" class="px-4 py-2 rounded-md user-btn">
                            {{ Auth::user()->name }}
                        </button>
                        <div id="dropdownMenu1"
                            class="absolute right-0 z-50 mt-2 w-48 bg-white rounded-lg shadow-lg hidden">
                            <a href="{{ route('dashboard') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>
                            <a href="{{ route('profile.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Hồ sơ</a>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"
                                fill="#e5e7eb" />
                            <path
                                d="M18 13H15C13.6739 13 12.4021 13.5268 11.4645 14.4645C10.5268 15.4021 10 16.6739 10 18V21H14V18C14 17.2044 14.3161 16.4413 14.8787 15.8787C15.4413 15.3161 16.2044 15 17 15H18V13Z"
                                fill="#e5e7eb" />
                            <path d="M4 12H6V14H4C2.89543 14 2 13.1046 2 12C2 10.8954 2.89543 10 4 10V12Z"
                                fill="#e5e7eb" />
                        </svg>
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2Z"
                                fill="#e5e7eb" />
                            <path
                                d="M18 14H15C13.6739 14 12.4021 14.5268 11.4645 15.4645C10.5268 16.4021 10 17.6739 10 19V22H14V19C14 18.2044 14.3161 17.4413 14.8787 16.8787C15.4413 16.3161 16.2044 16 17 16H18V14Z"
                                fill="#e5e7eb" />
                            <path d="M5 12H7V14H5C3.89543 14 3 13.1046 3 12C3 10.8954 3.89543 10 5 10V12Z"
                                fill="#e5e7eb" />
                            <path d="M19 8H21V10H19V12H17V10H15V8H17V6H19V8Z" fill="#e5e7eb" />
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
        <!-- Mobile Menu and Icons -->
        <div id="mobileMenu" class="md:hidden px-4 py-2 bg-gray-100 hidden">
            <div class="flex flex-col space-y-2">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center justify-center mb-2">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Shop Logo" class="h-10 w-auto">
                    <span class="ml-2 text-2xl font-bold text-gray-800"></span>
                </a>
                <!-- Search Bar -->
                <form action="{{ route('products.search') }}" method="GET" class="">
                    <div class="relative mb-2">
                        <input type="text" name="query" placeholder="Tìm kiếm sản phẩm..."
                            value="{{ request()->query('query') }}"
                            class="w-full p-2 pl-10 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-500"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </form>
                <!-- Cart Icon -->
                <a href="{{ route('cart.index') }}"
                    class="relative cart-btn ripple px-4 py-2 rounded-md w-full text-center">
                    Giỏ hàng
                    @if (isset($cartCount) && $cartCount > 0)
                        <span class="absolute -top-2 -right-2 cart-count" id="cart-count">{{ $cartCount }}</span>
                    @else
                        <span class="text-gray-300 ml-2 text-sm">0</span>
                    @endif
                </a>
                <!-- User Icon -->

            </div>
        </div>
    </header>
    <div class="container search-none">
        <!-- Secondary Menu -->
        <nav class="bg-gray-200 shadow-md">
            <div class="container mx-auto px-4 py-2">
                <ul class="flex space-x-6 overflow-x-auto whitespace-nowrap">
                    {{-- <li><a href="{{ route('home') }}" class="text-gray-800 hover:text-blue-600 font-medium">Trang
                            chủ</a></li>
                    <li><a href="{{ route('products.list') }}"
                            class="text-gray-800 hover:text-blue-600 font-medium">Sản phẩm</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-800 hover:text-blue-600 font-medium">Giới
                            thiệu</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-800 hover:text-blue-600 font-medium">Liên
                            hệ</a></li>
                    <li><a href="{{ route('news') }}" class="text-gray-800 hover:text-blue-600 font-medium">Tin
                            tức</a>
                    </li> --}}
                    @foreach ($menus as $menu)
                        <li><a href="{{ $menu->url ?? '#' }}" class="hover:text-blue-600">{{ $menu->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </nav>

    </div>
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
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
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Shop</h3>
                    <p class="text-gray-400">Cung cấp sản phẩm chất lượng cao với giá cả hợp lý.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liên kết nhanh</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white">Trang chủ</a></li>
                        <li><a href="{{ route('products.list') }}" class="text-gray-400 hover:text-white">Sản
                                phẩm</a>
                        </li>
                        <li><a href="{{ route('cart.index') }}" class="text-gray-400 hover:text-white">Giỏ hàng</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liên hệ</h3>
                    <p class="text-gray-400">Email: support@shop.com</p>
                    <p class="text-gray-400">Phone: 0123 456 789</p>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-4 text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} Shop. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
{{-- jquery --}}
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>
<script>
    // document.getElementById('menuToggle').addEventListener('click', function() {
    //     document.getElementById('menuList').classList.toggle('hidden');
    // });
    document.getElementById('menuToggle').addEventListener('click', function() {
        const menuList = document.getElementById('menuList');
        menuList.classList.toggle('show');
    });
</script>
<script>
    document.querySelectorAll('.ripple').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.classList.add('ripple-effect');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            this.appendChild(ripple);

            ripple.addEventListener('animationend', () => {
                ripple.remove();
            });

            // Hiển thị loading
            const spinner = this.querySelector('.spinner');
            const buttonText = this.textContent;
            this.classList.add('loading');
            spinner.classList.remove('hidden');

            // Giả lập thời gian xử lý (thay bằng logic thực tế)
            setTimeout(() => {
                this.classList.remove('loading');
                spinner.classList.add('hidden');
                // Thêm logic thành công nếu cần (ví dụ: thông báo)
            }, 2000); // 2 giây
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

</html>
