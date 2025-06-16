<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết danh mục</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-4 py-8">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Chi tiết danh mục</h1>
            <div class="flex space-x-4">
                <a href="{{ route('admin.categories.edit', $category) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition">Sửa</a>
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Quay lại danh sách</a>
            </div>
        </header>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $category->name }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-600 mb-2">ID: {{ $category->id }}</p>
                    @if ($category->image)
                        <p class="text-gray-600 mb-2">Ảnh:</p>
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                            class="w-48 h-48 object-cover mb-4">
                    @endif
                    @if ($category->parent)
                        <p class="text-gray-600 mb-2">Danh mục cha: {{ $category->parent->name }}</p>
                    @else
                        <p class="text-gray-600 mb-2">Danh mục cha: Không có</p>
                    @endif
                </div>
                <div>
                    @if ($category->children->count())
                        <p class="text-gray-600 mb-2">Danh mục con:</p>
                        <ul class="list-disc pl-5">
                            @foreach ($category->children as $child)
                                <li>{{ $child->name }} (ID: {{ $child->id }})</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-600 mb-2">Danh mục con: Không có</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
