@extends('layouts.app')

@section('title', 'Thêm Banner')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-lg mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Thêm Banner</h2>
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-medium">Hình ảnh</label>
                <input type="file" name="image" id="image" class="w-full p-2 border rounded-md @error('image') border-red-600 @enderror">
                @error('image')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="alt_text" class="block text-gray-700 font-medium">Alt Text</label>
                <input type="text" name="alt_text" id="alt_text" value="{{ old('alt_text') }}" class="w-full p-2 border rounded-md @error('alt_text') border-red-600 @enderror">
                @error('alt_text')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="link" class="block text-gray-700 font-medium">Link (tùy chọn)</label>
                <input type="url" name="link" id="link" value="{{ old('link') }}" class="w-full p-2 border rounded-md @error('link') border-red-600 @enderror">
                @error('link')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="order" class="block text-gray-700 font-medium">Thứ tự</label>
                <input type="number" name="order" id="order" value="{{ old('order', 0) }}" class="w-full p-2 border rounded-md @error('order') border-red-600 @enderror">
                @error('order')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Thêm</button>
                <a href="{{ route('admin.banners.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">Hủy</a>
            </div>
        </form>
    </div>
@endsection
