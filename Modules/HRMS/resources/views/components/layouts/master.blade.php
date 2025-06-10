<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>HRMS - {{ config('app.name', 'Infonet 2.0') }}</title>

    <meta name="description" content="{{ $description ?? 'HRMS Module for managing human resources.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'hrms, human resources, staff, leave' }}">
    <meta name="author" content="{{ $author ?? 'Your Company Name' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])


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

<body class="min-h-screen flex flex-col">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col">
        <!-- Topbar -->
        <!-- <header class="bg-white shadow-sm z-30">
            <div class="max-w-full mx-auto py-3 px-4 sm:px-6 lg:px-8 flex justify-between items-center relative">
                Mobile menu button
                <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden p-1.5 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 rounded-md transition duration-150 ease-in-out">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                HRMS Title / Logo
                <a href="{{ route('hrms.leave_ranks.index') }}"
                    class="text-xl font-extrabold text-blue-700 tracking-tight flex items-center gap-1.5">
                    <svg class="h-7 w-7 text-blue-500" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z">
                        </path>
                    </svg>
                    HRMS - Infonet v2.0
                </a>

                User/Auth Links (Right side)
                <div class="flex items-center space-x-3 text-sm">
                </div>
            </div>
        </header> -->
        <div class="navbar bg-base-100 shadow-md fixed">
            <div class="navbar-start">
                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /> </svg>
                    </div>
                    <ul
                        tabindex="0"
                        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                        <li><a>Home</a></li>
                        <li><a>Profile</a></li>
                    </ul>
                </div>
            </div>
            <div class="navbar-center">
                <a class="btn btn-ghost text-sm">HRMS - Infonet v2.0</a>
            </div>
            <div class="navbar-end">
                <button class="btn btn-ghost btn-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </button>
                <button class="btn btn-ghost btn-circle">
                <div class="indicator">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    <div class="badge badge-xs badge-warning indicator-item">12</div>
                </div>
                </button>
            </div>
        </div>

        <!-- Main Content Area with Sidebar -->
        <div class="flex flex-1">
            <!-- Sidebar -->
            <aside id="sidebar"
                class="fixed inset-y-0 bg-white shadow-lg rounded-xl w-60 md:relative md:translate-x-0 transform -translate-x-full transition-transform duration-300 ease-in-out z-20 mt-18 ml-2 mb-2"
                :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen && !isMobile }"
                {{-- isMobile check requires Alpine.js --}}>
                <div class="p-2 text-center">
                    <h2 class="text-sm font-bold text-gray-800 mb-2">Navigation</h2>
                    <nav>
                        <ul class="menu bg-base-200 rounded-box w-56">
                            <li class="text-xs my-1">
                                <a>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Home
                                </a>
                            </li>
                            <li class="text-xs my-1">
                                @if (Route::is('hrms.staff.index'))
                                <details open>
                                @elseif (Route::is('hrms.staff.create'))
                                <details open>
                                @else
                                <details>
                                @endif
                                    <summary>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                        Staff Management
                                    </summary>
                                    <ul>
                                        <li class="text-xs my-1">
                                            @if (Route::is('hrms.staff.index'))
                                            <a href="{{ route('hrms.staff.index') }}" class="menu-active">
                                            @else
                                            <a href="{{ route('hrms.staff.index') }}">
                                            @endif                                            
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                                </svg>
                                                Staff List
                                            </a>
                                        </li>
                                        <li class="text-xs my-1">
                                            @if (Route::is('hrms.staff.create'))
                                            <a href="{{ route('hrms.staff.create') }}" class="menu-active">
                                            @else
                                            <a href="{{ route('hrms.staff.create') }}">
                                            @endif
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                                </svg>
                                                Add New Staff
                                            </a>
                                        </li>
                                    </ul>
                                </details>
                            </li>
                            <li class="text-xs my-1">
                                @if (Route::is('hrms.leave_ranks.index'))
                                <details open>
                                @elseif (Route::is('hrms.leave_ranks.create'))
                                <details open>
                                @else
                                <details>
                                @endif
                                    <summary>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                        </svg>                                        
                                        Leave Management
                                    </summary>
                                    <ul>
                                        <li class="text-xs my-1">
                                            @if (Route::is('hrms.leave_ranks.index'))
                                            <a href="{{ route('hrms.leave_ranks.index') }}" class="menu-active">
                                            @else
                                            <a href="{{ route('hrms.leave_ranks.index') }}">
                                            @endif
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Leave Ranks List
                                            </a>
                                        </li>
                                    </ul>
                                </details>
                            </li>
                            <li class="text-xs my-1">
                                <a>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Report
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>          

            <!-- Overlay for mobile sidebar -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                class="fixed inset-0 bg-black bg-opacity-50 z-10 md:hidden">
            </div>

            <!-- Main Content Area -->
            <main id="main-content-area"
                class="flex-1 py-6 px-4 transition-all duration-300 ease-in-out mt-12">
                {{ $slot }}
            </main>
        </div>

        {{-- Footer removed --}}

    </div>

</body>

</html>
