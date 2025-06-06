<x-hrms::layouts.master>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-xl font-bold text-gray-800 mb-6">Manage Leave Ranks</h1>

        {{-- Inline Create Form --}}
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Add New Leave Rank</h2>
        <form action="{{ route('hrms.leave_ranks.store') }}" method="POST"
            class="mb-4 p-3 border border-gray-200 rounded-md bg-gray-50 max-w-3xl">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Leave Rank Name:</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="block w-full px-2.5 py-1.5 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active"
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Is Active</label>
                </div>
            </div>
            <button type="submit"
                class="mt-4 inline-flex justify-center py-1.5 px-3 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition duration-150 ease-in-out">
                Create Leave Rank
            </button>
        </form>

        <hr class="my-6 border-t border-gray-200">

        {{-- Leave Ranks List --}}
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Existing Leave Ranks</h2>

        {{-- Search Box --}}
        <form method="GET" action="{{ route('hrms.leave_ranks.index') }}" class="mb-4">
            <div class="relative w-full max-w-sm">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search leave ranks..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1116.65 2a7.5 7.5 0 010 15z" />
                    </svg>
                </div>
            </div>
        </form>

        @if ($leaveRanks->isEmpty())
            <p class="text-gray-600 text-xs text-base">No leave ranks found. Please add one using the form above.</p>
        @else
            <div class="overflow-x-auto border border-gray-200 rounded-md max-w-xl">
                <table class="min-w-full text-sm divide-y divide-gray-200 text-center">
                    <thead class="bg-gray-100">
                        <tr>
                            <th
                                class="py-2 px-3 font-medium text-gray-700 text-xs uppercase tracking-wide rounded-tl-md">
                                Name
                            </th>
                            <th
                                class="py-2 px-3 font-medium text-gray-700 text-xs uppercase tracking-wide rounded-tr-md">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($leaveRanks as $leaveRank)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-3 text-gray-800">
                                    {{ $leaveRank->name }}
                                </td>
                                <td class="py-2 px-3 whitespace-nowrap">
                                    <a href="{{ route('hrms.leave_ranks.edit', $leaveRank->id) }}"
                                        class="text-blue-600 hover:text-blue-800 text-xs font-medium mr-2 transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('hrms.leave_ranks.destroy', $leaveRank->id) }}"
                                        method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 text-xs font-medium focus:outline-none transition"
                                            onclick="return confirm('Are you sure you want to delete this leave rank?');">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif
    </div>

</x-hrms::layouts.master>
