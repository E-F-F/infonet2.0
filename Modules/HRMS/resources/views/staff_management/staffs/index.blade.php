<x-hrms::layouts.master>
    <div class="w-full max-w-4xl bg-white shadow-lg rounded-xl overflow-hidden">
        <!-- Table Header with Search and Filter -->
        <div
            class="p-4 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4 bg-gray-50 border-b border-gray-200">
            <!-- Search Input -->
            <div class="relative w-full sm:w-1/2">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="table-search"
                    class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search users by name or title">
            </div>

            <!-- Filter Dropdown -->
            <div class="w-full sm:w-1/2 flex justify-end">
                <button id="dropdownRadioButton" data-dropdown-toggle="dropdownRadio"
                    class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                    type="button">
                    <svg class="w-3 h-3 text-gray-500 dark:text-gray-400 me-3" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16Zm-1-5h2a1 1 0 1 0 0-2h-2a1 1 0 1 0 0 2Zm0-4h2a1 1 0 1 0 0-2h-2a1 1 0 1 0 0 2Z" />
                    </svg>
                    Filter by status
                    <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <!-- Dropdown menu -->
                <div id="dropdownRadio"
                    class="z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                    data-popper-reference-hidden="" data-popper-escaped="" data-popper-placement="top"
                    style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(522px, 3845px, 0px);">
                    <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200"
                        aria-labelledby="dropdownRadioButton">
                        <li>
                            <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input id="filter-radio-all" type="radio" value="All" name="filter-radio"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                    checked>
                                <label for="filter-radio-all"
                                    class="w-full ml-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">All</label>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input id="filter-radio-active" type="radio" value="Active" name="filter-radio"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="filter-radio-active"
                                    class="w-full ml-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">Active</label>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input id="filter-radio-pending" type="radio" value="Pending" name="filter-radio"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="filter-radio-pending"
                                    class="w-full ml-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">Pending</label>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input id="filter-radio-inactive" type="radio" value="Inactive" name="filter-radio"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="filter-radio-inactive"
                                    class="w-full ml-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">Inactive</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Responsive wrapper for the table -->
        <!-- overflow-x-auto ensures horizontal scrolling on small screens if content is too wide -->
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal table-auto text-sm text-left text-gray-500 dark:text-gray-400">
                <!-- Table Header -->
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <!-- Table header cells with styling for text, alignment, and background -->
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Title
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Role
                        </th>
                    </tr>
                </thead>
                <!-- Table Body -->
                <tbody id="data-table-body">
                    <!-- Sample Data Row 1 -->
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            John Doe
                        </td>
                        <td class="px-6 py-4">
                            Software Engineer
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Active</span>
                        </td>
                        <td class="px-6 py-4">
                            Member
                        </td>
                    </tr>
                    <!-- Sample Data Row 2 -->
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Jane Smith
                        </td>
                        <td class="px-6 py-4">
                            Product Manager
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                        </td>
                        <td class="px-6 py-4">
                            Admin
                        </td>
                    </tr>
                    <!-- Sample Data Row 3 -->
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Bob Johnson
                        </td>
                        <td class="px-6 py-4">
                            UX Designer
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">Inactive</span>
                        </td>
                        <td class="px-6 py-4">
                            Editor
                        </td>
                    </tr>
                    <!-- Sample Data Row 4 -->
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Alice Brown
                        </td>
                        <td class="px-6 py-4">
                            Frontend Developer
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Active</span>
                        </td>
                        <td class="px-6 py-4">
                            Contributor
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Flowbite JavaScript CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('table-search');
            const tableBody = document.getElementById('data-table-body');
            const rows = tableBody.querySelectorAll('tr');
            const filterRadios = document.querySelectorAll('input[name="filter-radio"]');

            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedStatusFilter = document.querySelector('input[name="filter-radio"]:checked').value;

                rows.forEach(row => {
                    const name = row.children[0].textContent.toLowerCase();
                    const title = row.children[1].textContent.toLowerCase();
                    // Get the text content of the status directly from the third cell (td)
                    // This is more robust as it doesn't rely on specific nested span classes.
                    const status = row.children[2].textContent.toLowerCase();

                    // Check for search term
                    const matchesSearch = name.includes(searchTerm) || title.includes(searchTerm);

                    // Check for status filter
                    const matchesFilter = (selectedStatusFilter === 'All') || (status.includes(
                        selectedStatusFilter.toLowerCase()));

                    // Show or hide the row based on both conditions
                    if (matchesSearch && matchesFilter) {
                        row.style.display = ''; // Show the row
                    } else {
                        row.style.display = 'none'; // Hide the row
                    }
                });
            }

            // Event listener for search input
            searchInput.addEventListener('keyup', applyFilters);

            // Event listeners for filter radio buttons
            filterRadios.forEach(radio => {
                radio.addEventListener('change', applyFilters);
            });

            // Initial application of filters when the page loads
            applyFilters();
        });
    </script>

</x-hrms::layouts.master>