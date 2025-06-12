<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Lịch sử đơn hàng</h1>
        <table class="w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="p-2">Mã đơn hàng</th>
                    <th class="p-2">Tổng tiền</th>
                    <th class="p-2">Trạng thái</th>
                    <th class="p-2">Phương thức</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td class="p-2">#{{ $order->id }}</td>
                        <td class="p-2">${{ number_format($order->total, 2) }}</td>
                        <td class="p-2">{{ $order->status }}</td>
                        <td class="p-2">{{ $order->payment_method === 'cod' ? 'COD' : 'Chuyển khoản' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-2 text-center">Chưa có đơn hàng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $orders->links() }}</div>
        <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded mt-4 inline-block">Quay lại Dashboard</a>
    </div>
</body>
</html>
