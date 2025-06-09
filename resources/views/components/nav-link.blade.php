@props(['active'])

@php
// Define the base classes for the navigation link
$baseClasses = 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none';

// Define the classes for the active state
$activeClasses = 'border-colorNormal text-gray-900 dark:text-white focus:border-colorNormal';

// Define the classes for the inactive state
$inactiveClasses = 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-600';

// Combine the classes based on whether the link is active
$classes = $baseClasses . ' ' . ($active ? $activeClasses : $inactiveClasses);
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
