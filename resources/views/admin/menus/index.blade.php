@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">Quản lý Menu</h2>
        <a href="{{ route('admin.menus.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">Thêm Menu</a>
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">{{ session('success') }}</div>
        @endif
        <table class="w-full bg-white shadow rounded mt-4">
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
                    @if ($menu->parent_id === null)
                        <tr class="border-b">
                            <td class="px-4 py-3">{{ $menu->id }}</td>
                            <td class="px-4 py-3">{{ $menu->name }}</td>
                            <td class="px-4 py-3">{{ $menu->slug }}</td>
                            <td class="px-4 py-3">{{ $menu->url }}</td>
                            <td class="px-4 py-3">{{ $menu->order }}</td>
                            <td class="px-4 py-3">{{ $menu->is_active ? 'Tắt' : 'Bật' }}</td>
                            <td class="px-4 py-3 flex space-x-2">
                                <a href="{{ route('admin.menus.edit', $menu->id) }}"
                                    class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">Sửa</a>
                                <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa menu này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @if ($menu->children->count())
                            @foreach ($menu->children as $child)
                                <tr class="border-b bg-gray-50">
                                    <td class="px-4 py-3 pl-12">{{ $child->id }}</td>
                                    <td class="px-4 py-3 pl-12">{{ $child->name }}</td>
                                    <td class="px-4 py-3 pl-12">{{ $child->slug }}</td>
                                    <td class="px-4 py-3 pl-12">{{ $child->url }}</td>
                                    <td class="px-4 py-3 pl-12">{{ $child->order }}</td>
                                    <td class="px-4 py-3 pl-12">{{ $child->is_active ? 'Tắt' : 'Bật' }}</td>
                                    <td class="px-4 py-3 pl-12 flex space-x-2">
                                        <a href="{{ route('admin.menus.edit', $child->id) }}"
                                            class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">Sửa</a>
                                        <form action="{{ route('admin.menus.destroy', $child->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa menu này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
