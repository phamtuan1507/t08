@extends('layouts.app')

@section('title', 'Sản phẩm')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Category Sidebar -->
            <aside class="w-full md:w-1/4 bg-white rounded-lg shadow p-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Danh mục</h3>

            </aside>

            <!-- Product List -->
            <main class="w-full md:w-3/4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($products as $product)
                        <div class="bg-white rounded-lg shadow p-4">
                            @if ($products->image)
                                <img src="{{ asset('storage/' . $products->image) }}" alt="{{ $products->name }}" class="w-full h-48 object-cover rounded-lg mb-4">
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                                    <span class="text-gray-500">No Image</span>
                                </div>
                            @endif
                            <h2 class="text-xl font-semibold text-gray-800">{{ $products->name }}</h2>
                            <p class="text-gray-600">${{ number_format($products->price, 2) }}</p>
                            <p class="text-gray-600">Stock: {{ $products->stock ?? 'Không giới hạn' }}</p>
                            <a href="{{ route('products.show', $products) }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Xem chi tiết</a>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $products->links() }}</div>
            </main>
        </div>
    </div>
@endsection
