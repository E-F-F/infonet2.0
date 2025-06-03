<!doctype html>
<html>

<head>
    @include('includes.head')
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="w-full">
        @include('includes.header') {{-- This will now include the new header.blade.php --}}
    </header>

    <div class="flex flex-1"> {{-- This div flex-1 ensures it takes remaining vertical space --}}
        @include('includes.sidebar')

        <main class="flex-1 p-6"> {{-- Main content area --}}
            @yield('content')
        </main>
    </div>

    <footer class="w-full">
        @include('includes.footer')
    </footer>

    {{-- Flowbite JS - ensure compatibility with your Flowbite CSS version --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    {{-- Consider putting any custom JS files here --}}
</body>

</html>