import './bootstrap';
import 'flowbite';
import Alpine from 'alpinejs';
// import $ from 'jquery'; // DataTables often requires jQuery
// import 'datatables.net'; // Core DataTables library
// import 'datatables.net-dt/css/jquery.dataTables.css'; // Default styling

// Optional: If you need to make jQuery global for some legacy scripts or specific plugins
// window.jQuery = window.$ = $;


window.Alpine = Alpine; // Optional: Makes Alpine globally available
Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
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
            // Get the text content of the status span, which is the actual status
            const statusElement = row.children[2].querySelector('span.relative, span.bg-').textContent;
            const status = statusElement ? statusElement.toLowerCase() : '';

            // Check for search term
            const matchesSearch = name.includes(searchTerm) || title.includes(searchTerm);

            // Check for status filter
            const matchesFilter = (selectedStatusFilter === 'All') || (status === selectedStatusFilter.toLowerCase());

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

