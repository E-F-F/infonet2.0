<x-hrms::layouts.master>
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Event List</h2>
            <form method="GET" class="flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search title..."
                    class="px-4 py-2 border rounded-md w-64" />
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Search</button>
            </form>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs uppercase bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-3">Title</th>
                        <th class="px-6 py-3">Start Date</th>
                        <th class="px-6 py-3">End Date</th>
                        <th class="px-6 py-3">Company</th>
                        <th class="px-6 py-3">Branch</th>
                        <th class="px-6 py-3">Venue</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $event->title }}</td>
                            <td class="px-6 py-4">{{ $event->start_date }}</td>
                            <td class="px-6 py-4">{{ $event->end_date }}</td>
                            <td class="px-6 py-4">{{ $event->event_company }}</td>
                            <td class="px-6 py-4">{{ $event->event_branch }}</td>
                            <td class="px-6 py-4">{{ $event->event_venue }}</td>
                            <td class="px-6 py-4">
                                @if ($event->is_active)
                                    <span class="text-green-600 font-semibold">Active</span>
                                @else
                                    <span class="text-red-600 font-semibold">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{-- <a href="{{ route('hrms.events.show', $event->id) }}"
                                    class="text-blue-600 hover:underline">View</a> --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No events found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $events->withQueryString()->links() }}
        </div>
    </div>
</x-hrms::layouts.master>
