<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          darkMode: localStorage.getItem('darkMode')
                    || (!!window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches),
          toggle() {
              this.darkMode = !this.darkMode
              localStorage.setItem('darkMode', this.darkMode)
          }
      }"
      x-init="$watch('darkMode', val => document.documentElement.classList.toggle('dark', val))"
      x-bind:class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SsttMate') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Baloo&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-colorBackgroundLight dark:bg-colorBackgroundDark">
            <div class="flex flex-col items-center justify-center text-center">
                <a href="/" class="flex flex-col items-center justify-center">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    <h1 class="font-baloo text-[40px] text-stroke-yellow">SsttMate</h1>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-[#181D23] border border-[#2A2F36] rounded-lg" style="box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
