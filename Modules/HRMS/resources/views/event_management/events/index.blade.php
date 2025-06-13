<x-hrms::layouts.master>
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1000)" x-show="show" x-transition.opacity.duration.500ms
            class="fixed top-4 left-1/2 transform -translate-x-1/2
               flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg
               bg-green-50 border border-green-300 shadow z-50"
            role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"
                class="flex-shrink-0 inline w-5 h-5 mr-3">
                <path fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"
                    clip-rule="evenodd" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    <div class="container mx-auto px-4 py-6">
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <form id="eventForm" method="POST" action="{{ route('hrms.event.store') }}">
                @csrf
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Event Type</th>
                            <th class="px-4 py-3">Start Date</th>
                            <th class="px-4 py-3">End Date</th>
                            <th class="px-4 py-3">Company</th>
                            <th class="px-4 py-3">Branch</th>
                            <th class="px-4 py-3">Venue</th>
                            <th class="px-4 py-3">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="divide-x divide-gray-200">
                            <td class="px-4 py-3">
                                <input type="text" name="title"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" name="hrms_event_type_id"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="date" name="start_date"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="date" name="end_date"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" name="event_company"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" name="event_branch"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" name="event_venue"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <textarea name="remarks" rows="2"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500 resize-none"></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex items-center justify-between border-t border-gray-200 px-4 py-4">

                    <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-300 font-medium rounded-md text-xs px-4 py-2 transition-colors duration-200">
                        Create Event
                    </button>
                    {{-- <a href="{{ route('hrms.event.index') }}"
                        class="text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-2 focus:ring-gray-300 font-medium rounded-md text-xs px-4 py-2 border border-gray-300">
                        Event Listing
                    </a> --}}
                </div>
            </form>
        </div>
    </div>


    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-end mb-6">
            <form method="GET" class="flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search title..."
                    class="px-3 py-2 border border-gray-300 rounded-md text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64" />
                <button type="submit"
                    class="text-sm px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                    Search
                </button>
            </form>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">Title</th>
                        <th class="px-4 py-3 text-left">Start Date</th>
                        <th class="px-4 py-3 text-left">End Date</th>
                        <th class="px-4 py-3 text-left">Company</th>
                        <th class="px-4 py-3 text-left">Branch</th>
                        <th class="px-4 py-3 text-left">Venue</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($events as $event)
                        {{-- Wrap both TRs in a Tbody with x-data --}}
                <tbody x-data="{ expanded: false }" class="divide-y divide-gray-100">
                    <tr @click="expanded = !expanded" class="hover:bg-gray-50 cursor-pointer">
                        <td class="px-4 py-3">
                            <a href="{{ route('hrms.event.show', $event->id) }}" class="text-blue-600 hover:underline">
                                {{ $event->title }}
                            </a>
                        </td>
                        <td class="px-4 py-3">{{ $event->start_date }}</td>
                        <td class="px-4 py-3">{{ $event->end_date }}</td>
                        <td class="px-4 py-3">{{ $event->event_company }}</td>
                        <td class="px-4 py-3">{{ $event->event_branch }}</td>
                        <td class="px-4 py-3">{{ $event->event_venue }}</td>
                        <td class="px-4 py-3">
                            @if ($event->is_active)
                                <span
                                    class="inline-block px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded">Active</span>
                            @else
                                <span
                                    class="inline-block px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('hrms.event.show', $event->id) }}"
                                class="text-blue-600 hover:underline text-xs">View</a>
                        </td>
                    </tr>
                    <tr x-show="expanded" x-transition.opacity> {{-- Added .opacity for smoother transition --}}
                        <td colspan="8" class="px-4 py-3 bg-gray-50">
                            <div class="flex items-start justify-between text-sm text-gray-700">
                                <div>
                                    <strong>Remarks:</strong> {{ $event->remarks ?? 'No remarks available.' }}
                                </div>
                                <a href=""
                                    class="ml-4 text-xs px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors duration-200">
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            @empty
                <tbody> {{-- Added a tbody for the empty state --}}
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">No events found.</td>
                    </tr>
                </tbody>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $events->withQueryString()->links() }}
        </div>
    </div>
</x-hrms::layouts.master>
