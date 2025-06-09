<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

                <div class="p-4 sm:p-8 bg-colorBackgroundLight dark:bg-colorBackgroundDark rounded-lg flex flex-col" style="box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);">
                    <div class="text-center mb-4">
                        <h2 class="text-2xl inline-block bg-gradient-to-r from-colorHigh to-colorNormal bg-clip-text text-transparent">
                            Sound Indicator
                        </h2>
                    </div>

                    <div class="flex-grow flex flex-col justify-center items-center">

                        <div class="relative w-full max-w-xs">
                            <img src="{{ asset('images/level_indicator.png') }}" alt="Sound Indicator Gauge" class="w-full h-auto">
                            <div id="arrow" class="
                                /* --- Styling untuk Garis Panah --- */
                                absolute left-1/2 bottom-0 w-1 h-[90px] bg-black dark:bg-white 
                                origin-bottom transition-transform duration-500 ease-in-out
                                
                                /* --- Styling untuk Lingkaran Pangkal (menggunakan 'before') --- */
                                before:content-[''] before:absolute before:w-[16px] before:h-[16px] 
                                before:bg-black dark:before:bg-white before:rounded-full before:left-1/2 before:bottom-[-8px] 
                                before:-translate-x-1/2
                                
                                /* --- Styling untuk Segitiga Ujung (menggunakan 'after') --- */
                                after:content-[''] after:absolute after:w-0 after:h-0
                                after:border-l-[8px] after:border-l-transparent
                                after:border-r-[8px] after:border-r-transparent
                                after:border-b-[16px] 
                                after:border-b-black dark:after:border-b-white
                                after:left-1/2 after:top-[-14px] after:-translate-x-1/2"
                                style="transform: rotate(-90deg);">
                            </div>
                        </div>

                        <div class="text-center mt-6 text-3xl font-bold text-black dark:text-white">
                            <span id="dbValue">00</span><span class="text-black dark:text-white"> dB</span>
                        </div>

                    </div>
                </div>


                <div class="p-4 sm:p-8 bg-colorBackgroundLight dark:bg-colorBackgroundDark rounded-lg" style="box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);">

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl inline-block bg-gradient-to-r from-colorHigh to-colorNormal bg-clip-text text-transparent">
                            Sound Log
                        </h2>
                        <button id="start-button" class="ms-4 primary-button-themed">
                            Start
                        </button>
                    </div>

                    <div class="space-y-3 text-gray-600 dark:text-gray-300 text-base">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600 dark:text-gray-400">Account Name</span>
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600 dark:text-gray-400">Microphone Status</span>
                            <span class="font-bold text-red-500">Offline</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600 dark:text-gray-400">Timer</span>
                            <span id="timer-display">00 h : 00 m : 00 s</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600 dark:text-gray-400">dB Minimum</span>
                            <span>00 dB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600 dark:text-gray-400">dB Maximum</span>
                            <span>100 dB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600 dark:text-gray-400">dB Average</span>
                            <span>50 dB</span>
                        </div>
                    </div>

                    <hr class="border-gray-700 my-4">

                    <div class="flex justify-between items-center text-base">
                        <span class="font-bold text-gray-500 dark:text-gray-300">Safety Level</span>
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 bg-gray-500 rounded-full"></span>
                            <span class="text-gray-600 dark:text-gray-400">None</span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="p-4 sm:p-8 bg-colorBackgroundLight dark:bg-colorBackgroundDark rounded-lg" style="box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);">
                <div class="p-6">
                    <div class="text-center">
                        <h2 class="text-2xl inline-block bg-gradient-to-r from-colorHigh to-colorNormal bg-clip-text text-transparent mb-8">
                            Sound Monitoring
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>