@extends('layouts.app')

@section('title', 'Thêm bài viết')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Thêm bài viết</h2>
        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Tiêu đề</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="w-full p-2 border rounded @error('title') border-red-500 @enderror" required>
                @error('title')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Mô tả</label>
                <textarea name="description" id="description" cols="50" rows="1">
                    {{ old('description') }}
                </textarea>
                @error('description')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="content" class="block text-gray-700">Nội dung</label>
                <textarea name="content" id="content" cols="50" rows="1">
                    {{ old('content') }}
                </textarea>
                @error('content')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700">Ảnh bài viết</label>
                <input type="file" name="image" id="image">
                @error('content')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-check">
                <input type="checkbox" name="is_featured" id="is_featured" value="1"
                    {{ old('is_featured') ? 'checked' : '' }}>
                <label for="is_featured">Bài viết nổi bật</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="is_published" id="is_published" value="1"
                    {{ old('is_published', 1) ? 'checked' : '' }}>
                <label for="is_published">Hiển thị bài viết</label>
            </div>
            <div class="flex space-x-4 mt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Thêm bài viết</button>
                <a href="{{ route('admin.posts.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Hủy</a>
            </div>
        </form>
    </div>

@endsection
