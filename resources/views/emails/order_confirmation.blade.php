<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận đơn hàng</title>
</head>
<body>
    <h1>Xác nhận đơn hàng #{{ $order->id }}</h1>
    <p>Cảm ơn bạn đã đặt hàng! Dưới đây là chi tiết đơn hàng của bạn:</p>

    <h2>Thông tin đơn hàng</h2>
    <p><strong>Tổng tiền:</strong> ${{ number_format($order->total, 2) }}</p>
    @if($order->discountCode)
        <p><strong>Mã giảm giá:</strong> {{ $order->discountCode->code }} ({{ $order->discountCode->discount_percentage }}%)</p>
    @endif
    <p><strong>Địa chỉ giao hàng:</strong> {{ $order->address }}</p>
    <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method === 'cash_on_delivery' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản ngân hàng' }}</p>

    <h2>Chi tiết sản phẩm</h2>
    <ul>
        @foreach($order->items as $item)
            <li>{{ $item->product->name }} x {{ $item->quantity }} - ${{ number_format($item->price * $item->quantity, 2) }}</li>
        @endforeach
    </ul>

    <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email hoặc số điện thoại.</p>
</body>
</html>
