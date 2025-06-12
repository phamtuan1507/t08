@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">Quản lý Menu</h2>
        <a href="{{ route('admin.menus.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">Thêm Menu</a>
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">{{ session('success') }}</div>
        @endif
        <table class="w-full bg-white shadow rounded">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="p-2">ID</th>
                    <th class="p-2">Tên</th>
                    <th class="p-2">Slug</th>
                    <th class="p-2">URL</th>
                    <th class="p-2">Thứ tự</th>
                    <th class="p-2">Trạng thái</th>
                    <th class="p-2">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menus as $menu)
                    <tr class="border-b">
                        <td class="p-2">{{ $menu->id }}</td>
                        <td class="p-2">{{ $menu->name }}</td>
                        <td class="p-2">{{ $menu->slug }}</td>
                        <td class="p-2">{{ $menu->url }}</td>
                        <td class="p-2">{{ $menu->order }}</td>
                        <td class="p-2">{{ $menu->is_active ? 'Kích hoạt' : 'Tắt' }}</td>
                        <td class="p-2">
                            <a href="{{ route('admin.menus.edit', $menu->id) }}" class="text-blue-600 hover:underline">Sửa</a>
                            <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="inline-block ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
