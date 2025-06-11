<x-hrms::layouts.master>
    <div x-data="{ tab: 'personal' }">
        <div class="bg-white shadow-lg rounded-xl p-6 w-full">
            <h1 class="text-2xl font-semibold text-gray-800 text-center mb-6">Staff Profile Details</h1>

            @if ($staff)
                <!-- Tabs -->
                <div class="flex justify-center mb-6 border-b border-gray-200">
                    <button @click="tab = 'personal'"
                        :class="tab === 'personal' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'"
                        class="px-4 py-2 text-sm font-medium focus:outline-none">
                        Personal Info
                    </button>
                    <button @click="tab = 'employment'"
                        :class="tab === 'employment' ? 'border-b-2 border-green-600 text-green-600' : 'text-gray-600'"
                        class="ml-6 px-4 py-2 text-sm font-medium focus:outline-none">
                        Employment Info
                    </button>
                </div>

                <!-- Personal Information -->
                <div x-show="tab === 'personal'" x-transition>
                    <div class="space-y-4 text-sm text-gray-700">
                        <h2 class="text-base font-medium text-blue-700 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Personal Information
                        </h2>
                        <div>
                            <label class="block font-medium text-gray-600">Full Name</label>
                            <div class="mt-1 px-3 py-2 border border-gray-300 bg-white rounded-md">
                                {{ optional($staff->personal)->fullName ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-600">Gender</label>
                            <div class="mt-1 px-3 py-2 border border-gray-300 bg-white rounded-md">
                                {{ optional($staff->personal)->gender ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-600">Profile Image</label>
                            <div class="mt-1">
                                @if (optional($staff->personal)->image_url)
                                    <img src="{{ $staff->personal->image_url }}" alt="Profile"
                                        class="w-20 h-20 rounded-full object-cover border border-gray-300"
                                        onerror="this.onerror=null;this.src='https://placehold.co/80x80/cccccc/ffffff?text=No+Image';">
                                @else
                                    <span class="text-gray-500">No image available</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div x-show="tab === 'employment'" x-transition>
                    <div class="space-y-4 text-sm text-gray-700">
                        <h2 class="text-base font-medium text-green-700 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 13.255A23.55 23.55 0 0112 15c-1.638 0-3.23-.28-4.755-.845l-4.214 3.738A1 1 0 003 20h18a1 1 0 00.993-1.127l-.272-2.712zm-9 3.245a4 4 0 110-8 4 4 0 010 8z" />
                            </svg>
                            Employment Details
                        </h2>
                        <div>
                            <label class="block font-medium text-gray-600">Branch</label>
                            <div class="mt-1 px-3 py-2 border border-gray-300 bg-white rounded-md">
                                {{ optional(optional($staff->employment)->branch)->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-600">Designation</label>
                            <div class="mt-1 px-3 py-2 border border-gray-300 bg-white rounded-md">
                                {{ optional(optional($staff->employment)->designation)->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-600">Leave Rank</label>
                            <div class="mt-1 px-3 py-2 border border-gray-300 bg-white rounded-md">
                                {{ optional(optional($staff->employment)->leaveRank)->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-600">Pay Group</label>
                            <div class="mt-1 px-3 py-2 border border-gray-300 bg-white rounded-md">
                                {{ optional(optional($staff->employment)->payGroup)->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-600">Employee Number</label>
                            <div class="mt-1 px-3 py-2 border border-gray-300 bg-white rounded-md">
                                {{ optional($staff->employment)->employee_number ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-600">Joining Date</label>
                            <div class="mt-1 px-3 py-2 border border-gray-300 bg-white rounded-md">
                                {{ optional($staff->employment)->joining_date ? \Carbon\Carbon::parse($staff->employment->joining_date)->format('M d, Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center text-gray-600 py-8">
                    <p class="text-base font-semibold">No staff member found with this ID.</p>
                    <p class="mt-1 text-sm">Please check the ID and try again.</p>
                </div>
            @endif


        </div>
    </div>
</x-hrms::layouts.master>
