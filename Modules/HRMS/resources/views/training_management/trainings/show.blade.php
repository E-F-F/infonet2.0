<x-hrms::layouts.master>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Event Details</h1>

        <div class="bg-white shadow-lg rounded-xl p-8">
            <form id="eventForm" method="POST" action="{{ route('hrms.event.update', $event->id) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="title" name="title"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 disabled:bg-gray-50 disabled:cursor-not-allowed"
                            value="{{ $event->title }}" disabled>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="eventType" class="block text-sm font-medium text-gray-700 mb-1">Event
                                Type</label>
                            <input type="text" id="eventType" name="hrms_event_type_id"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 disabled:bg-gray-50 disabled:cursor-not-allowed"
                                value="{{ $event->hrms_event_type_id }}" disabled>
                        </div>
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">Start
                                Date</label>
                            <input type="date" id="startDate" name="start_date"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 disabled:bg-gray-50 disabled:cursor-not-allowed"
                                value="{{ $event->start_date }}" disabled>
                        </div>
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="endDate" name="end_date"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 disabled:bg-gray-50 disabled:cursor-not-allowed"
                                value="{{ $event->end_date }}" disabled>
                        </div>
                        <div>
                            <label for="eventCompany"
                                class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                            <input type="text" id="eventCompany" name="event_company"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 disabled:bg-gray-50 disabled:cursor-not-allowed"
                                value="{{ $event->event_company }}" disabled>
                        </div>
                        <div>
                            <label for="eventBranch" class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                            <input type="text" id="eventBranch" name="event_branch"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 disabled:bg-gray-50 disabled:cursor-not-allowed"
                                value="{{ $event->event_branch }}" disabled>
                        </div>
                        <div>
                            <label for="eventVenue" class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
                            <input type="text" id="eventVenue" name="event_venue"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 disabled:bg-gray-50 disabled:cursor-not-allowed"
                                value="{{ $event->event_venue }}" disabled>
                        </div>
                    </div>

                    <div>
                        <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                        <textarea id="remarks" name="remarks" rows="4"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 disabled:bg-gray-50 disabled:cursor-not-allowed"
                            disabled>{{ $event->remarks }}</textarea>
                    </div>
                </div>

                <div class="flex items-center space-x-4 pt-8 border-t border-gray-200 mt-8">
                    <button type="button" id="editButton"
                        class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
                        Edit
                    </button>
                    <button type="button" id="saveButton"
                        class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200 hidden">
                        Save Changes
                    </button>
                    <button type="button" id="cancelButton"
                        class="text-gray-900 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 border border-gray-300 transition-colors duration-200 hidden">
                        Cancel
                    </button>
                    <a href="{{ route('hrms.event.index') }}"
                        class="ml-auto text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
                        Back to Events
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div id="confirmationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-transparent hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm mx-auto animate-fade-in-up">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Confirm Save</h3>
            <p class="text-sm text-gray-600 mb-6">Are you sure you want to save these changes?</p>
            <div class="flex justify-end space-x-4">
                <button type="button" id="modalCancelButton"
                    class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors">
                    Cancel
                </button>
                <button type="button" id="modalConfirmButton"
                    class="px-5 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 transition-colors">
                    Yes, Save
                </button>
            </div>
        </div>
    </div>

    <div class="mt-8 p-4 bg-gray-100 rounded-lg">
        @php
            $activityLogs = $event->getDecodedActivityLogs();
        @endphp

        @if (!empty($activityLogs))
            <ul class="list-disc pl-5">
                @foreach ($activityLogs as $log)
                    <li class="text-gray-700">
                        {{ is_array($log) ? $log['message'] ?? ($log['description'] ?? json_encode($log)) : $log }}
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">No activity logs found or could not be decoded.</p>
        @endif
    </div>
</x-hrms::layouts.master>
