<nav class="bg-blue-600 p-4 shadow-md text-white flex justify-between items-center">
    <div class="text-2xl font-bold">
        <a href="#" class="text-white hover:text-blue-100">Infonet <strong class="font-normal">V</strong>2.0</a>
    </div>
    <div class="flex items-center space-x-4">
        {{-- Notification Bell Icon --}}
        <a href="#"
            class="text-white hover:text-blue-100 p-2 rounded-full hover:bg-blue-700 transition-colors duration-200">
            <i class="fas fa-bell text-xl"></i> {{-- Font Awesome Bell Icon --}}
        </a>
        <div class="relative">
            <button id="userMenuButton" class="flex items-center text-white focus:outline-none">
                {{-- User Avatar (random online picture) --}}
                <img class="w-8 h-8 rounded-full border-2 border-white object-cover"
                    src="https://picsum.photos/80/80?random={{ rand(1, 1000) }}" {{-- Random image from Picsum --}}
                    alt="User Avatar">
                <span class="ml-2 font-medium hidden md:inline">John Doe</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 hidden">
                <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profile</a>
                <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Settings</a>
                <a href="#" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Logout</a>
            </div>
        </div>
    </div>
</nav>

{{-- Simple JavaScript for dropdown toggle (you might use Flowbite's dropdown or Alpine.js) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenu = document.getElementById('userMenu');

        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', function() {
                userMenu.classList.toggle('hidden');
            });

            // Close dropdown if clicked outside
            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }
    });
</script>
