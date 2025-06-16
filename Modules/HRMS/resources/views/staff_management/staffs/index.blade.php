<x-hrms::layouts.master>

    <div class="flex items-center justify-between mb-6 px-4">
        <h1 class="font-bold text-2xl">Employees</h1>

        <x-hrms::buttons.addButton id="addEmployeeBtn" text="New Employee" />
    </div>

    @include('hrms::components.datatable', [
        'headers' => $headers,
        // 'filters' => $filters,
        'rows' => $rows,
        'perPage' => 10,
        'searchable' => true,
        'searchPlaceholder' => 'Search employees...',
    ])

</x-hrms::layouts.master>
