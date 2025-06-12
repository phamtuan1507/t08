@extends('layouts.app')

@section('title', 'Liên hệ')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Liên hệ</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4 shadow">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4 shadow">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-gray-700">Tên</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>
            <div>
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>
            <div>
                <label for="subject" class="block text-gray-700">Chủ đề</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>
            <div>
                <label for="message" class="block text-gray-700">Tin nhắn</label>
                <textarea name="message" id="message" rows="4"
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required>{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Gửi yêu
                cầu</button>
        </form>
    </div>
@endsection
