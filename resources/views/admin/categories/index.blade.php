<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-4 py-8">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Quản lý danh mục</h1>
            <div class="flex space-x-4">
                <a href="{{ route('admin.categories.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Thêm danh mục</a>
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Quay lại Dashboard</a>
            </div>
        </header>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 shadow">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow">
                {{ session('error') }}
            </div>
        @endif

        @if ($categories->isEmpty())
            <p class="text-gray-600 text-lg text-center">Không có danh mục nào.</p>
        @else
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Tên danh mục</th>
                            <th class="px-4 py-3">Ảnh</th>
                            <th class="px-4 py-3">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            @if ($category->parent_id === null) <!-- Chỉ hiển thị danh mục cha -->
                                <tr class="border-b">
                                    <td class="px-4 py-3">{{ $category->id }}</td>
                                    <td class="px-4 py-3">{{ $category->name }}</td>
                                    <td class="p-4">
                                        @if ($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                                class="w-32 h-32 object-cover mt-2">
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 flex space-x-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">Sửa</a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                                @if ($category->children->count())
                                    @foreach ($category->children as $child)
                                        <tr class="border-b bg-gray-50">
                                            <td class="px-4 py-3 pl-12">{{ $child->id }}</td>
                                            <td class="px-4 py-3 pl-12">{{ $child->name }}</td>
                                            <td class="p-4 pl-12">
                                                @if ($child->image)
                                                    <img src="{{ asset('storage/' . $child->image) }}" alt="{{ $child->name }}"
                                                        class="w-32 h-32 object-cover mt-2">
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 pl-12 flex space-x-2">
                                                <a href="{{ route('admin.categories.edit', $child) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">Sửa</a>
                                                <form action="{{ route('admin.categories.destroy', $child) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Xóa</button>
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
            <div class="pagination mt-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</body>
</html>
