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
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Start Date</th>
                            <th class="px-4 py-3">End Date</th>
                            <th class="px-4 py-3">Branch</th>
                            <th class="px-4 py-3">Award</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="divide-x divide-gray-200">
                            <td class="px-4 py-3">
                                <input type="text" name="training_name"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <select name="hrms_training_type_id"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                                    <option value="" disabled selected>Select Type</option>
                                    @foreach ($trainingTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <input type="date" name="training_start_date"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <input type="date" name="training_end_date"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-3">
                                <select name="branch_id"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                                    <option value="" disabled selected>Select Branch</option>
                                    @foreach ($branch as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <select name="hrms_training_award_type_id"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                                    <option value="" disabled selected>Select Award</option>
                                    @foreach ($trainingAwardsTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex items-center justify-between border-t border-gray-200 px-4 py-4">

                    <button type="button" id="createButton"
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-300 font-medium rounded-md text-xs px-4 py-2 transition-colors duration-200">
                        Create Training
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- Confirmation Modal --}}
    {{-- <div id="confirmationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-transparent hidden">
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
    </div> --}}

    {{-- Training Listing --}}
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
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Start Date</th>
                        <th class="px-4 py-3 text-left">End Date</th>
                        <th class="px-4 py-3 text-left">Branch</th>
                        <th class="px-4 py-3 text-left">Award</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($trainings as $training)
                        {{-- Wrap both TRs in a Tbody with x-data --}}
                <tbody x-data="{ expanded: false }" class="divide-y divide-gray-100">
                    <tr @click="expanded = !expanded" class="hover:bg-gray-50 cursor-pointer">
                        <td class="px-4 py-3">
                            {{-- <a href="{{ route('hrms.event.show', $event->id) }}"
                                class="text-blue-600 hover:underline">
                                {{ $event->title }}
                            </a> --}}
                            <a href="" class="text-blue-600 hover:underline">
                                {{ $training->training_name }}
                            </a>
                        </td>
                        <td class="px-4 py-3">{{ $training->trainingType->name ?? '' }}</td>
                        <td class="px-4 py-3">{{ $training->training_start_date }}</td>
                        <td class="px-4 py-3">{{ $training->training_end_date }}</td>
                        <td class="px-4 py-3">{{ $training->branch->name }}</td>
                        <td class="px-4 py-3">{{ $training->trainingAwardType->name }}</td>
                        <td class="px-4 py-3">
                            <a href="" class="text-blue-600 hover:underline text-xs">Show/Edit</a>
                        </td>
                    </tr>
                </tbody>
            @empty
                <tbody> {{-- Added a tbody for the empty state --}}
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">No training found.</td>
                    </tr>
                </tbody>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $trainings->withQueryString()->links() }}
        </div>
    </div>
</x-hrms::layouts.master>
