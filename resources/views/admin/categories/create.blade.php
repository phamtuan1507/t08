<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm danh mục</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-4 py-8">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Thêm danh mục</h1>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Quay lại danh sách</a>
        </header>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Tên danh mục</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full p-2 border rounded @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-gray-700">Ảnh danh mục</label>
                    <input type="file" name="image" id="image" class="w-full p-2 border rounded @error('image') border-red-500 @endif">
                    @error('image')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Lưu</button>
            </form>
        </div>
    </div>
</body>
</html>
