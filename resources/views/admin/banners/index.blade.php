@extends('layouts.app')

@section('title', 'Quản lý Banner')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Danh sách Banner</h2>
            </div>
            <div>
                <a href="{{ route('admin.banners.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Thêm Banner</a>
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Quay lại Dashboard</a>
            </div>
        </div>
        @if ($banners->isEmpty())
            <p class="text-gray-600">Chưa có banner nào.</p>
        @else
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2 text-left">Hình ảnh</th>
                        <th class="border p-2 text-left">Alt Text</th>
                        <th class="border p-2 text-left">Link</th>
                        <th class="border p-2 text-left">Thứ tự</th>
                        <th class="border p-2 text-left">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banners as $banner)
                        <tr>
                            <td class="border p-2">
                                <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->alt_text }}" class="h-16 w-auto">
                            </td>
                            <td class="border p-2">{{ $banner->alt_text }}</td>
                            <td class="border p-2">{{ $banner->link ?? 'N/A' }}</td>
                            <td class="border p-2">{{ $banner->order }}</td>
                            <td class="border p-2">
                                <a href="{{ route('admin.banners.edit', $banner) }}" class="text-blue-600 hover:underline">Sửa</a>
                                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Bạn có chắc muốn xóa banner này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
