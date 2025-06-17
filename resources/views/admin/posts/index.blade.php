@extends('layouts.app')

@section('title', 'Blog Posts')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Danh sách bài viết</h2>
        <a href="{{ route('admin.posts.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg mb-4 inline-block">
            Thêm bài viết
        </a>
        <a href="{{ route('admin.dashboard') }}"
            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Quay lại Dashboard</a>
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 shadow">
                {{ session('success') }}
            </div>
        @endif
        @if ($posts->isEmpty())
            <p class="text-gray-600">Không có bài viết nào.</p>
        @else
            <table class="w-full bg-white rounded-lg shadow">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-4 text-left">ID</th>
                        <th class="p-4 text-left">Tiêu đề</th>
                        <th class="p-4 text-left">Nổi bật</th>
                        <th class="p-4 text-left">Hiển thị</th>
                        <th class="p-4 text-left">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td class="p-4">{{ $post->id }}</td>
                            <td class="p-4">{{ $post->title }}</td>
                            <td class="p-4">{{ $post->is_featured ? 'Có' : 'Không' }}</td>
                            <td class="p-4">{{ $post->is_published ? 'Hiển thị' : 'Ẩn' }}</td>
                            <td class="p-4">
                                <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-info text-blue-600 hover:underline">Xem</a>
                                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary text-blue-600 hover:underline">Sửa</a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="post"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>

@endsection
