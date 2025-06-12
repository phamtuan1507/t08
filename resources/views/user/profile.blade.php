@extends('layouts.app')

@section('title', 'Hồ sơ người dùng')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Thông tin cá nhân</h2>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium">Tên</label>
                <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" class="w-full p-2 border rounded-md @error('name') border-red-600 @enderror">
                @error('name')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" class="w-full p-2 border rounded-md @error('email') border-red-600 @enderror">
                @error('email')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="address" class="block text-gray-700 font-medium">Địa chỉ</label>
                <input type="text" name="address" id="address" value="{{ old('address', Auth::user()->address) }}" class="w-full p-2 border rounded-md @error('address') border-red-600 @enderror">
                @error('address')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium">Mật khẩu mới (để trống nếu không đổi)</label>
                <input type="password" name="password" id="password" class="w-full p-2 border rounded-md @error('password') border-red-600 @enderror">
                @error('password')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 font-medium">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full p-2 border rounded-md">
            </div>
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Cập nhật</button>
                <a href="{{ route('home') }}" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">Hủy</a>
            </div>
        </form>
    </div>
@endsection
