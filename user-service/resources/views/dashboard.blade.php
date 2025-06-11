<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('🔊 Sound Level Dashboard - Ruangan A') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen bg-gradient-to-br from-gray-50 to-orange-100 dark:from-gray-900 dark:to-gray-800">
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
                                    <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                                @elseif($connectionStatus === 'warning')
                                    <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                                @else
                                    <div class="w-4 h-4 bg-red-500 rounded-full"></div>
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
                                    </span>
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $connectionMessage }}</p>
                            </div>
                            <div class="ml-auto">
                                <button onclick="refreshSoundData()" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

                <!-- Sound Indicator -->
                <div class="bg-white dark:bg-gray-800 rounded-[16px] shadow-lg flex flex-col items-center p-8" style="min-width:370px;">
                    <h3 class="text-[2.1rem] font-bold mb-4 text-center tracking-wide">
                        <span class="text-red-500">Sound</span>
                        <span class="text-orange-400"> Indicator</span>
                    </h3>
                    <div class="flex flex-col items-center w-full relative">
                        <canvas id="gauge" width="340" height="200" style="display:block;"></canvas>
                        <div class="absolute left-1/2 top-[120px] -translate-x-1/2 w-full flex flex-col items-center pointer-events-none select-none">
                            <div class="text-5xl md:text-6xl font-extrabold flex items-end drop-shadow-lg">
                                <span id="sound-level-value" class="text-gray-900 dark:text-white" style="text-shadow:2px 2px 0 #ffa726;">{{ is_numeric($data['sound_level']) ? $data['sound_level'] : '00' }}</span>
                                <span class="ml-2 text-yellow-500 font-semibold text-2xl md:text-3xl mb-1">dB</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sound Log -->
                <div class="bg-white dark:bg-gray-800 rounded-[16px] shadow-lg flex flex-col justify-between mt-0 md:mt-0 p-8" style="min-width:370px;">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-[2.1rem] font-bold tracking-wide">
                                <span class="text-red-500">Sound</span>
                                <span class="text-orange-400"> Log</span>
                            </h3>
                            @if(!$isMonitoring)
                            <form method="POST" action="{{ route('monitoring.start') }}">
                                @csrf
                                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 px-10 text-xl rounded-[12px] shadow">START</button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('monitoring.finish') }}">
                                @csrf
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-10 text-xl rounded-[12px] shadow">Finish</button>
                            </form>
                            @endif
                        </div>
                        <div class="mt-2 space-y-1 text-base">
                            <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">Account Name</span><span class="font-bold text-gray-800 dark:text-gray-100">{{ $data['account'] }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">Microphone Status</span><span class="mic-status-display text-{{ $data['mic_color'] }} font-semibold">{{ $data['mic_status'] }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">Timer</span><span class="timer-display font-mono text-gray-800 dark:text-gray-100">{{ $data['timer'] }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">Sound Level Minimum</span><span class="min-display font-bold text-gray-900 dark:text-gray-100">{{ $data['min'] }} dB</span></div>
                            <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">Sound Level Maximum</span><span class="max-display font-bold text-gray-900 dark:text-gray-100">{{ $data['max'] }} dB</span></div>
                            <div class="flex justify-between"><span class="text-gray-600 dark:text-gray-300">Sound Level Average</span><span class="avg-display font-bold text-gray-900 dark:text-gray-100">{{ $data['avg'] }} dB</span></div>
                        </div>
                        <hr class="my-4 border-gray-200 dark:border-gray-600">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-700 dark:text-gray-200">Safety Level</span>
                            <span class="safety-indicator inline-flex items-center gap-1">
                                <span class="w-3 h-3 rounded-full bg-{{ $data['safety_color'] }} inline-block"></span>
                                <span class="text-{{ $data['safety_color'] }} font-semibold">{{ $data['safety_text'] }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sound Monitoring -->
            <div class="col-span-1 md:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-[16px] shadow-lg mt-4 p-8">
                    <h2 class="text-[2.1rem] font-bold mb-6 text-center tracking-wide">
                        <span class="text-red-500">Sound</span>
                        <span class="text-orange-400"> Monitoring</span>
                    </h2>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <canvas id="soundChart" height="110"></canvas>
                        <div class="flex justify-between text-base text-gray-700 dark:text-gray-300 mt-6 font-semibold">
                            <span>Last 1 Minute</span>
                            <span>Real-time Sound Level Monitoring</span>
                        </div>
                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                            <span id="data-counter">Data points: 0</span>
                            <span class="mx-2">•</span>
                            <span>Updates every 1 second</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let soundChart;
        let refreshInterval;
        let timerInterval;
        let dataUpdateCounter = 0;
        let monitoringStartTime = null;
        
        // Initialize monitoring start time if monitoring is active
        @if($isMonitoring)
        monitoringStartTime = new Date('{{ session("monitoring_start_time") }}');
        @endif
        
        function updateTimer() {
            if (monitoringStartTime) {
                const now = new Date();
                const elapsed = Math.floor((now - monitoringStartTime) / 1000);
                
                const hours = Math.floor(elapsed / 3600);
                const minutes = Math.floor((elapsed % 3600) / 60);
                const seconds = elapsed % 60;
                
                const timerString = 
                    String(hours).padStart(2, '0') + ':' +
                    String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0');
                
                const timerElements = document.querySelectorAll('.timer-display');
                timerElements.forEach(el => el.textContent = timerString);
            }
        }
        
        function startTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
            }
            timerInterval = setInterval(updateTimer, 1000); // Update every second
        }
        
        function stopTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
            monitoringStartTime = null;
        }
        
        function refreshSoundData() {
            fetch('/api/sound-data')
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data) {
                        dataUpdateCounter++;
                        
                        // Update sound indicator - now using 'db' field instead of 'amplitude'
                        const dbValue = result.data.db || 0;
                        document.getElementById('sound-level-value').textContent = dbValue;
                        drawGauge(dbValue);
                        
                        // Update JSON response display in real-time
                        if (result.response) {
                            console.log('Firebase Response:', result.response);
                            // Update the displayed JSON if element exists
                            const jsonDisplay = document.querySelector('.font-mono.bg-gray-100.dark\\:bg-gray-700');
                            if (jsonDisplay) {
                                jsonDisplay.textContent = JSON.stringify(result.response, null, 2);
                            }
                        }
                        
                        // Update stats if monitoring is active
                        if (result.stats) {
                            // Update microphone status
                            const micElements = document.querySelectorAll('.mic-status-display');
                            micElements.forEach(el => {
                                el.textContent = result.stats.mic_status;
                                el.className = `mic-status-display text-${result.stats.mic_color} font-semibold`;
                            });
                            
                            // Update min/max/avg
                            const minElements = document.querySelectorAll('.min-display');
                            minElements.forEach(el => el.textContent = result.stats.min + ' dB');
                            
                            const maxElements = document.querySelectorAll('.max-display');
                            maxElements.forEach(el => el.textContent = result.stats.max + ' dB');
                            
                            const avgElements = document.querySelectorAll('.avg-display');
                            avgElements.forEach(el => el.textContent = result.stats.avg + ' dB');
                            
                            // Update safety status
                            const safetyElement = document.querySelector('.safety-indicator');
                            if (safetyElement) {
                                safetyElement.innerHTML = `
                                    <span class="w-3 h-3 rounded-full bg-${result.stats.safety_color} inline-block"></span>
                                    <span class="text-${result.stats.safety_color} font-semibold">${result.stats.safety_text}</span>
                                `;
                            }
                        }
                        
                        // Update chart with real data
                        if (result.chart_data && soundChart) {
                            soundChart.data.labels = result.chart_data.labels;
                            soundChart.data.datasets[0].data = result.chart_data.data;
                            soundChart.update('none');
                        }
                        
                        // Update data counter and timestamp
                        document.getElementById('data-counter').textContent = `Data points: ${dataUpdateCounter}`;
                        
                        // Update last refresh timestamp
                        const now = new Date();
                        const timestamp = now.toLocaleTimeString();
                        let timestampElement = document.getElementById('last-refresh');
                        if (!timestampElement) {
                            timestampElement = document.createElement('span');
                            timestampElement.id = 'last-refresh';
                            timestampElement.className = 'text-xs text-gray-400 ml-2';
                            document.getElementById('data-counter').parentNode.appendChild(timestampElement);
                        }
                        timestampElement.textContent = `Last update: ${timestamp}`;
                        
                    } else {
                        console.warn('Failed to get valid data:', result);
                        // Show error state but continue trying
                        let errorElement = document.getElementById('refresh-error');
                        if (!errorElement) {
                            errorElement = document.createElement('div');
                            errorElement.id = 'refresh-error';
                            errorElement.className = 'text-xs text-red-500 text-center mt-2';
                            document.getElementById('data-counter').parentNode.appendChild(errorElement);
                        }
                        errorElement.textContent = 'Connection issue - retrying...';
                        
                        setTimeout(() => {
                            if (errorElement) errorElement.textContent = '';
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error refreshing sound data:', error);
                    
                    let errorElement = document.getElementById('refresh-error');
                    if (!errorElement) {
                        errorElement = document.createElement('div');
                        errorElement.id = 'refresh-error';
                        errorElement.className = 'text-xs text-red-500 text-center mt-2';
                        document.getElementById('data-counter').parentNode.appendChild(errorElement);
                    }
                    errorElement.textContent = 'Network error - retrying...';
                    
                    setTimeout(() => {
                        if (errorElement) errorElement.textContent = '';
                    }, 5000);
                });
        }

        function startAutoRefresh() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
            
            // Start immediate refresh
            refreshSoundData();
            
            // Set up periodic refresh every 1000ms (1 second)
            refreshInterval = setInterval(refreshSoundData, 1000);
        }

        function stopAutoRefresh() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
                refreshInterval = null;
                console.log('Auto-refresh stopped');
            }
        }

        function startTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
            }
            timerInterval = setInterval(updateTimer, 1000); // Update every second
        }
        
        function stopTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
            monitoringStartTime = null;
        }
        
        function refreshSoundData() {
            fetch('/api/sound-data')
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data) {
                        dataUpdateCounter++;
                        
                        // Update sound indicator - now using 'db' field instead of 'amplitude'
                        const dbValue = result.data.db || 0;
                        document.getElementById('sound-level-value').textContent = dbValue;
                        drawGauge(dbValue);
                        
                        // Update JSON response display in real-time
                        if (result.response) {
                            console.log('Firebase Response:', result.response);
                            // Update the displayed JSON if element exists
                            const jsonDisplay = document.querySelector('.font-mono.bg-gray-100.dark\\:bg-gray-700');
                            if (jsonDisplay) {
                                jsonDisplay.textContent = JSON.stringify(result.response, null, 2);
                            }
                        }
                        
                        // Update stats if monitoring is active
                        if (result.stats) {
                            // Update microphone status
                            const micElements = document.querySelectorAll('.mic-status-display');
                            micElements.forEach(el => {
                                el.textContent = result.stats.mic_status;
                                el.className = `mic-status-display text-${result.stats.mic_color} font-semibold`;
                            });
                            
                            // Update min/max/avg
                            const minElements = document.querySelectorAll('.min-display');
                            minElements.forEach(el => el.textContent = result.stats.min + ' dB');
                            
                            const maxElements = document.querySelectorAll('.max-display');
                            maxElements.forEach(el => el.textContent = result.stats.max + ' dB');
                            
                            const avgElements = document.querySelectorAll('.avg-display');
                            avgElements.forEach(el => el.textContent = result.stats.avg + ' dB');
                            
                            // Update safety status
                            const safetyElement = document.querySelector('.safety-indicator');
                            if (safetyElement) {
                                safetyElement.innerHTML = `
                                    <span class="w-3 h-3 rounded-full bg-${result.stats.safety_color} inline-block"></span>
                                    <span class="text-${result.stats.safety_color} font-semibold">${result.stats.safety_text}</span>
                                `;
                            }
                        }
                        
                        // Update chart with real data
                        if (result.chart_data && soundChart) {
                            soundChart.data.labels = result.chart_data.labels;
                            soundChart.data.datasets[0].data = result.chart_data.data;
                            soundChart.update('none');
                        }
                        
                        // Update data counter and timestamp
                        document.getElementById('data-counter').textContent = `Data points: ${dataUpdateCounter}`;
                        
                        // Update last refresh timestamp
                        const now = new Date();
                        const timestamp = now.toLocaleTimeString();
                        let timestampElement = document.getElementById('last-refresh');
                        if (!timestampElement) {
                            timestampElement = document.createElement('span');
                            timestampElement.id = 'last-refresh';
                            timestampElement.className = 'text-xs text-gray-400 ml-2';
                            document.getElementById('data-counter').parentNode.appendChild(timestampElement);
                        }
                        timestampElement.textContent = `Last update: ${timestamp}`;
                        
                    } else {
                        console.warn('Failed to get valid data:', result);
                        // Show error state but continue trying
                        let errorElement = document.getElementById('refresh-error');
                        if (!errorElement) {
                            errorElement = document.createElement('div');
                            errorElement.id = 'refresh-error';
                            errorElement.className = 'text-xs text-red-500 text-center mt-2';
                            document.getElementById('data-counter').parentNode.appendChild(errorElement);
                        }
                        errorElement.textContent = 'Connection issue - retrying...';
                        
                        setTimeout(() => {
                            if (errorElement) errorElement.textContent = '';
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error refreshing sound data:', error);
                    
                    let errorElement = document.getElementById('refresh-error');
                    if (!errorElement) {
                        errorElement = document.createElement('div');
                        errorElement.id = 'refresh-error';
                        errorElement.className = 'text-xs text-red-500 text-center mt-2';
                        document.getElementById('data-counter').parentNode.appendChild(errorElement);
                    }
                    errorElement.textContent = 'Network error - retrying...';
                    
                    setTimeout(() => {
                        if (errorElement) errorElement.textContent = '';
                    }, 5000);
                });
        }

        function startAutoRefresh() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
            
            // Start immediate refresh
            refreshSoundData();
            
            // Set up periodic refresh every 1000ms (1 second)
            refreshInterval = setInterval(refreshSoundData, 1000);
        }

        function stopAutoRefresh() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
                refreshInterval = null;
                console.log('Auto-refresh stopped');
            }
        }

        function startTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
            }
            timerInterval = setInterval(updateTimer, 1000); // Update every second
        }
        
        function stopTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
            monitoringStartTime = null;
        }
        
        function drawGauge(value = 0) {
            const canvas = document.getElementById('gauge');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Settings
            const centerX = 170, centerY = 170, radius = 130, lw = 32;
            ctx.lineWidth = lw;

            // Draw colored arcs with new ranges
            ctx.beginPath();
            ctx.strokeStyle = "#43a047"; // Green for Low (0-50)
            ctx.arc(centerX, centerY, radius, Math.PI, Math.PI*1.25);
            ctx.stroke();
            ctx.beginPath();
            ctx.strokeStyle = "#ffd600"; // Yellow for Normal (51-70)
            ctx.arc(centerX, centerY, radius, Math.PI*1.25, Math.PI*1.5);
            ctx.stroke();
            ctx.beginPath();
            ctx.strokeStyle = "#ffb300"; // Orange for Moderate (71-100)
            ctx.arc(centerX, centerY, radius, Math.PI*1.5, Math.PI*1.85);
            ctx.stroke();
            ctx.beginPath();
            ctx.strokeStyle = "#e53935"; // Red for High (101+)
            ctx.arc(centerX, centerY, radius, Math.PI*1.85, 0);
            ctx.stroke();

            // Draw labels with new ranges
            ctx.save();
            ctx.font = "bold 15px Arial";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            ctx.fillStyle = "#43a047";
            ctx.fillText("Low", centerX - 105, centerY - 30);
            ctx.font = "12px Arial";
            ctx.fillText("0-50 dB", centerX - 105, centerY - 10);
            ctx.font = "bold 15px Arial";
            ctx.fillStyle = "#ffd600";
            ctx.fillText("Normal", centerX - 35, centerY - 95);
            ctx.font = "12px Arial";
            ctx.fillText("51-70 dB", centerX - 35, centerY - 75);
            ctx.font = "bold 15px Arial";
            ctx.fillStyle = "#ffb300";
            ctx.fillText("Moderate", centerX + 45, centerY - 95);
            ctx.font = "12px Arial";
            ctx.fillText("71-100 dB", centerX + 45, centerY - 75);
            ctx.font = "bold 15px Arial";
            ctx.fillStyle = "#e53935";
            ctx.fillText("High", centerX + 105, centerY - 30);
            ctx.font = "12px Arial";
            ctx.fillText("101+ dB", centerX + 105, centerY - 10);
            ctx.restore();

            // Draw needle (improved version)
            let val = Math.min(Math.max(value, 0), 150); // Clamp between 0-150
            let angle = Math.PI + (val/150)*Math.PI; // Calculate angle based on value
            ctx.save();
            ctx.translate(centerX, centerY);
            ctx.rotate(angle);
            
            // Draw needle shadow for depth
            ctx.save();
            ctx.translate(2, 2);
            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(-100, 0);
            ctx.lineWidth = 8;
            ctx.strokeStyle = "rgba(0, 0, 0, 0.3)";
            ctx.stroke();
            ctx.restore();
            
            // Draw needle shaft
            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(-100, 0);
            ctx.lineWidth = 6;
            ctx.strokeStyle = "#FF4444"; // Red needle for better visibility
            ctx.stroke();
            
            // Draw needle tip (arrow head)
            ctx.beginPath();
            ctx.moveTo(-100, 0);
            ctx.lineTo(-85, -8);
            ctx.lineTo(-85, 8);
            ctx.closePath();
            ctx.fillStyle = "#FF4444";
            ctx.fill();
            ctx.strokeStyle = "#CC0000";
            ctx.lineWidth = 2;
            ctx.stroke();
            
            // Draw center circle
            ctx.beginPath();
            ctx.arc(0, 0, 18, 0, 2*Math.PI);
            ctx.fillStyle = "#FFFFFF";
            ctx.fill();
            ctx.strokeStyle = "#333333";
            ctx.lineWidth = 3;
            ctx.stroke();
            
            // Add small center dot
            ctx.beginPath();
            ctx.arc(0, 0, 6, 0, 2*Math.PI);
            ctx.fillStyle = "#FF4444";
            ctx.fill();
            
            ctx.restore();
        }

        // Initialize with immediate start
        document.addEventListener('DOMContentLoaded', function() {
            drawGauge({{ is_numeric($data['sound_level']) ? $data['sound_level'] : 0 }});
            document.getElementById('sound-level-value').innerText = "{{ is_numeric($data['sound_level']) ? $data['sound_level'] : '00' }}";
            
            // Start auto-refresh immediately
            startAutoRefresh();
            
            // Start timer if monitoring is active
            @if($isMonitoring)
            startTimer();
            @endif
            
            console.log('Dashboard initialized with auto-refresh enabled');
        });
        
        // Ensure refresh continues when page becomes visible again
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                console.log('Page visible - ensuring auto-refresh is active');
                startAutoRefresh();
            } else {
                console.log('Page hidden - maintaining auto-refresh');
            }
        });
        
        // Handle form submissions for start/finish monitoring
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.action.includes('monitoring.start')) {
                monitoringStartTime = new Date();
                startTimer();
                startAutoRefresh();
            } else if (form.action.includes('monitoring.finish')) {
                stopTimer();
            }
        });
        
        // Only stop auto-refresh when user actually leaves the page
        window.addEventListener('beforeunload', function() {
            stopAutoRefresh();
            stopTimer();
            console.log('Page unloading - stopped auto-refresh');
        });
        
        // Add manual refresh button functionality
        window.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'r') {
                e.preventDefault();
                refreshSoundData();
                console.log('Manual refresh triggered');
            }
        });
    </script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Data for chart monitoring
    @if($isMonitoring && !empty($data['chart_data']['data']))
    const initialLabels = @json($data['chart_data']['labels']);
    const initialData = @json($data['chart_data']['data']);
    @else
    const initialLabels = [];
    const initialData = [];
    @endif
    
    const chartData = {
        labels: initialLabels,
        datasets: [{
            label: 'Sound Level (dB)',
            data: initialData,
            borderColor: '#ffa726',
            backgroundColor: 'rgba(255,167,38,0.18)',
            tension: 0.45,
            pointRadius: 3,
            pointBackgroundColor: '#ffa726',
            fill: true,
            borderWidth: 3,
        }]
    };
    
    const config = {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            animation: false, // Disable animation for real-time updates
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    backgroundColor: '#fff',
                    titleColor: '#222',
                    bodyColor: '#222',
                    borderColor: '#ffa726',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    caretSize: 7,
                    caretPadding: 8,
                    callbacks: {
                        label: function(context) {
                            return `Sound Level: ${context.parsed.y} dB`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: '#f3f3f3' },
                    ticks: { color: '#888', font: { size: 12, weight: 'bold' } }
                },
                y: {
                    min: 0,
                    max: 150, // Adjust max to accommodate new ranges
                    ticks: { stepSize: 25, color: '#888', font: { size: 12, weight: 'bold' } },
                    grid: { color: '#f3f3f3' }
                }
            }
        }
    };
    soundChart = new Chart(document.getElementById('soundChart'), config);
    </script>
</x-app-layout>