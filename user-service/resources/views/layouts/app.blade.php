<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          darkMode: localStorage.getItem('darkMode') === 'true' 
                    || (localStorage.getItem('darkMode') === null && window.matchMedia('(prefers-color-scheme: dark)').matches),
          toggle() {
              this.darkMode = !this.darkMode
              localStorage.setItem('darkMode', this.darkMode)
              document.documentElement.classList.toggle('dark', this.darkMode)
          }
      }"
      x-init="
          document.documentElement.classList.toggle('dark', darkMode);
          $watch('darkMode', val => document.documentElement.classList.toggle('dark', val))
      "
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SsttMate') }}</title>
        <link rel="icon" href="{{ asset('images/ssttmate_icon.svg') }}" type="image/svg+xml">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Custom CSS for floating circles -->
        <style>
            .floating-circles {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                pointer-events: none;
                z-index: -1;
                overflow: hidden;
            }
            
            .circle {
                position: absolute;
                border-radius: 50%;
                opacity: 0.4;
                filter: blur(2px);
            }
            
            .circle-red {
                background: radial-gradient(circle, #ff6b6b 0%, #ff4757 50%, #ff3838 100%);
                animation: float-red 25s infinite linear;
            }
            
            .circle-yellow {
                background: radial-gradient(circle, #ffd32a 0%, #ffb142 50%, #ffa726 100%);
                animation: float-yellow 30s infinite linear;
            }
            
            .circle-1 { width: 300px; height: 300px; top: 10%; left: -150px; animation-delay: 0s; }
            .circle-2 { width: 200px; height: 200px; top: 50%; right: -100px; animation-delay: -5s; }
            .circle-3 { width: 150px; height: 150px; top: 80%; left: 20%; animation-delay: -10s; }
            .circle-4 { width: 250px; height: 250px; top: 20%; right: 15%; animation-delay: -15s; }
            .circle-5 { width: 120px; height: 120px; top: 60%; left: 5%; animation-delay: -20s; }
            .circle-6 { width: 180px; height: 180px; top: 30%; left: 70%; animation-delay: -8s; }
            
            @keyframes float-red {
                0% { transform: translateY(0px) translateX(0px) rotate(0deg); }
                25% { transform: translateY(-50px) translateX(30px) rotate(90deg); }
                50% { transform: translateY(0px) translateX(60px) rotate(180deg); }
                75% { transform: translateY(50px) translateX(30px) rotate(270deg); }
                100% { transform: translateY(0px) translateX(0px) rotate(360deg); }
            }
            
            @keyframes float-yellow {
                0% { transform: translateX(0px) translateY(0px) rotate(0deg) scale(1); }
                20% { transform: translateX(40px) translateY(-30px) rotate(72deg) scale(1.1); }
                40% { transform: translateX(20px) translateY(20px) rotate(144deg) scale(0.9); }
                60% { transform: translateX(-30px) translateY(-10px) rotate(216deg) scale(1.05); }
                80% { transform: translateX(-10px) translateY(40px) rotate(288deg) scale(0.95); }
                100% { transform: translateX(0px) translateY(0px) rotate(360deg) scale(1); }
            }
            
            .text-stroke-yellow {
                color: transparent;
                -webkit-text-stroke: 1px #FFBB01;
            }
            
            .dark .text-stroke-yellow {
                -webkit-text-stroke: 2px #FFBB01;
            }
            
            .dark .circle {
                opacity: 0.2;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 relative">
            <!-- Floating Circles Background -->
            <div class="floating-circles">
                <div class="circle circle-yellow circle-1"></div>
                <div class="circle circle-red circle-2"></div>
                <div class="circle circle-yellow circle-3"></div>
                <div class="circle circle-red circle-4"></div>
                <div class="circle circle-yellow circle-5"></div>
                <div class="circle circle-red circle-6"></div>
            </div>
            
            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="dark:bg-gray-900">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>