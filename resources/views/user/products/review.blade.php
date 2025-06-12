<div class="mt-6">
    <h3 class="text-lg font-semibold">Đánh giá sản phẩm</h3>
    <form action="{{ route('products.review', $product->id) }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-4">
            <label for="rating" class="block text-gray-700">Đánh giá (1-5 sao):</label>
            <select name="rating" id="rating" class="w-full p-2 border rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-blue-500" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="comment" class="block text-gray-700">Nhận xét:</label>
            <textarea name="comment" id="comment" class="w-full p-2 border rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Gửi đánh giá</button>
    </form>

    <!-- Hiển thị đánh giá đã phê duyệt -->
    <h3 class="text-lg font-semibold mt-6">Đánh giá từ khách hàng</h3>
    @forelse($product->reviews->where('approved', true) as $review)
        <div class="border p-4 mb-2 rounded-lg mt-2">
            <p><strong>{{ $review->user->name }}</strong> - {{ $review->rating }} sao</p>
            <p>{{ $review->comment }}</p>
            <small>{{ $review->created_at->format('d/m/Y') }}</small>
        </div>
    @empty
        <p class="text-gray-600">Chưa có đánh giá nào.</p>
    @endforelse
</div>
