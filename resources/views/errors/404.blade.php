<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-blue-600">404</h1>
        <p class="text-2xl mt-4 text-gray-700">Oops! Page not found.</p>
        <p class="text-lg text-gray-500 mt-2">You don't have permission to view this page.</p>
        <a href="{{ url('/') }}"
            class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
            Go Home
        </a>
    </div>
</body>

</html>
