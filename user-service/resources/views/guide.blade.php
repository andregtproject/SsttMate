<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('📖 User Guide') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen bg-gradient-to-br from-gray-50 to-orange-100 dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <h3 class="text-3xl font-bold mb-8 text-center">
                        <span class="text-red-500">SsttMate</span>
                        <span class="text-orange-400"> User Guide</span>
                    </h3>
                    
                    <div class="space-y-8">
                        <!-- Getting Started -->
                        <section>
                            <h4 class="text-xl font-semibold mb-4 text-yellow-600">🚀 Getting Started</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <ol class="list-decimal list-inside space-y-2">
                                    <li>Navigate to the Dashboard from the Home menu</li>
                                    <li>Click the "Start" button to begin sound monitoring</li>
                                    <li>Timer will start counting and data will refresh every 2 seconds</li>
                                    <li>Monitor real-time sound levels on the gauge and chart</li>
                                    <li>Click "Finish" to stop monitoring session</li>
                                </ol>
                            </div>
                        </section>

                        <!-- Understanding Sound Levels -->
                        <section>
                            <h4 class="text-xl font-semibold mb-4 text-green-600">🔊 Understanding Sound Levels</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                                    <h5 class="font-semibold text-green-700 dark:text-green-300">Low (0-50 dB)</h5>
                                    <p class="text-sm text-green-600 dark:text-green-400">Very quiet environment, whisper level</p>
                                </div>
                                <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                                    <h5 class="font-semibold text-yellow-700 dark:text-yellow-300">Normal (51-70 dB)</h5>
                                    <p class="text-sm text-yellow-600 dark:text-yellow-400">Normal conversation, office environment</p>
                                </div>
                                <div class="bg-orange-50 dark:bg-orange-900 p-4 rounded-lg">
                                    <h5 class="font-semibold text-orange-700 dark:text-orange-300">Moderate (71-100 dB)</h5>
                                    <p class="text-sm text-orange-600 dark:text-orange-400">Busy street, loud conversation</p>
                                </div>
                                <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg">
                                    <h5 class="font-semibold text-red-700 dark:text-red-300">High (101+ dB)</h5>
                                    <p class="text-sm text-red-600 dark:text-red-400">Very noisy, potentially harmful. Minimum safe monitoring level: 84 dB</p>
                                </div>
                            </div>
                        </section>

                        <!-- Features -->
                        <section>
                            <h4 class="text-xl font-semibold mb-4 text-blue-600">⚡ Features</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <ul class="list-disc list-inside space-y-2">
                                    <li><strong>Real-time Monitoring:</strong> Live sound level updates every 2 seconds</li>
                                    <li><strong>Visual Gauge:</strong> Easy-to-read color-coded sound meter with dynamic needle</li>
                                    <li><strong>Live Statistics:</strong> Real-time min, max, and average sound levels</li>
                                    <li><strong>Session Timer:</strong> Automatic timer starts when monitoring begins and tracks elapsed time</li>
                                    <li><strong>Dynamic Chart:</strong> Real-time chart showing last 1 minute of sound level data</li>
                                    <li><strong>Microphone Status:</strong> Shows connection status to sound monitoring device</li>
                                    <li><strong>Safety Indicators:</strong> Color-coded safety levels (Low, Normal, Moderate, High)</li>
                                    <li><strong>Data Counter:</strong> Shows number of data points collected during session</li>
                                    <li><strong>Auto Refresh:</strong> Continuous data updates during monitoring session</li>
                                    <li><strong>Dark Mode:</strong> Toggle between light and dark themes</li>
                                </ul>
                            </div>
                        </section>

                        <!-- Troubleshooting -->
                        <section>
                            <h4 class="text-xl font-semibold mb-4 text-purple-600">🔧 Troubleshooting</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="space-y-4">
                                    <div>
                                        <h5 class="font-semibold">Microphone not working?</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Check browser permissions and ensure microphone access is allowed</p>
                                    </div>
                                    <div>
                                        <h5 class="font-semibold">No data showing?</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Click the "Refresh Now" button or check your internet connection</p>
                                    </div>
                                    <div>
                                        <h5 class="font-semibold">Connection issues?</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Check the status indicator at the top of the dashboard</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Keyboard Shortcuts -->
                        <section>
                            <h4 class="text-xl font-semibold mb-4 text-indigo-600">⌨️ Keyboard Shortcuts</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <ul class="list-disc list-inside space-y-1">
                                    <li><kbd class="px-2 py-1 bg-gray-200 dark:bg-gray-600 rounded">Ctrl + R</kbd> - Refresh sound data</li>
                                </ul>
                            </div>
                        </section>
                    </div>

                    <div class="mt-8 text-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-yellow-400 hover:bg-yellow-500 text-black font-bold rounded-lg transition">
                            ← Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
