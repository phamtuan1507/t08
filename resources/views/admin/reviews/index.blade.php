@extends('layouts.app')

@section('title', 'Quản lý Đánh giá')

{{-- @section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Quản lý Đánh giá</h2>
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4 shadow">
                {{ session('success') }}
            </div>
        @endif
        @if ($reviews->isEmpty())
            <p class="text-gray-600">Không có đánh giá nào.</p>
        @else
            <div class="space-y-4">
                @foreach ($reviews as $review)
                    <div class="bg-gray-100 p-4 rounded-lg shadow">
                        <p><strong>Sản phẩm:</strong> {{ $review->product->name }}</p>
                        <p><strong>Người dùng:</strong> {{ $review->user->name }}</p>
                        <p><strong>Đánh giá:</strong> {{ $review->rating }} sao</p>
                        <p><strong>Nhận xét:</strong> {{ $review->comment }}</p>
                        <p><strong>Trạng thái:</strong> {{ $review->approved ? 'Đã phê duyệt' : 'Chờ phê duyệt' }}</p>
                        <p><strong>Ngày gửi:</strong> {{ $review->created_at->format('d/m/Y H:i') }}</p>
                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="inline">
                            @csrf
                            @method('POST')
                            <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded {{ $review->approved ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $review->approved ? 'disabled' : '' }}>Phê duyệt</button>
                        </form>
                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded ml-2">Xóa</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection --}}
@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Quản lý đánh giá sản phẩm</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($reviews->isEmpty())
            <p class="text-gray-600">Không có đánh giá nào để hiển thị.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Sản phẩm</th>
                            <th class="py-3 px-6 text-left">Người dùng</th>
                            <th class="py-3 px-6 text-left">Điểm số</th>
                            <th class="py-3 px-6 text-left">Bình luận</th>
                            <th class="py-3 px-6 text-left">Trạng thái</th>
                            <th class="py-3 px-6 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm">
                        @foreach ($reviews as $review)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left">{{ $review->product->name }}</td>
                                <td class="py-3 px-6 text-left">{{ $review->user->name }}</td>
                                <td class="py-3 px-6 text-left">{{ $review->rating }} sao</td>
                                <td class="py-3 px-6 text-left">{{ $review->comment }}</td>
                                <td class="py-3 px-6 text-left">
                                    <span
                                        class="inline-block px-3 py-1 rounded-full text-sm {{ $review->approved ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                        {{ $review->approved ? 'Đã phê duyệt' : 'Chưa phê duyệt' }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    @if (!$review->approved)
                                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Phê
                                                duyệt</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                        class="inline-block ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <div class="mt-6">
                {{ $reviews->links('pagination::tailwind') }}
            </div>
        @endif
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Quay lại Dashboard</a>
    </div>
@endsection
