@props(['disabled' => false])

<input 
    @disabled($disabled) 
    {{ $attributes->merge([
        'class' => 
            'bg-[#181D23] text-white border border-gray-700 rounded-md shadow-md focus:border-yellow-500 focus:ring-yellow-500 focus:ring-1'
    ]) }}
>