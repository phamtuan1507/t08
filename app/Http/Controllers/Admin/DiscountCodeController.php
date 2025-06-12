<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Models\User;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index()
    {
        $discountCodes = DiscountCode::with('user')->get();
        return view('admin.discount-codes.index', compact('discountCodes'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.discount-codes.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:discount_codes|alpha_dash',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'required|boolean',
            'user_id' => 'nullable|exists:users,id',
            'expires_at' => 'nullable|date',
        ]);

        DiscountCode::create($request->all());
        return redirect()->route('admin.discount-codes.index')->with('success', 'Mã giảm giá đã được tạo.');
    }

    public function edit(DiscountCode $discountCode)
    {
        $users = User::all();
        return view('admin.discount-codes.edit', compact('discountCode', 'users'));
    }

    public function update(Request $request, DiscountCode $discountCode)
    {
        $request->validate([
            'code' => 'required|alpha_dash|unique:discount_codes,code,' . $discountCode->id,
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'required|boolean',
            'user_id' => 'nullable|exists:users,id',
            'expires_at' => 'nullable|date',
        ]);

        $discountCode->update($request->all());
        return redirect()->route('admin.discount-codes.index')->with('success', 'Mã giảm giá đã được cập nhật.');
    }

    public function destroy(DiscountCode $discountCode)
    {
        $discountCode->delete();
        return redirect()->route('admin.discount-codes.index')->with('success', 'Mã giảm giá đã được xóa.');
    }
}
