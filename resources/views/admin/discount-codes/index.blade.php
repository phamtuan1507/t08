@extends('layouts.app')

@section('content')
    <h1>Quản lý mã giảm giá</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('admin.discount-codes.create') }}" class="btn btn-primary mb-3">Thêm mới</a>
    <table class="table">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Phần trăm giảm</th>
                <th>Trạng thái</th>
                <th>Người dùng</th>
                <th>Hết hạn</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($discountCodes as $code)
                <tr>
                    <td>{{ $code->code }}</td>
                    <td>{{ $code->discount_percentage }}%</td>
                    <td>{{ $code->is_active ? 'Hoạt động' : 'Tắt' }}</td>
                    <td>{{ $code->user ? $code->user->name : 'Công khai' }}</td>
                    <td>{{ $code->expires_at }}</td>
                    <td>
                        <a href="{{ route('admin.discount-codes.edit', $code->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('admin.discount-codes.destroy', $code->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
