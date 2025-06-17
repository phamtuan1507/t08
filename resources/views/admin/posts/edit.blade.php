@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Sửa bài viết</h1>
        <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Tiêu đề</label>
                <input type="text" name="title" id="title" class="w-full p-2 border rounded"
                    value="{{ old('title', $post->title) }}" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Mô tả</label>
                <textarea name="description" id="description" class="w-full p-2 border rounded">{{ old('description', $post->description) }}</textarea>
            </div>
            <div class="mb-4">
                <label for="content" class="block text-gray-700">Nội dung</label>
                <textarea name="content" id="content" class="w-full p-2 border rounded" required>{{ old('content', $post->content) }}</textarea>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700">Ảnh bài viết</label>
                <input type="file" name="image" id="image" class="w-full p-2 border rounded">
                {{-- @if ($post->image)
                <img src="{{ asset($post->image) }}" alt="Current Image" width="100">
            @endif --}}
                @if ($post->image && Storage::disk('public')->exists($post->image))
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->name }}"
                        class="w-32 h-32 object-cover mt-2">
                @endif
            </div>
            <div class="form-check">
                <input type="checkbox" name="is_featured" id="is_featured" value="1"
                    {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                <label for="is_featured">Bài viết nổi bật</label>
            </div>
            <div class="form-check mb-4">
                <input type="checkbox" name="is_published" id="is_published" value="1"
                    {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                <label for="is_published">Hiển thị bài viết</label>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Cập nhật</button>
        </form>
    </div>

@endsection
