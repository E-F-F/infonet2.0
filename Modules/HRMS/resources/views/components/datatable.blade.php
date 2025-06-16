@props([
    'headers' => [],
    'rows' => [],
    'searchable' => true,
    'searchPlaceholder' => 'Search...',
    'filters' => [], // expects array of filters with 'label', 'name', and 'options'
])

@php
    $encodedRows = json_encode($rows);
@endphp

<div class="w-full max-w-auto bg-white rounded-md overflow-hidden p-4 shadow-lg">
    <!-- Table Header with Search and Filters -->
    <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4  flex-wrap">
        @if ($searchable)
            <!-- Search Input -->
            <div class="relative w-full sm:w-1/2">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-3 h-3 text-gray-500" fill="none" viewBox="0 0 20 20" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="dynamic-table-search"
                    class="block w-60 p-1 pl-10 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-200 focus:border-blue-200"
                    placeholder="{{ $searchPlaceholder }}">
            </div>
        @endif

        <!-- Filter Dropdowns -->
        @foreach ($filters as $filter)
            <div class="w-full sm:w-auto">
                <label class="block mb-1 text-sm font-medium text-gray-700">{{ $filter['label'] }}</label>
                <select name="{{ strtolower($filter['name']) }}"
                    class="filter-select block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach ($filter['options'] as $option)
                        <option value="{{ strtolower($option) }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach

        <!-- Reset Button -->
        <div class="w-full sm:w-auto">
            <button id="reset-filters" class="px-3 py-1 text-sm text-white bg-red-500 hover:bg-red-600 rounded-md">
                Reset
            </button>
        </div>
    </div>

    <!-- Table -->

    <div class="overflow-x-auto rounded-lg shadow-sm">
        <table class="min-w-full table-auto text-sm text-left text-gray-500 shadow-lg">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    @foreach ($headers as $key => $label)
                        <th scope="col" class="px-6 py-3 cursor-pointer group" data-key="{{ $key }}">
                            <span class="flex items-center gap-1">
                                {{ $label }}
                                <svg class="w-3 h-3 group-hover:opacity-100 opacity-30" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="dynamic-table-body" class="divide-y divide-gray-200">
                @foreach ($rows as $row)
                    <tr class="bg-white  hover:bg-gray-50">
                        @foreach ($headers as $key => $label)
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                @if ($key === 'fullName' && isset($row['id']))
                                    <a href="{{ route('hrms.staff.show', ['id' => $row['id']]) }}"
                                        class="text-blue-600 hover:underline">
                                        {{ $row[$key] }}
                                    </a>
                                @else
                                    {{ $row[$key] ?? '-' }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls -->
    <div class="flex justify-between items-center mt-4 text-sm text-gray-700">
        <div>
            Showing <span id="current-page-start">1</span> to <span id="current-page-end">10</span> of <span
                id="total-rows">0</span> entries
        </div>
        <div class="flex items-center space-x-1">
            <button id="prev-page" class="px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200">Previous</button>
            <div id="pagination-numbers" class="flex space-x-1"></div>
            <button id="next-page" class="px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200">Next</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('dynamic-table-search');
            const tableBody = document.getElementById('dynamic-table-body');
            const allRows = Array.from(tableBody.querySelectorAll('tr'));
            const filterSelects = document.querySelectorAll('.filter-select');
            const headers = document.querySelectorAll('thead th[data-key]');
            const pageSize = 10;
            let currentPage = 1;
            let currentSort = {
                key: null,
                asc: true
            };

            function getFilteredRows() {
                const searchTerm = searchInput?.value.toLowerCase() || '';
                const activeFilters = {};

                filterSelects.forEach(select => {
                    activeFilters[select.name] = select.value.toLowerCase();
                });

                return allRows.filter(row => {
                    const cells = Array.from(row.querySelectorAll('td'));
                    const rowText = cells.map(td => td.textContent.toLowerCase()).join(' ');

                    let matchesSearch = rowText.includes(searchTerm);
                    let matchesFilters = Object.entries(activeFilters).every(([key, val]) => {
                        return val === 'all' || rowText.includes(val);
                    });

                    return matchesSearch && matchesFilters;
                });
            }

            function applySort(rows) {
                if (!currentSort.key) return rows;

                const index = Array.from(headers).findIndex(h => h.dataset.key === currentSort.key);
                return rows.sort((a, b) => {
                    const aText = a.querySelectorAll('td')[index]?.textContent.trim().toLowerCase();
                    const bText = b.querySelectorAll('td')[index]?.textContent.trim().toLowerCase();

                    if (aText < bText) return currentSort.asc ? -1 : 1;
                    if (aText > bText) return currentSort.asc ? 1 : -1;
                    return 0;
                });
            }

            function renderPagination(totalPages) {
                const container = document.getElementById('pagination-numbers');
                container.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    btn.className =
                        `px-2 py-1 rounded-full border border-gray-300 text-xs ${i === currentPage ? 'bg-blue-500 text-white' : 'bg-white hover:bg-gray-200'}`;
                    btn.addEventListener('click', () => {
                        currentPage = i;
                        renderTable();
                    });
                    container.appendChild(btn);
                }
            }

            function renderTable() {
                const filtered = getFilteredRows();
                const sorted = applySort(filtered);
                const total = sorted.length;
                const start = (currentPage - 1) * pageSize;
                const end = start + pageSize;
                const paginated = sorted.slice(start, end);

                tableBody.innerHTML = '';
                paginated.forEach(row => tableBody.appendChild(row));

                // Update pagination text
                document.getElementById('current-page-start').textContent = Math.min(start + 1, total);
                document.getElementById('current-page-end').textContent = Math.min(end, total);
                document.getElementById('total-rows').textContent = total;

                renderPagination(Math.ceil(total / pageSize));
            }

            // Reset button
            document.getElementById('reset-filters')?.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                filterSelects.forEach(select => select.selectedIndex = 0);
                currentPage = 1;
                renderTable();
            });


            // Events
            searchInput?.addEventListener('keyup', () => {
                currentPage = 1;
                renderTable();
            });

            filterSelects.forEach(select => {
                select.addEventListener('change', () => {
                    currentPage = 1;
                    renderTable();
                });
            });

            document.getElementById('prev-page').addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                }
            });

            document.getElementById('next-page').addEventListener('click', () => {
                const totalRows = getFilteredRows().length;
                if (currentPage < Math.ceil(totalRows / pageSize)) {
                    currentPage++;
                    renderTable();
                }
            });

            headers.forEach(header => {
                header.addEventListener('click', () => {
                    const key = header.dataset.key;
                    if (currentSort.key === key) {
                        currentSort.asc = !currentSort.asc;
                    } else {
                        currentSort = {
                            key,
                            asc: true
                        };
                    }
                    renderTable();
                });
            });

            renderTable();
        });
    </script>

</div>
