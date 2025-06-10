<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('🔊 Sound Level Dashboard - Ruangan A') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Connection Status Card -->
            <div class="mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg border-l-4 
                    @if($connectionStatus === 'success') border-green-500 
                    @elseif($connectionStatus === 'warning') border-yellow-500 
                    @else border-red-500 @endif">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($connectionStatus === 'success')
                                    <div class="w-4 h-4 bg-green-500 rounded-full animate-pulse"></div>
                                @elseif($connectionStatus === 'warning')
                                    <div class="w-4 h-4 bg-yellow-500 rounded-full animate-pulse"></div>
                                @else
                                    <div class="w-4 h-4 bg-red-500 rounded-full animate-pulse"></div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Sound Service Status: 
                                    <span class="
                                        @if($connectionStatus === 'success') text-green-600 dark:text-green-400
                                        @elseif($connectionStatus === 'warning') text-yellow-600 dark:text-yellow-400
                                        @else text-red-600 dark:text-red-400 @endif
                                    ">
                                        {{ ucfirst($connectionStatus) }}
                                    </span>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $connectionMessage }}</p>
                            </div>
                            <div class="ml-auto">
                                <button onclick="refreshSoundData()" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200 transform hover:scale-105">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Refresh Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Sound Level Card -->
            <div class="bg-gradient-to-br from-indigo-50 to-blue-100 dark:from-gray-800 dark:to-gray-700 overflow-hidden shadow-xl sm:rounded-xl border border-gray-200 dark:border-gray-600">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                                🏢 Ruangan A
                                <span class="ml-3 text-sm bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full">
                                    Live Monitoring
                                </span>
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Real-time sound level monitoring system</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Last updated</p>
                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300" id="last-updated">
                                {{ now()->format('H:i:s') }}
                            </p>
                        </div>
                    </div>

                    <div id="sound-data-container">
                        @if($soundData)
                            <!-- Sound Data Display -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Amplitude Gauge -->
                                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
                                    <div class="text-center">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Sound Amplitude</h3>
                                        
                                        <!-- Circular Progress -->
                                        <div class="relative w-40 h-40 mx-auto mb-4">
                                            <svg class="w-40 h-40 transform -rotate-90" viewBox="0 0 144 144">
                                                <circle cx="72" cy="72" r="65" stroke="currentColor" stroke-width="8" 
                                                    fill="transparent" class="text-gray-200 dark:text-gray-600"/>
                                                <circle cx="72" cy="72" r="65" stroke="currentColor" stroke-width="8" 
                                                    fill="transparent" 
                                                    stroke-dasharray="{{ 2 * pi() * 65 }}"
                                                    stroke-dashoffset="{{ 2 * pi() * 65 - (($soundData['amplitude'] ?? 0) / 150 * 2 * pi() * 65) }}"
                                                    class="@if(($soundData['amplitude'] ?? 0) > 100) text-red-500 @elseif(($soundData['amplitude'] ?? 0) > 70) text-yellow-500 @else text-green-500 @endif transition-all duration-1000"
                                                    id="amplitude-circle"/>
                                            </svg>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="text-center">
                                                    <span class="text-3xl font-bold text-gray-800 dark:text-gray-200" id="amplitude-value">
                                                        {{ $soundData['amplitude'] ?? 'N/A' }}
                                                    </span>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">dB</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Level Indicator -->
                                        <div class="flex justify-center space-x-2 mb-4">
                                            <div class="flex items-center text-sm">
                                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                                <span class="text-gray-600 dark:text-gray-400">Quiet (0-70)</span>
                                            </div>
                                            <div class="flex items-center text-sm">
                                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                                <span class="text-gray-600 dark:text-gray-400">Normal (70-100)</span>
                                            </div>
                                            <div class="flex items-center text-sm">
                                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                                <span class="text-gray-600 dark:text-gray-400">Loud (100+)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Card -->
                                <div class="space-y-6">
                                    <!-- Noise Level Status -->
                                    <div class="bg-gradient-to-r from-{{ ($soundData['isLoud'] ?? false) ? 'red' : 'green' }}-500 to-{{ ($soundData['isLoud'] ?? false) ? 'red' : 'green' }}-600 rounded-xl p-6 text-white shadow-lg">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold mb-2">Noise Level Status</h3>
                                                <p class="text-3xl font-bold" id="noise-status">
                                                    {{ ($soundData['isLoud'] ?? false) ? 'LOUD' : 'QUIET' }}
                                                </p>
                                                <p class="text-sm opacity-90" id="noise-description">
                                                    {{ ($soundData['isLoud'] ?? false) ? 'Environment is noisy' : 'Environment is peaceful' }}
                                                </p>
                                            </div>
                                            <div class="text-6xl opacity-80">
                                                @if($soundData['isLoud'] ?? false)
                                                    🔊
                                                @else
                                                    🔇
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Info Card -->
                                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Room Information</h3>
                                        <div class="space-y-3">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Room ID:</span>
                                                <span class="font-medium text-gray-800 dark:text-gray-200">ROOM-A-001</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Sensor Type:</span>
                                                <span class="font-medium text-gray-800 dark:text-gray-200">Digital Microphone</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Update Frequency:</span>
                                                <span class="font-medium text-gray-800 dark:text-gray-200">Real-time</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Auto Refresh:</span>
                                                <span class="font-medium text-green-600 dark:text-green-400">
                                                    <span id="auto-refresh-status">Enabled (5s)</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Activity Log -->
                            <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Recent Activity</h3>
                                <div id="activity-log" class="space-y-2 max-h-32 overflow-y-auto">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 flex justify-between">
                                        <span>System initialized</span>
                                        <span>{{ now()->format('H:i:s') }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- No Data State -->
                            <div class="text-center py-16">
                                <div class="text-6xl mb-4">🚫</div>
                                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Sound Data Available</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6">Unable to retrieve data from the sound monitoring service.</p>
                                <button onclick="refreshSoundData()" 
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Try Again
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let lastAmplitude = {{ $soundData['amplitude'] ?? 0 }};
        let autoRefreshInterval;
        let activityLog = [];

        function addToActivityLog(message) {
            const now = new Date().toLocaleTimeString();
            activityLog.unshift({ message, time: now });
            
            // Keep only last 10 entries
            if (activityLog.length > 10) {
                activityLog = activityLog.slice(0, 10);
            }
            
            updateActivityLogDisplay();
        }

        function updateActivityLogDisplay() {
            const logContainer = document.getElementById('activity-log');
            if (logContainer) {
                logContainer.innerHTML = activityLog.map(entry => 
                    `<div class="text-sm text-gray-600 dark:text-gray-400 flex justify-between">
                        <span>${entry.message}</span>
                        <span>${entry.time}</span>
                    </div>`
                ).join('');
            }
        }

        function refreshSoundData() {
            addToActivityLog('Refreshing data...');
            
            fetch('/api/sound-data')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        updateSoundDisplay(data.data);
                        
                        // Check if amplitude changed significantly
                        const newAmplitude = data.data.amplitude || 0;
                        if (Math.abs(newAmplitude - lastAmplitude) > 5) {
                            addToActivityLog(`Amplitude changed from ${lastAmplitude}dB to ${newAmplitude}dB`);
                            lastAmplitude = newAmplitude;
                        }
                        
                        addToActivityLog('Data updated successfully');
                    } else {
                        addToActivityLog('No data received from service');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    addToActivityLog('Failed to refresh data');
                });
        }

        function updateSoundDisplay(soundData) {
            // Update amplitude value
            const amplitudeValue = document.getElementById('amplitude-value');
            if (amplitudeValue) {
                amplitudeValue.textContent = soundData.amplitude || 'N/A';
            }
            
            // Update circular progress
            const circle = document.getElementById('amplitude-circle');
            if (circle) {
                const circumference = 2 * Math.PI * 65;
                const progress = Math.min((soundData.amplitude || 0) / 150, 1);
                const offset = circumference - (progress * circumference);
                circle.style.strokeDashoffset = offset;
                
                // Update color based on amplitude
                circle.className = circle.className.replace(/text-(red|yellow|green)-500/g, '');
                if (soundData.amplitude > 100) {
                    circle.classList.add('text-red-500');
                } else if (soundData.amplitude > 70) {
                    circle.classList.add('text-yellow-500');
                } else {
                    circle.classList.add('text-green-500');
                }
            }
            
            // Update noise status
            const noiseStatus = document.getElementById('noise-status');
            const noiseDescription = document.getElementById('noise-description');
            if (noiseStatus && noiseDescription) {
                noiseStatus.textContent = soundData.isLoud ? 'LOUD' : 'QUIET';
                noiseDescription.textContent = soundData.isLoud ? 'Environment is noisy' : 'Environment is peaceful';
            }
            
            // Update last updated time
            const lastUpdated = document.getElementById('last-updated');
            if (lastUpdated) {
                lastUpdated.textContent = new Date().toLocaleTimeString();
            }
        }

        function startAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
            
            autoRefreshInterval = setInterval(() => {
                refreshSoundData();
            }, 5000); // Refresh every 5 seconds
            
            addToActivityLog('Auto-refresh started (5s interval)');
        }

        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
            }
            
            const status = document.getElementById('auto-refresh-status');
            if (status) {
                status.textContent = 'Disabled';
                status.className = 'font-medium text-red-600 dark:text-red-400';
            }
            
            addToActivityLog('Auto-refresh stopped');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Start auto-refresh
            startAutoRefresh();
            
            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.key === 'r' && (e.ctrlKey || e.metaKey)) {
                    e.preventDefault();
                    refreshSoundData();
                }
            });
            
            addToActivityLog('Dashboard initialized');
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
        });
    </script>
</x-app-layout>
