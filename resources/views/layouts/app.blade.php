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
        <link rel="icon" href="{{ asset('images/ssttmate_icon.svg') }}" type="image/svg+xml">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-colorBackgroundLight dark:bg-colorBackgroundDark">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
