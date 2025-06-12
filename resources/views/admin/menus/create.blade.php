@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">Thêm Menu</h2>
        <form action="{{ route('admin.menus.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Tên</label>
                <input type="text" name="name" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Slug</label>
                <input type="text" name="slug" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">URL</label>
                <input type="text" name="url" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Thứ tự</label>
                <input type="number" name="order" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Trạng thái</label>
                <input type="checkbox" name="is_active" value="1" checked>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Lưu</button>
        </form>
    </div>
@endsection
