<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Category;

class UserController extends Controller
{
    public function dashboard()
    {
        return view('user.dashboard');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.product')->paginate(10);
        return view('user.orders.index', compact('orders'));
    }

    public function profile()
    {
        $categories = Category::all();
        $user = Auth::user();
        Log::info('Rendering user profile', ['view' => 'user.profile', 'user_id' => $user->id]);
        return view('user.profile', compact('user','categories'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            $data = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'address' => $request->input('address'),
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->input('password'));
            }

            $user->update($data);

            Log::info('User profile updated', ['user_id' => $user->id]);
            return redirect()->route('profile.index')->with('success', 'Hồ sơ đã được cập nhật.');
        } catch (\Exception $e) {
            Log::error('Error updating user profile', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra. Vui lòng thử lại.');
        }
    }
}
