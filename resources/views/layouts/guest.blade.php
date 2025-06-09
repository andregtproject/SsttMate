<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          darkMode: localStorage.getItem('darkMode') === 'true',
          toggle() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('darkMode', this.darkMode);
          }
      }"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SsttMate') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/iconic.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Mencegah 'flash' saat memuat halaman dalam mode gelap -->
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-colorBackgroundLight dark:bg-colorBackgroundDark">
        <div class="flex flex-col items-center justify-center text-center">
            <a href="/" class="flex flex-col items-center justify-center">
                 <img src="{{ asset('images/iconic.png') }}" alt="SsttMate Logo" class="w-32 h-32 mb-2">
                 <h1 class="font-baloo text-[40px] text-stroke-yellow">SsttMate</h1>
            </a>
        </div>

        <!-- Kontainer form ini sekarang responsif terhadap mode terang/gelap -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-colorBackgroundLight dark:bg-colorBackgroundDark border border-gray-200 dark:border-[#2A2F36] shadow-md dark:shadow-2xl dark:shadow-black/25 overflow-hidden rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
