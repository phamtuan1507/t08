@extends('layouts.app')

@section('title', 'Danh sách sản phẩm')

@section('content')
    <div class="container mx-auto p-6">
        <!-- Bộ lọc và sắp xếp -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <form action="{{ route('products.list') }}" method="GET" id="filter-form">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <!-- Lọc danh mục -->
                    <div class="w-full md:w-1/4">
                        <select name="category" onchange="document.getElementById('filter-form').submit()" class="w-full p-2 border rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lọc khoảng giá -->
                    <div class="w-full md:w-1/4">
                        <label for="price-range" class="block text-sm font-medium text-gray-700">Khoảng giá</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="price_min" value="{{ request('price_min', 0) }}" placeholder="Tối thiểu" class="w-1/2 p-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" onchange="document.getElementById('filter-form').submit()">
                            <input type="number" name="price_max" value="{{ request('price_max', 1000) }}" placeholder="Tối đa" class="w-1/2 p-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" onchange="document.getElementById('filter-form').submit()">
                        </div>
                    </div>

                    <!-- Lọc đánh giá -->
                    <div class="w-full md:w-1/4">
                        <label class="block text-sm font-medium text-gray-700">Đánh giá</label>
                        <div class="flex gap-2">
                            <label><input type="checkbox" name="rating[]" value="5" {{ in_array('5', request('rating', [])) ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()"> 5 sao</label>
                            <label><input type="checkbox" name="rating[]" value="4" {{ in_array('4', request('rating', [])) ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()"> 4 sao+</label>
                        </div>
                    </div>

                    <!-- Sắp xếp -->
                    <div class="w-full md:w-1/4">
                        <select name="sort" onchange="document.getElementById('filter-form').submit()" class="w-full p-2 border rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">Sắp xếp theo</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến cao</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến thấp</option>
                        </select>
                    </div>
                </div>
            </form>

            {{-- <!-- Thanh tìm kiếm với gợi ý -->
            <div class="relative mt-4">
                <form action="{{ route('products.search') }}" method="GET">
                    <input type="text" id="search-input" name="query" placeholder="Tìm kiếm sản phẩm..." value="{{ request('query') }}" class="w-full p-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" onkeyup="suggestProducts()">
                    <button type="submit" class="absolute right-2 top-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
                <div id="suggestions" class="absolute z-10 w-full bg-white border rounded-lg shadow-lg mt-1 max-h-48 overflow-y-auto hidden"></div>
            </div> --}}
        </div>

        <!-- Danh sách sản phẩm -->
        @if($products->isEmpty())
            <p class="text-gray-600 text-center">Không tìm thấy sản phẩm nào.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    @include('user.products._partials.product-card', ['product' => $product])
                @endforeach
            </div>

            <!-- Phân trang -->
            <div class="mt-6">
                {{ $products->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

    <!-- JavaScript cho gợi ý sản phẩm -->
    <script>
        function suggestProducts() {
            const query = document.getElementById('search-input').value;
            const suggestionsDiv = document.getElementById('suggestions');

            if (query.length < 2) {
                suggestionsDiv.classList.add('hidden');
                return;
            }

            fetch(`/api/products/suggest?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        suggestionsDiv.innerHTML = data.map(product => `
                            <div class="p-2 hover:bg-gray-100 cursor-pointer" onclick="window.location.href='/products/${product.id}'">
                                ${product.name} - $${product.price}
                            </div>
                        `).join('');
                        suggestionsDiv.classList.remove('hidden');
                    } else {
                        suggestionsDiv.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching suggestions:', error);
                    suggestionsDiv.classList.add('hidden');
                });
        }

        // Ẩn gợi ý khi click ra ngoài
        document.addEventListener('click', function(event) {
            const suggestionsDiv = document.getElementById('suggestions');
            const searchInput = document.getElementById('search-input');
            if (!suggestionsDiv.contains(event.target) && !searchInput.contains(event.target)) {
                suggestionsDiv.classList.add('hidden');
            }
        });
    </script>
@endsection
