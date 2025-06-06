<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>HRMS Module - {{ config('app.name', 'Infonet 2.0') }}</title>

    <meta name="description" content="{{ $description ?? 'HRMS Module for managing human resources.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'hrms, human resources, staff, leave' }}">
    <meta name="author" content="{{ $author ?? 'Your Company Name' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', 'Figtree', sans-serif;
            /* Prioritize Inter, fallback to Figtree */
            background-color: #f3f4f6;
            /* Light gray background */
        }

        /* Custom styles for sidebar toggle on mobile */
        @media (max-width: 767px) {
            .sidebar-collapsed #sidebar {
                transform: translateX(-100%);
            }

            .sidebar-expanded #sidebar {
                transform: translateX(0%);
            }

            .sidebar-collapsed #main-content-area {
                margin-left: 0;
            }

            .sidebar-expanded #main-content-area {
                margin-left: 0;
                /* Sidebar will overlay content on mobile */
            }
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col">
        <!-- Topbar -->
        <header class="bg-white shadow-sm z-30">
            <div class="max-w-full mx-auto py-3 px-4 sm:px-6 lg:px-8 flex justify-between items-center relative">
                <!-- Mobile menu button -->
                <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden p-1.5 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 rounded-md transition duration-150 ease-in-out">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- HRMS Title / Logo -->
                <a href="{{ route('hrms.leave_ranks.index') }}"
                    class="text-xl font-extrabold text-blue-700 tracking-tight flex items-center gap-1.5">
                    <svg class="h-7 w-7 text-blue-500" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z">
                        </path>
                    </svg>
                    HRMS
                </a>

                <!-- User/Auth Links (Right side) -->
                <div class="flex items-center space-x-3 text-sm">
                </div>
            </div>
        </header>

        <!-- Main Content Area with Sidebar -->
        <div class="flex flex-1">
            <!-- Sidebar -->
            <aside id="sidebar"
                class="fixed inset-y-0 left-0 bg-white shadow-lg w-64 md:relative md:translate-x-0 transform -translate-x-full transition-transform duration-300 ease-in-out z-20"
                :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen && !isMobile }"
                {{-- isMobile check requires Alpine.js --}}>
                <div class="p-4">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Navigation</h2>
                    <nav>
                        <ul>
                            {{-- New Dropdown for HR Management --}}
                            <li x-data="{ open: false }" class="mb-2">
                                <a @click="open = !open" href="#"
                                    class="flex items-center p-2 text-gray-700 hover:bg-blue-100 hover:text-blue-700 rounded-md transition duration-200 ease-in-out justify-between cursor-pointer text-sm">
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.525.322 1.018.8 1.485 1.5M12 12a2 2 0 100-4 2 2 0 000 4z">
                                            </path>
                                        </svg>
                                        Staff Management
                                    </span>
                                    <svg class="h-3 w-3 transform transition-transform duration-200"
                                        :class="{ 'rotate-90': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                                <ul x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95" class="ml-5 mt-1 space-y-1">
                                    <li>
                                        <a href="{{ route('hrms.staff.index') }}"
                                            class="flex items-center p-2 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-md transition duration-200 ease-in-out text-sm">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM12 14v5m-4 0h8a2 2 0 002-2v-5a2 2 0 00-2-2H8a2 2 0 00-2 2v5z">
                                                </path>
                                            </svg>
                                            Staff
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li x-data="{ open: false }" class="mb-2">
                                <a @click="open = !open" href="#"
                                    class="flex items-center p-2 text-gray-700 hover:bg-blue-100 hover:text-blue-700 rounded-md transition duration-200 ease-in-out justify-between cursor-pointer text-sm">
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.525.322 1.018.8 1.485 1.5M12 12a2 2 0 100-4 2 2 0 000 4z">
                                            </path>
                                        </svg>
                                        Leave Management
                                    </span>
                                    <svg class="h-3 w-3 transform transition-transform duration-200"
                                        :class="{ 'rotate-90': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                                <ul x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95" class="ml-5 mt-1 space-y-1">
                                    <li>
                                        <a href="{{ route('hrms.leave_ranks.index') }}"
                                            class="flex items-center p-2 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-md transition duration-200 ease-in-out text-sm">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7v4a2 2 0 002 2h4a2 2 0 002-2V7m0 10a2 2 0 01-2 2H8a2 2 0 01-2-2m0 0V5a2 2 0 012-2h4a2 2 0 012 2v12m-4 2h.01">
                                                </path>
                                            </svg>
                                            Leave Ranks
                                        </a>
                                    </li>
                                    {{-- <li>
                                        <a href="{{ route('hrms.staff.create') }}"
                                            class="flex items-center p-2 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-md transition duration-200 ease-in-out text-sm">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM12 14v5m-4 0h8a2 2 0 002-2v-5a2 2 0 00-2-2H8a2 2 0 00-2 2v5z">
                                                </path>
                                            </svg>
                                            Add Staff
                                        </a>
                                    </li> --}}
                                </ul>
                            </li>

                            {{-- Dropdown for Reports (kept separate) --}}
                            <li x-data="{ open: false }" class="mb-2">
                                <a @click="open = !open" href="#"
                                    class="flex items-center p-2 text-gray-700 hover:bg-blue-100 hover:text-blue-700 rounded-md transition duration-200 ease-in-out justify-between cursor-pointer text-sm">
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v2m6-2a2 2 100-4m0 4a2 2 110-4m0 4v2">
                                            </path>
                                        </svg>
                                        Reports
                                    </span>
                                    <svg class="h-3 w-3 transform transition-transform duration-200"
                                        :class="{ 'rotate-90': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                                <ul x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95" class="ml-5 mt-1 space-y-1">
                                    <li>
                                        <a href="#"
                                            class="flex items-center p-2 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-md transition duration-200 ease-in-out text-sm">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-1.25-3M15 10V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v5m6 0h2a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2v-5a2 2 0 012-2h2">
                                                </path>
                                            </svg>
                                            Attendance
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#"
                                            class="flex items-center p-2 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-md transition duration-200 ease-in-out text-sm">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 17v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2a2 2 0 002-2zM12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v2m6-2a2 2 100-4m0 4a2 2 110-4m0 4v2">
                                                </path>
                                            </svg>
                                            Actual Reports
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

            <!-- Overlay for mobile sidebar -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                class="fixed inset-0 bg-black bg-opacity-50 z-10 md:hidden"></div>

            <!-- Main Content Area -->
            <main id="main-content-area"
                class="flex-1 py-6 px-4 sm:px-6 lg:px-8 transition-all duration-300 ease-in-out">
                {{ $slot }}
            </main>
        </div>

        {{-- Footer removed --}}

    </div>

    {{-- Alpine.js for sidebar toggle --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- Extra scripts section --}}
    @stack('scripts')
</body>

</html>
