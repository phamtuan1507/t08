<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Not Found</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-red-600">View Not Found</h1>
        <p class="text-gray-600">The view 'view.name' was not found. Please check the controller or route configuration.</p>
        <a href="{{ route('home') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg mt-4 inline-block">Quay lại trang chủ</a>
    </div>
</body>
</html>
