@props([
    'label',
    'name',
    'type' => 'text',
    'value' => old($name),
    'required' => false,
    'placeholder' => '',
    'options' => [],
])

<div class="{{ $type === 'textarea' ? 'md:col-span-3' : '' }}">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    @if ($type === 'select')
        <select name="{{ $name }}" id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm']) }}>
            <option value="">Select {{ $label }}</option>
            @foreach ($options as $key => $text)
                <option value="{{ $key }}" {{ $value == $key ? 'selected' : '' }}>{{ $text }}</option>
            @endforeach
        </select>

    @elseif ($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $name }}" rows="3"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm']) }}
            placeholder="{{ $placeholder }}">{{ $value }}</textarea>

    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}"
            {{ $required ? 'required' : '' }}
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm']) }}>
    @endif
</div>
