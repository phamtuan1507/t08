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
            <h1 class="text-3xl font-bold text-gray-800">Thông báo liên hệ</h1>
            <div class="flex space-x-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Quay lại danh
                    sách</a>
            </div>
        </header>
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4 shadow">
                {{ session('success') }}
            </div>
        @endif
        @if ($contacts->isEmpty())
            <p class="text-gray-600">Không có yêu cầu liên hệ nào.</p>
        @else
            <div class="space-y-4">
                @foreach ($contacts as $contact)
                    <div class="bg-gray-100 p-4 rounded-lg shadow">
                        <h4 class="text-lg font-semibold text-gray-800">{{ $contact->subject }}</h4>
                        <p class="text-gray-600">Từ: {{ $contact->name }} <{{ $contact->email }}>
                        </p>
                        <p class="text-gray-600 mt-2">{{ $contact->message }}</p>
                        <p class="text-gray-500 text-sm mt-2">Gửi vào: {{ $contact->created_at->format('d/m/Y H:i') }}
                        </p>

                        <form action="{{ route('admin.send.response', $contact->id) }}" method="POST" class="mt-4">
                            @csrf
                            <textarea name="response" rows="3"
                                class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                                placeholder="Nhập phản hồi của bạn..." required></textarea>
                            <button type="submit"
                                class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Gửi phản
                                hồi</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>

</html>
