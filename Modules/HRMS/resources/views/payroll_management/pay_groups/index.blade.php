<x-hrms::layouts.master>
    <div class="max-w-5xl mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Pay Groups</h2>
            <button id="addNewRowBtn"
                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300
                           font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Add New Row
            </button>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-700" id="payGroupsTable">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payGroups as $type)
                        <tr class="bg-white border-b hover:bg-gray-50" data-id="{{ $type->id }}">
                            <td class="px-6 py-4 editable-cell" data-field="name">{{ $type->name }}</td>
                            <td class="px-6 py-4 flex gap-2 action-buttons">
                                <button
                                    class="edit-btn text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded-lg text-sm px-3 py-1.5">
                                    Edit
                                </button>
                                <button
                                    class="save-btn text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-3 py-1.5 hidden">
                                    Save
                                </button>
                                <button
                                    class="cancel-btn text-gray-800 bg-gray-200 hover:bg-gray-300 font-medium rounded-lg text-sm px-3 py-1.5 hidden">
                                    Cancel
                                </button>
                                <button
                                    class="delete-btn text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-3 py-1.5">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const payGroupsTable = document.getElementById('payGroupsTable');
            const addNewRowBtn = document.getElementById('addNewRowBtn');

            function makeRowEditable(row) {
                row.classList.add('editing');
                const cell = row.querySelector('[data-field="name"]');
                const value = cell.innerText;
                cell.innerHTML = `<input type="text" value="${value}" class="w-full px-2 py-1 border rounded" />`;

                row.querySelector('.edit-btn').classList.add('hidden');
                row.querySelector('.save-btn').classList.remove('hidden');
                row.querySelector('.cancel-btn').classList.remove('hidden');
                row.querySelector('.delete-btn').classList.add('hidden');
            }

            function makeRowViewable(row, originalName) {
                row.classList.remove('editing');
                const cell = row.querySelector('[data-field="name"]');
                cell.innerText = originalName;

                row.querySelector('.edit-btn').classList.remove('hidden');
                row.querySelector('.save-btn').classList.add('hidden');
                row.querySelector('.cancel-btn').classList.add('hidden');
                row.querySelector('.delete-btn').classList.remove('hidden');
            }

            payGroupsTable.addEventListener('click', async (e) => {
                const row = e.target.closest('tr');
                if (!row) return;

                const rowId = row.dataset.id;
                const isNew = !rowId;

                // EDIT
                if (e.target.classList.contains('edit-btn')) {
                    const originalName = row.querySelector('[data-field="name"]').innerText;
                    row.dataset.originalName = originalName;
                    makeRowEditable(row);
                }

                // SAVE
                if (e.target.classList.contains('save-btn')) {
                    const input = row.querySelector('[data-field="name"] input');
                    const newName = input.value.trim();

                    if (newName === '') {
                        alert('Pay group name cannot be empty.');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('name', newName);
                    formData.append('_token', '{{ csrf_token() }}');

                    let url, method;
                    if (isNew) {
                        url = '{{ route('hrms.pay-groups.store') }}';
                        method = 'POST';
                    } else {
                        url = `{{ url('hrms/pay-groups') }}/${rowId}`;
                        formData.append('_method', 'PUT');
                        method = 'POST';
                    }

                    try {
                        const response = await fetch(url, {
                            method,
                            body: formData
                        });

                        if (!response.ok) {
                            if (response.status === 422) {
                                const errorData = await response.json();
                                alert(errorData.message);
                                return;
                            }
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();
                        if (data.success) {
                            row.dataset.id = data.payGroup.id;
                            row.querySelector('[data-field="name"]').innerText = data.payGroup.name;
                            makeRowViewable(row, data.payGroup.name);
                            alert(data.message);
                        } else {
                            alert('Failed to save pay group: ' + data.message);
                        }
                    } catch (error) {
                        console.error(error);
                        alert('An unexpected error occurred.');
                    }
                }

                // CANCEL
                if (e.target.classList.contains('cancel-btn')) {
                    if (isNew) {
                        row.remove();
                    } else {
                        const originalName = row.dataset.originalName;
                        makeRowViewable(row, originalName);
                    }
                }

                // DELETE
                if (e.target.classList.contains('delete-btn')) {
                    if (!confirm('Are you sure you want to delete this pay group?')) return;

                    try {
                        const response = await fetch(`{{ url('hrms/pay-groups') }}/${rowId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        if (data.success) {
                            row.remove();
                            alert(data.message || 'Pay group deleted successfully.');
                        } else {
                            alert('Failed to delete pay group.');
                        }
                    } catch (error) {
                        console.error(error);
                        alert('An unexpected error occurred while deleting.');
                    }
                }
            });

            addNewRowBtn.addEventListener('click', () => {
                const newRow = payGroupsTable.querySelector('tbody').insertRow();
                newRow.classList.add('bg-white', 'border-b', 'hover:bg-gray-50', 'editing');

                newRow.innerHTML = `
            <td class="px-6 py-4 editable-cell" data-field="name">
                <input type="text" class="w-full px-2 py-1 border rounded" placeholder="New Pay Group Name" />
            </td>
            <td class="px-6 py-4 flex gap-2 action-buttons">
                <button class="edit-btn text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded-lg text-sm px-3 py-1.5 hidden">Edit</button>
                <button class="save-btn text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-3 py-1.5">Save</button>
                <button class="cancel-btn text-gray-800 bg-gray-200 hover:bg-gray-300 font-medium rounded-lg text-sm px-3 py-1.5">Cancel</button>
                <button class="delete-btn bg-red-600 text-white rounded px-3 py-1.5 hover:bg-red-700 hidden">Delete</button>
            </td>
        `;
                newRow.scrollIntoView({
                    behavior: 'smooth',
                    block: 'end'
                });
            });
        });
    </script>
</x-hrms::layouts.master>
