<x-hrms::layouts.master>
    <div class="container p-4 max-w-3xl px-4">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">Create New Event</h1>
            <form id="eventForm" method="POST" action="{{ route('hrms.event.store') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-xs font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="title" name="title"
                            class="block w-full border-0 border-b-[0.5px] border-black focus:border-blue-500 focus:outline-none focus:ring-0 px-1 py-1 text-xs bg-transparent">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="eventType" class="block text-xs font-medium text-gray-700 mb-1">Event
                                Type</label>
                            <input type="text" id="eventType" name="hrms_event_type_id"
                                class="block w-full border-0 border-b-[0.5px] border-black focus:border-blue-500 focus:outline-none focus:ring-0 px-1 py-1 text-xs bg-transparent">
                        </div>

                        <div>
                            <label for="startDate" class="block text-xs font-medium text-gray-700 mb-1">Start
                                Date</label>
                            <input type="date" id="startDate" name="start_date"
                                class="block w-full border-0 border-b-[0.5px] border-black focus:border-blue-500 focus:outline-none focus:ring-0 px-1 py-1 text-xs bg-transparent">
                        </div>

                        <div>
                            <label for="endDate" class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="endDate" name="end_date"
                                class="block w-full border-0 border-b-[0.5px] border-black focus:border-blue-500 focus:outline-none focus:ring-0 px-1 py-1 text-xs bg-transparent">
                        </div>

                        <div>
                            <label for="eventCompany"
                                class="block text-xs font-medium text-gray-700 mb-1">Company</label>
                            <input type="text" id="eventCompany" name="event_company"
                                class="block w-full border-0 border-b-[0.5px] border-black focus:border-blue-500 focus:outline-none focus:ring-0 px-1 py-1 text-xs bg-transparent">
                        </div>

                        <div>
                            <label for="eventBranch" class="block text-xs font-medium text-gray-700 mb-1">Branch</label>
                            <input type="text" id="eventBranch" name="event_branch"
                                class="block w-full border-0 border-b-[0.5px] border-black focus:border-blue-500 focus:outline-none focus:ring-0 px-1 py-1 text-xs bg-transparent">
                        </div>

                        <div>
                            <label for="eventVenue" class="block text-xs font-medium text-gray-700 mb-1">Venue</label>
                            <input type="text" id="eventVenue" name="event_venue"
                                class="block w-full border-0 border-b-[0.5px] border-black focus:border-blue-500 focus:outline-none focus:ring-0 px-1 py-1 text-xs bg-transparent">
                        </div>
                    </div>

                    <div>
                        <label for="remarks" class="block text-xs font-medium text-gray-700 mb-1">Remarks</label>
                        <textarea id="remarks" name="remarks" rows="3"
                            class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none px-2 py-1 text-xs resize-none"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                    <button type="button" id="createButton"
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-300 font-medium rounded-md text-xs px-4 py-2 transition-colors duration-200">
                        Create Event
                    </button>
                    <a href="{{ route('hrms.event.index') }}"
                        class="text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-2 focus:ring-gray-300 font-medium rounded-md text-xs px-4 py-2 border border-gray-300">
                        Event Listing
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Confirmation Modal --}}
    <div id="confirmationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-transparent hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm mx-auto animate-fade-in-up">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Confirm Creation</h3>
            <p class="text-sm text-gray-600 mb-6">Are you sure you want to create this event?</p>
            <div class="flex justify-end space-x-4">
                <button type="button" id="modalCancelButton"
                    class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors">
                    Cancel
                </button>
                <button type="button" id="modalConfirmButton"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-colors">
                    Yes, Create
                </button>
            </div>
        </div>
    </div>
</x-hrms::layouts.master>
