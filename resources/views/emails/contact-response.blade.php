<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phản hồi từ Admin</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #2c3e50;">Phản hồi từ Admin</h2>
    <p>Cảm ơn bạn đã liên hệ với chúng tôi về chủ đề: <strong>{{ $contact->subject }}</strong>.</p>
    <p>Thông tin của bạn:</p>
    <ul style="list-style-type: none; padding: 0;">
        <li><strong>Tên:</strong> {{ $contact->name }}</li>
        <li><strong>Email:</strong> {{ $contact->email }}</li>
        <li><strong>Tin nhắn:</strong> {{ $contact->message }}</li>
    </ul>
    <p>Phản hồi từ admin (gửi vào {{ now()->format('d/m/Y H:i') }}):</p>
    <p style="background-color: #f5f6fa; padding: 10px; border-radius: 5px;">{{ $response }}</p>
    <p style="color: #7f8c8d;">Nếu bạn có thêm câu hỏi, vui lòng liên hệ lại.</p>
</body>
</html>
