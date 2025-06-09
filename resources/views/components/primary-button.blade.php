<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center px-4 py-2 bg-colorNormal hover:bg-yellow-500 text-black font-semibold text-sm rounded-md transition focus:outline-none focus:ring-2 focus:ring-yellow-300'
]) }}>
    {{ $slot }}
</button>