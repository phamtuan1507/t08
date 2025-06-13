<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request,Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'product_id' => $product->id, // Đảm bảo chỉ lấy ID
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'approved' => false, // Chờ admin phê duyệt
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được gửi và đang chờ phê duyệt.');
    }

    public function adminIndex()
    {
        $reviews = Review::with(['product', 'user'])->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->update(['approved' => true]);

            Log::info('Review approved', ['review_id' => $id, 'admin_id' => Auth::id()]);
            return redirect()->back()->with('success', 'Đánh giá đã được phê duyệt.');
        } catch (\Exception $e) {
            Log::error('Error approving review', ['error' => $e->getMessage(), 'review_id' => $id]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi phê duyệt đánh giá.');
        }
    }

    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            Log::info('Review deleted', ['review_id' => $id, 'admin_id' => Auth::id()]);
            return redirect()->back()->with('success', 'Đánh giá đã được xóa.');
        } catch (\Exception $e) {
            Log::error('Error deleting review', ['error' => $e->getMessage(), 'review_id' => $id]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa đánh giá.');
        }
    }
}
