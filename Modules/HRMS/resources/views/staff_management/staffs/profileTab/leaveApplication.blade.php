<div class="max-w-6xl mx-auto space-y-6 p-4">
    <!-- Leave Application History Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 pb-3 flex justify-between items-center border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                Leave Application History
            </h2>
            <div class="relative">
                <input type="text" placeholder="Search applications..." class="pl-8 pr-4 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-2.5 top-2.5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        
        <div class="p-6 space-y-4">
            <!-- Filter Controls -->
            <div class="flex flex-wrap gap-3 mb-4">
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-blue-500 focus:border-blue-500">
                    <option>All Leave Types</option>
                    <option>Annual Leave</option>
                    <option>Medical Leave</option>
                    <option>Unpaid Leave</option>
                </select>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-blue-500 focus:border-blue-500">
                    <option>All Statuses</option>
                    <option>Approved</option>
                    <option>Pending</option>
                    <option>Rejected</option>
                </select>
                <div class="relative">
                    <input type="text" placeholder="Date range" class="pl-8 pr-4 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-2.5 top-2.5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <button class="text-sm text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg">
                    Apply Filters
                </button>
            </div>
            
            <!-- Leave Application Cards -->
            <div class="space-y-4">
                <!-- Single Leave Application Card -->
                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-sm transition-shadow">
                    <div class="bg-gray-50 px-4 py-3 flex justify-between items-center border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-gray-800">#55</span>
                            <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">Approved</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">Created: 03 May 2023</span>
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                View
                            </button>
                        </div>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</p>
                                <p class="text-gray-800">Unpaid Leave</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</p>
                                <p class="text-gray-800">25 Apr 2023 (1 day)</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</p>
                                <p class="text-gray-800">WA</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Description</p>
                                <p class="text-gray-800">Added 8 days after leave</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Approving Officer</p>
                                <p class="text-gray-800">NG SIEW LING LING</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</p>
                                <div class="grid grid-cols-3 gap-2 text-sm">
                                    <div>
                                        <p class="text-gray-500">Entitled</p>
                                        <p class="text-gray-800">-</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Taken</p>
                                        <p class="text-gray-800">-</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Remaining</p>
                                        <p class="text-gray-800 font-medium">-</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</p>
                                <p class="text-gray-800">03 May 2023 12:37</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Empty State (when no applications exist) -->
                <!--
                <div class="bg-gray-50 rounded-lg p-8 text-center border-2 border-dashed border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-500 mt-2">No leave applications found</p>
                    <button class="mt-4 text-sm text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg inline-flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Apply for Leave
                    </button>
                </div>
                -->
            </div>
            
            <!-- Pagination -->
            <div class="flex items-center justify-between mt-6">
                <div class="text-sm text-gray-500">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">1</span> of <span class="font-medium">1</span> entries
                </div>
                <div class="flex gap-1">
                    <button class="px-3 py-1 border border-gray-300 rounded text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50" disabled>
                        Previous
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded text-sm font-medium bg-blue-50 text-blue-600 border-blue-300">
                        1
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50" disabled>
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>