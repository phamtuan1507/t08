@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Sửa sản phẩm</h1>
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Tên</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                    class="w-full p-2 border rounded @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="price" class="block text-gray-700">Giá</label>
                <input type="number" name="price" id="price" step="0.01"
                    value="{{ old('price', $product->price) }}"
                    class="w-full p-2 border rounded @error('price') border-red-500 @enderror">
                @error('price')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="category_id" class="block text-gray-700">Danh mục</label>
                <select name="category_id" id="category_id"
                    class="w-full p-2 border rounded @error('category_id') border-red-500 @enderror">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Mô tả</label>
                <textarea name="description" id="description" class="w-full p-2 border rounded">{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700">Hình ảnh</label>
                <input type="file" name="image" id="image" class="w-full p-2 border rounded">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="w-32 h-32 object-cover mt-2">
                @endif
            </div>
            <div class="mb-4">
                <label for="stock" class="block text-gray-700">Tồn kho</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock') }}"
                    class="w-full p-2 border rounded">
                @error('stock')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Cập nhật</button>
        </form>
    </div>
@endsection
