<x-hrms::layouts.master>
    <div x-data="{ activeTab: 'personal' }" class="max-w-7xl mx-auto bg-white rounded-sm p-6 shadow-lg">
        <h2 class="text-xl font-bold mb-6">Employee Profile - {{ optional($staff->personal)->fullName ?? 'N/A' }}</h2>

        <!-- Tabs Navigation -->
        <div class="inline-flex rounded-lg bg-blue-100 p-1">
            <template
                x-for="(tab, index) in [
                    { id: 'personal', label: 'Personal' },
                    { id: 'contact', label: 'Contact' },
                    { id: 'organization', label: 'Organization' },
                    { id: 'leave-history', label: 'Leave History' },
                    { id: 'leave-adjustments', label: 'Leave Adjustments' },
                    { id: 'attendance', label: 'Attendance History' },
                    { id: 'qualifications', label: 'Qualifications' }
                ]"
                            :key="tab.id">
                            <button @click="activeTab = tab.id"
                                :class="{
                                    'text-blue-600 bg-white shadow-sm': activeTab === tab.id,
                                    'text-gray-600 hover:text-gray-900': activeTab !== tab.id
                                }"
                                class="px-4 py-2 text-sm font-medium rounded-md focus:outline-none" x-text="tab.label"></button>
            </template>
        </div>

        <!-- Tabs Content -->
        <div id="tab-content" class="mt-6">
            <!-- Personal Tab -->
            <div x-show="activeTab === 'personal'" class="tab-pane">
                @include('hrms::staff_management.staffs.profileTab.personal')
            </div>

            <!-- Contact Tab -->
            <div x-show="activeTab === 'contact'" class="tab-pane">
                @include('hrms::staff_management.staffs.profileTab.contact')
            </div>

            <!-- Other Tabs (optional placeholders) -->
            <div x-show="activeTab === 'organization'" class="tab-pane">
                @include('hrms::staff_management.staffs.profileTab.organization')
            </div>

            <div x-show="activeTab === 'leave-history'" class="tab-pane">
                @include('hrms::staff_management.staffs.profileTab.leaveApplication')
            </div>

            <div x-show="activeTab === 'leave-adjustments'" class="tab-pane">
                <div class="p-4 border rounded bg-white">Leave Adjustments tab content</div>
            </div>

            <div x-show="activeTab === 'attendance'" class="tab-pane">
                <div class="p-4 border rounded bg-white">Attendance History tab content</div>
            </div>

            <div x-show="activeTab === 'qualifications'" class="tab-pane">
                <div class="p-4 border rounded bg-white">Qualifications tab content</div>
            </div>
        </div>
    </div>
</x-hrms::layouts.master>
