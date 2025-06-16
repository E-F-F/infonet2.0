<div class="max-w-6xl mx-auto space-y-6 p-4">

    <!-- Personal Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 pb-2 flex justify-between items-center border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800">Personal Details</h2>
            <span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded-full">Basic Information</span>
        </div>
        <div class="p-6 flex flex-col md:flex-row gap-6">
            <div class="flex-shrink-0">
                <div class="relative">
                    <img src="https://images.ctfassets.net/h6goo9gw1hh6/2sNZtFAWOdP1lmQ33VwRN3/24e953b920a9cd0ff2e1d587742a2472/1-intro-photo-final.jpg?w=1200&h=992&fl=progressive&q=70&fm=jpg"
                        alt="Profile Picture" class="w-32 h-32 rounded-xl object-cover border-4 border-white shadow-md">
                    <div
                        class="absolute -bottom-2 -right-2 bg-blue-500 text-white text-xs font-medium px-2 py-1 rounded-full">
                        Active
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</p>
                        <p class="text-gray-800 font-medium">{{ optional($staff->personal)->fullName ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Birth</p>
                        <p class="text-gray-800">{{ optional($staff->personal)->dob ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</p>
                        <p class="text-gray-800">{{ optional($staff->personal)->gender ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Marital Status</p>
                        <p class="text-gray-800">{{ optional($staff->personal)->marital_status ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Group</p>
                        <p class="text-gray-800">{{ optional($staff->personal)->blood_group ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Employee ID</p>
                        <p class="text-gray-800">{{ optional($staff->employment)->employee_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid of Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Identification Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 pb-3 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                    Identification
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500">IC No</p>
                        <p class="text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Passport No</p>
                        <p class="text-gray-800">-</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500">EPF No</p>
                        <p class="text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">SOCSO No</p>
                        <p class="text-gray-800">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Details Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 pb-3 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                            clip-rule="evenodd" />
                    </svg>
                    Bank Details
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-xs font-medium text-gray-500">Bank Account</p>
                    <p class="text-gray-800">-</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500">Bank Name</p>
                        <p class="text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Bank Branch</p>
                        <p class="text-gray-800">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Family Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 pb-3 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path
                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z" />
                </svg>
                Family Details
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-4">
                    <h3 class="font-medium text-gray-700 border-b pb-2">Parents</h3>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Father's Name</p>
                        <p class="text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Father's DOB</p>
                        <p class="text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Mother's Name</p>
                        <p class="text-gray-800">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Mother's DOB</p>
                        <p class="text-gray-800">-</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <h3 class="font-medium text-gray-700 border-b pb-2">Spouse</h3>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Name</p>
                        <p class="text-gray-800">NUR SUFAZILAH BTE DAIDEY</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Date of Birth</p>
                        <p class="text-gray-800">1998-05-14</p>
                    </div>
                </div>
            </div>

            <h3 class="flex justify-between font-medium text-gray-700 border-b pb-2 mb-4">Children
                <button class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Add Child
                </button>
            </h3>

            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <p class="text-gray-500 mt-2">No children data available</p>
            </div>
        </div>
    </div>

    <!-- Status Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Account Status</h2>
                    <p class="text-sm text-gray-500">Login ID: MOHDSAHMIZAMBINSA_2023-01-09</p>
                </div>
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    UNAUTHORIZED
                </span>
            </div>
        </div>
    </div>

</div>
