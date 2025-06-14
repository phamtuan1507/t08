@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Right Column: Category List -->
            <div class="w-full lg:w-1/4">
                <div class="bg-white shadow-md rounded-lg p-4 sticky top-6">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Danh mục</h3>
                    <ul class="space-y-2">
                        @foreach ($categories as $cat)
                            <li>
                                <a href="{{ route('user.categories.show', $cat) }}"
                                    class="block p-2 rounded-lg hover:bg-gray-100 {{ $category->id === $cat->id ? 'bg-blue-100 text-blue-800' : 'text-gray-700' }}">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- Left Column: Product List with Filters -->
            <div class="w-full lg:w-3/4">
                <!-- Bộ lọc và sắp xếp -->
                <div class="bg-white shadow-md rounded-lg p-4 mb-6">
                    <form action="{{ route('user.categories.show', $category) }}" method="GET" id="filter-form">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <!-- Sắp xếp -->
                            <div class="w-full md:w-1/4">
                                <select name="sort" onchange="document.getElementById('filter-form').submit()"
                                    class="w-full p-2 border rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="">Sắp xếp theo</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá:
                                        Thấp đến cao</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá:
                                        Cao đến thấp</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Danh sách sản phẩm -->
                @if ($products->isEmpty())
                    <p class="text-gray-600 text-center">Không tìm thấy sản phẩm nào trong danh mục {{ $category->name }}.
                    </p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($products as $product)
                            @include('user.products._partials.product-card', ['product' => $product])
                        @endforeach
                    </div>

                    <!-- Phân trang -->
                    <div class="mt-6">
                        {{ $products->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>


        </div>
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

            fetch(`/api/products/suggest?query=${encodeURIComponent(query)}&category_id={{ $category->id }}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        suggestionsDiv.innerHTML = data.map(product => `
                            <div class="p-2 hover:bg-gray-100 cursor-pointer" onclick="window.location.href='{{ route('products.show', ['product' => ':id']) }}'.replace(':id', ${product.id})">
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
