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
                    <div class="w-full overflow-x-auto">
                        <canvas id="soundChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data awal: 60 menit terakhir (1 jam, 1 data per menit)
        let labels = Array.from({length: 60}, (_, i) => `${i+1}m`);
        let data = Array.from({length: 60}, () => 0);

        // Fungsi untuk cek mode terang/gelap
        function isDarkMode() {
            return document.documentElement.classList.contains('dark');
        }

        function getChartColors() {
            if (isDarkMode()) {
                return {
                    grid: '#444',
                    label: '#ccc',
                    bg: 'rgba(255,165,0,0.1)'
                }
            } else {
                return {
                    grid: '#bbb',
                    label: '#333',
                    bg: 'rgba(255,165,0,0.08)'
                }
            }
        }

        let chartColors = getChartColors();

        const ctx = document.getElementById('soundChart').getContext('2d');
        const soundChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'dB',
                    data: data,
                    borderColor: 'orange',
                    backgroundColor: chartColors.bg,
                    pointBackgroundColor: 'orange',
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        title: { display: true, text: 'dB', color: chartColors.label },
                        ticks: { color: chartColors.label },
                        grid: { color: chartColors.grid }
                    },
                    x: {
                        title: { display: true, text: 'Last 1 Hour (per Minute)', color: chartColors.label },
                        ticks: { color: chartColors.label },
                        grid: { color: chartColors.grid }
                    }
                }
            }
        });

        // Update chart colors jika mode berubah
        const observer = new MutationObserver(() => {
            chartColors = getChartColors();
            soundChart.options.scales.x.ticks.color = chartColors.label;
            soundChart.options.scales.x.grid.color = chartColors.grid;
            soundChart.options.scales.x.title.color = chartColors.label;
            soundChart.options.scales.y.ticks.color = chartColors.label;
            soundChart.options.scales.y.grid.color = chartColors.grid;
            soundChart.options.scales.y.title.color = chartColors.label;
            soundChart.data.datasets[0].backgroundColor = chartColors.bg;
            soundChart.update();
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

        let intervalId = null;
        let minuteBuffer = [];
        let minuteCount = 0;

        document.getElementById('start-button').addEventListener('click', function() {
            if (intervalId) return; // Jangan double start

            intervalId = setInterval(() => {
                fetch('/api/sound/latest')
                    .then(response => response.json())
                    .then(result => {
                        const dbValue = result.db ?? 0;
                        minuteBuffer.push(dbValue);

                        // Update indikator dB di atas (real-time)
                        document.getElementById('dbValue').textContent = dbValue;

                        // Setiap 60 detik (1 menit), ambil rata-rata dan update chart
                        minuteCount++;
                        if (minuteCount >= 60) {
                            const avg = Math.round(minuteBuffer.reduce((a, b) => a + b, 0) / minuteBuffer.length);
                            soundChart.data.datasets[0].data.push(avg);
                            soundChart.data.datasets[0].data.shift();
                            soundChart.update();
                            minuteBuffer = [];
                            minuteCount = 0;
                        }
                    })
                    .catch(() => {
                        minuteBuffer.push(0);
                        document.getElementById('dbValue').textContent = '00';
                        minuteCount++;
                        if (minuteCount >= 60) {
                            soundChart.data.datasets[0].data.push(0);
                            soundChart.data.datasets[0].data.shift();
                            soundChart.update();
                            minuteBuffer = [];
                            minuteCount = 0;
                        }
                    });
            }, 1000);
        });
    </script>
</x-app-layout>