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
            <a href="{{ route('admin.categories.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Quay lại danh sách</a>
        </header>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 shadow">
                {{ session('error') }}
            </div>
        @endif
        <div class="bg-white rounded-lg shadow p-6 max-w-lg mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Sửa Banner</h2>
            <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="image" class="block text-gray-700 font-medium">Hình ảnh (để trống nếu không thay
                        đổi)</label>
                    <input type="file" name="image" id="image"
                        class="w-full p-2 border rounded-md @error('image') border-red-600 @enderror">
                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->alt_text }}"
                        class="mt-2 h-32 w-auto">
                    @error('image')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="alt_text" class="block text-gray-700 font-medium">Alt Text</label>
                    <input type="text" name="alt_text" id="alt_text"
                        value="{{ old('alt_text', $banner->alt_text) }}"
                        class="w-full p-2 border rounded-md @error('alt_text') border-red-600 @enderror">
                    @error('alt_text')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="link" class="block text-gray-700 font-medium">Link (tùy chọn)</label>
                    <input type="url" name="link" id="link" value="{{ old('link', $banner->link) }}"
                        class="w-full p-2 border rounded-md @error('link') border-red-600 @enderror">
                    @error('link')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="order" class="block text-gray-700 font-medium">Thứ tự</label>
                    <input type="number" name="order" id="order" value="{{ old('order', $banner->order) }}"
                        class="w-full p-2 border rounded-md @error('order') border-red-600 @enderror">
                    @error('order')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex space-x-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Cập
                        nhật</button>
                    <a href="{{ route('admin.banners.index') }}"
                        class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">Hủy</a>
                </div>
            </form>
        </div>
</body>

</html>
