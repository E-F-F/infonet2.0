@props([
    'id' => 'addNewRowBtn',
    'text' => 'New Item',
    'class' => '',
])

<button id="{{ $id }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 mt-4 text-white bg-blue-500 hover:bg-blue-600 focus:ring-3 focus:outline-none focus:ring-blue-300 rounded-full text-sm px-3 py-1.5 text-center ' . $class]) }}>

    {{-- Plus Icon (Heroicons solid/outline) --}}
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
        <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z"
            clip-rule="evenodd" />
    </svg>

    {{ $text }}
</button>
