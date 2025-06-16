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

// document.addEventListener('DOMContentLoaded', function () {
//     const searchInput = document.getElementById('table-search');
//     const tableBody = document.getElementById('data-table-body');
//     const rows = tableBody.querySelectorAll('tr');
//     const filterRadios = document.querySelectorAll('input[name="filter-radio"]');

//     function applyFilters() {
//         const searchTerm = searchInput.value.toLowerCase();
//         const selectedStatusFilter = document.querySelector('input[name="filter-radio"]:checked').value;

//         rows.forEach(row => {
//             const name = row.children[0].textContent.toLowerCase();
//             const title = row.children[1].textContent.toLowerCase();
//             // Get the text content of the status span, which is the actual status
//             const statusElement = row.children[2].querySelector('span.relative, span.bg-').textContent;
//             const status = statusElement ? statusElement.toLowerCase() : '';

//             // Check for search term
//             const matchesSearch = name.includes(searchTerm) || title.includes(searchTerm);

//             // Check for status filter
//             const matchesFilter = (selectedStatusFilter === 'All') || (status === selectedStatusFilter.toLowerCase());

//             // Show or hide the row based on both conditions
//             if (matchesSearch && matchesFilter) {
//                 row.style.display = ''; // Show the row
//             } else {
//                 row.style.display = 'none'; // Hide the row
//             }
//         });
//     }

//     // Event listener for search input
//     searchInput.addEventListener('keyup', applyFilters);

//     // Event listeners for filter radio buttons
//     filterRadios.forEach(radio => {
//         radio.addEventListener('change', applyFilters);
//     });

//     // Initial application of filters when the page loads
//     applyFilters();
// });

document.addEventListener('DOMContentLoaded', function () {
    const eventForm = document.getElementById('eventForm');
    const confirmationModal = document.getElementById('confirmationModal');
    const modalConfirmButton = document.getElementById('modalConfirmButton');
    const modalCancelButton = document.getElementById('modalCancelButton');

    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');
    const createButton = document.getElementById('createButton');

    const formElements = eventForm?.querySelectorAll('input, textarea, select') || [];

    // Store original values for edit cancel
    const originalValues = {};
    formElements.forEach(el => {
        if (el.name) originalValues[el.name] = el.value;
    });

    // Handle enabling form for editing
    function enableFormEditing() {
        formElements.forEach(el => el.disabled = false);
        editButton?.classList.add('hidden');
        saveButton?.classList.remove('hidden');
        cancelButton?.classList.remove('hidden');
    }

    // Revert to original values
    function cancelEditing() {
        formElements.forEach(el => {
            if (originalValues[el.name] !== undefined) {
                el.value = originalValues[el.name];
            }
            el.disabled = true;
        });
        editButton?.classList.remove('hidden');
        saveButton?.classList.add('hidden');
        cancelButton?.classList.add('hidden');
    }

    // Show confirmation modal
    function showModal() {
        confirmationModal.classList.remove('hidden');
        confirmationModal.classList.add('flex');
    }

    // Hide confirmation modal
    function hideModal() {
        confirmationModal.classList.add('hidden');
        confirmationModal.classList.remove('flex');
    }

    // Bind events
    editButton?.addEventListener('click', enableFormEditing);
    cancelButton?.addEventListener('click', cancelEditing);
    saveButton?.addEventListener('click', showModal);
    createButton?.addEventListener('click', showModal);

    modalConfirmButton?.addEventListener('click', function () {
        eventForm.submit();
    });

    modalCancelButton?.addEventListener('click', hideModal);
});
