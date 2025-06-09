@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'bg-colorBackgroundLight dark:bg-colorBackgroundDark text-gray-900 dark:text-gray-300 border-gray-300 dark:border-gray-600 rounded-md focus:border-colorNormal focus:ring-1 focus:ring-colorNormal'
    ]) }}
>
