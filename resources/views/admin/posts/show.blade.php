@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $post->title }}</h2>

        {{-- @if ($post->image)
            <img src="{{ asset($post->image) }}" alt="{{ $post->title }}"
                class="w-full max-w-md h-auto rounded-lg mb-6 object-cover">
        @endif --}}
        @if ($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->name }}"
                class="w-full h-48 object-cover rounded-lg mb-4">
        @else
            <div class="w-full h-48 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                <span class="text-gray-500">No Image</span>
            </div>
        @endif
        <p class="mb-4">
            <strong class="text-gray-700">Mô tả:</strong>
            <span class="text-gray-600">{{ $post->description }}</span>
        </p>

        <div class="mb-4 prose max-w-none">
            <strong class="text-gray-700">Nội dung:</strong>
            {!! $post->content !!}
        </div>

        <p class="mb-4">
            <strong class="text-gray-700">Nổi bật:</strong>
            <span class="text-gray-600">{{ $post->is_featured ? 'Có' : 'Không' }}</span>
        </p>

        <p class="mb-6">
            <strong class="text-gray-700">Trạng thái:</strong>
            <span class="text-gray-600">{{ $post->is_published ? 'Hiển thị' : 'Ẩn' }}</span>
        </p>

        <a href="{{ route('admin.posts.index') }}"
            class="inline-block px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
            Back
        </a>
    </div>
@endsection
