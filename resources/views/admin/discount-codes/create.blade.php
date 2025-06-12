@extends('layouts.app')

@section('content')
    <h1>Thêm mã giảm giá</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.discount-codes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Mã giảm giá</label>
            <input type="text" name="code" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Phần trăm giảm (%)</label>
            <input type="number" name="discount_percentage" class="form-control" step="0.01" min="0" max="100" required>
        </div>
        <div class="form-group">
            <label>Trạng thái</label>
            <select name="is_active" class="form-control">
                <option value="1">Hoạt động</option>
                <option value="0">Tắt</option>
            </select>
        </div>
        <div class="form-group">
            <label>Người dùng (tuỳ chọn)</label>
            <select name="user_id" class="form-control">
                <option value="">Công khai</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Hết hạn (tuỳ chọn)</label>
            <input type="date" name="expires_at" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
    </form>
@endsection
