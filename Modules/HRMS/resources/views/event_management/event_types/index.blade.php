<x-hrms::layouts.master>
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Event Types</h2>
        <button data-modal-target="addModal" data-modal-toggle="addModal" 
                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 
                       font-medium rounded-lg text-sm px-5 py-2.5 text-center">
            Add New Event Type
        </button>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Active</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($eventTypes as $type)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $type->name }}</td>
                        <td class="px-6 py-4">{{ $type->is_active ? 'Yes' : 'No' }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            {{-- Edit Button --}}
                            <button data-modal-target="editModal{{ $type->id }}" data-modal-toggle="editModal{{ $type->id }}"
                                class="text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded-lg text-sm px-3 py-1.5">
                                Edit
                            </button>

                            {{-- Delete Form --}}
                            <form action="{{ route('hrms.event-types.destroy', $type->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this event type?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-3 py-1.5">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div id="editModal{{ $type->id }}" tabindex="-1" aria-hidden="true"
                         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto
                                md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center bg-black/50">
                        <div class="relative w-full max-w-md">
                            <div class="relative bg-white rounded-lg shadow">
                                <form action="{{ route('hrms.event-types.update', $type->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="p-6">
                                        <h3 class="mb-4 text-xl font-semibold text-gray-800">Edit Event Type</h3>
                                        <div class="mb-4">
                                            <label class="block mb-2 text-sm font-medium text-gray-700">Name</label>
                                            <input type="text" name="name" value="{{ $type->name }}" required
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div class="flex items-center mb-4">
                                            <input type="checkbox" name="is_active" id="active{{ $type->id }}"
                                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                   {{ $type->is_active ? 'checked' : '' }}>
                                            <label for="active{{ $type->id }}" class="ml-2 text-sm text-gray-700">Is Active</label>
                                        </div>
                                        <div class="flex justify-end gap-2">
                                            <button type="submit"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                                Save
                                            </button>
                                            <button type="button" data-modal-hide="editModal{{ $type->id }}"
                                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Add Modal --}}
    <div id="addModal" tabindex="-1" aria-hidden="true"
         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto
                md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center bg-black/50">
        <div class="relative w-full max-w-md">
            <div class="relative bg-white rounded-lg shadow">
                <form action="{{ route('hrms.event-types.store') }}" method="POST">
                    @csrf
                    <div class="p-6">
                        <h3 class="mb-4 text-xl font-semibold text-gray-800">Add New Event Type</h3>
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex items-center mb-4">
                            <input type="checkbox" name="is_active" id="active_add"
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                            <label for="active_add" class="ml-2 text-sm text-gray-700">Is Active</label>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                Add
                            </button>
                            <button type="button" data-modal-hide="addModal"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-hrms::layouts.master>
