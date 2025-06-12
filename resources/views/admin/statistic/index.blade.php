@extends('layouts.app')

@section('title', __('messages.admin_dashboard'))
@vite(['resources/js/app.js'])
@section('content')
    <div class="container mx-auto p-6">
        <h1>Thống kê dữ liệu</h1>
        <div style="width:75%;">
            <x-chartjs-component :chart="$chart" />
        </div>
    </div>


@endsection
