<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
        <p>Welcome, {{ Auth::user()->name }}!</p>
        <a href="{{ route('products.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Manage Products</a>
        <a href="{{ route('categories.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Manage Categories</a>
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2">Logout</button>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</html>
