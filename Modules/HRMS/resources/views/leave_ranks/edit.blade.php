<x-hrms::layouts.master>
        <div class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Edit Leave Rank</h1>

            <form action="{{ route('hrms.leave_ranks.update', $leaveRank->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Use PUT method for update --}}

                <div class="mb-5">
                    <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Leave Rank Name:</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $leaveRank->name) }}"
                        class="shadow-sm appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="e.g., Senior Staff">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="is_active" id="is_active"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        {{ old('is_active', $leaveRank->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 block text-gray-700 text-sm font-semibold">Is Active</label>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 transition duration-300 ease-in-out">
                        Update Leave Rank
                    </button>
                    <a href="{{ route('hrms.leave_ranks.index') }}"
                        class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-md shadow-md transition duration-300 ease-in-out">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
</x-hrms::layouts.master>