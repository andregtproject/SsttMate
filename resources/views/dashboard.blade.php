<x-app-layout>
    <div class="py-12 min-h-screen" style="background: linear-gradient(120deg, #f8fafc 60%, #ffd6c0 100%);">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

                <!-- Sound Indicator -->
                <div class="bg-white rounded-[16px] shadow-lg flex flex-col items-center p-8" style="min-width:370px;">
                    <h3 class="text-[2.1rem] font-bold mb-4 text-center tracking-wide">
                        <span class="text-red-500">Sound</span>
                        <span class="text-orange-400"> Indicator</span>
                    </h3>
                    <div class="flex flex-col items-center w-full relative">
                        <canvas id="gauge" width="340" height="200" style="display:block;"></canvas>
                        <div class="absolute left-1/2 top-[120px] -translate-x-1/2 w-full flex flex-col items-center pointer-events-none select-none">
                            <div class="text-5xl md:text-6xl font-extrabold flex items-end drop-shadow-lg">
                                <span id="db-value" class="text-gray-900" style="text-shadow:2px 2px 0 #ffa726;">{{ is_numeric($data['db']) ? $data['db'] : '00' }}</span>
                                <span class="ml-2 text-yellow-500 font-semibold text-2xl md:text-3xl mb-1">dB</span>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                function drawGauge(value = 0) {
                    const canvas = document.getElementById('gauge');
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    // Settings
                    const centerX = 170, centerY = 170, radius = 130, lw = 32;
                    ctx.lineWidth = lw;

                    // Draw colored arcs
                    ctx.beginPath();
                    ctx.strokeStyle = "#43a047";
                    ctx.arc(centerX, centerY, radius, Math.PI, Math.PI*1.33);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.strokeStyle = "#ffd600";
                    ctx.arc(centerX, centerY, radius, Math.PI*1.33, Math.PI*1.66);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.strokeStyle = "#ffb300";
                    ctx.arc(centerX, centerY, radius, Math.PI*1.66, Math.PI*1.88);
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.strokeStyle = "#e53935";
                    ctx.arc(centerX, centerY, radius, Math.PI*1.88, 0);
                    ctx.stroke();

                    // Draw labels
                    ctx.save();
                    ctx.font = "bold 15px Arial";
                    ctx.textAlign = "center";
                    ctx.textBaseline = "middle";
                    ctx.fillStyle = "#43a047";
                    ctx.fillText("Low", centerX - 105, centerY - 30);
                    ctx.font = "12px Arial";
                    ctx.fillText("0-40 dB", centerX - 105, centerY - 10);
                    ctx.font = "bold 15px Arial";
                    ctx.fillStyle = "#ffd600";
                    ctx.fillText("Normal", centerX - 55, centerY - 95);
                    ctx.font = "12px Arial";
                    ctx.fillText("41-60 dB", centerX - 55, centerY - 75);
                    ctx.font = "bold 15px Arial";
                    ctx.fillStyle = "#ffb300";
                    ctx.fillText("Medium", centerX + 55, centerY - 95);
                    ctx.font = "12px Arial";
                    ctx.fillText("61-80 dB", centerX + 55, centerY - 75);
                    ctx.font = "bold 15px Arial";
                    ctx.fillStyle = "#e53935";
                    ctx.fillText("High", centerX + 105, centerY - 30);
                    ctx.font = "12px Arial";
                    ctx.fillText("81+ dB", centerX + 105, centerY - 10);
                    ctx.restore();

                    // Draw needle (arrow style)
                    let val = {{ is_numeric($data['db']) ? $data['db'] : 0 }};
                    let angle = Math.PI + (val/100)*Math.PI;
                    ctx.save();
                    ctx.translate(centerX, centerY);
                    ctx.rotate(angle);
                    ctx.beginPath();
                    ctx.moveTo(0,0);
                    ctx.lineTo(-110,0);
                    ctx.lineWidth = 8;
                    ctx.strokeStyle = "#222";
                    ctx.stroke();
                    ctx.beginPath();
                    ctx.moveTo(-110,0);
                    ctx.lineTo(-90,-13);
                    ctx.lineTo(-90,13);
                    ctx.closePath();
                    ctx.fillStyle = "#222";
                    ctx.fill();
                    ctx.beginPath();
                    ctx.arc(0,0,15,0,2*Math.PI);
                    ctx.fillStyle = "#222";
                    ctx.fill();
                    ctx.restore();
                }
                drawGauge({{ is_numeric($data['db']) ? $data['db'] : 0 }});
                document.getElementById('db-value').innerText = "{{ is_numeric($data['db']) ? $data['db'] : '00' }}";
                </script>

                <!-- Sound Log -->
                <div class="bg-white rounded-[16px] shadow-lg flex flex-col justify-between mt-0 md:mt-0 p-8" style="min-width:370px;">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-[2.1rem] font-bold tracking-wide">
                                <span class="text-red-500">Sound</span>
                                <span class="text-orange-400"> Log</span>
                            </h3>
                            @if(!$isMonitoring)
                            <form method="POST" action="{{ route('monitoring.start') }}">
                                @csrf
                                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-3 px-10 text-xl rounded-[12px] shadow transition">Start</button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('monitoring.finish') }}">
                                @csrf
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-10 text-xl rounded-[12px] shadow transition">Finish</button>
                            </form>
                            @endif
                        </div>
                        <div class="mt-2 space-y-1 text-base">
                            <div class="flex justify-between"><span class="text-gray-600">Account Name</span><span class="font-bold text-gray-800">{{ $data['account'] }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Microphone Status</span><span class="text-{{ $data['mic_color'] }} font-semibold">{{ $data['mic_status'] }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Timer</span><span class="font-mono text-gray-800">{{ $data['timer'] }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">dB Minimum</span><span class="font-bold text-gray-900">{{ $data['min'] }} dB</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">dB Maximum</span><span class="font-bold text-gray-900">{{ $data['max'] }} dB</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">dB Average</span><span class="font-bold text-gray-900">{{ $data['avg'] }} dB</span></div>
                        </div>
                        <hr class="my-4 border-gray-200">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-700">Safety Level</span>
                            <span class="inline-flex items-center gap-1">
                                <span class="w-3 h-3 rounded-full bg-{{ $data['safety_color'] }} inline-block"></span>
                                <span class="text-{{ $data['safety_color'] }} font-semibold">{{ $data['safety_text'] }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sound Monitoring -->
            <div class="col-span-1 md:col-span-2">
                <div class="bg-white rounded-[16px] shadow-lg mt-4 p-8">
                    <h2 class="text-[2.1rem] font-bold mb-6 text-center tracking-wide">
                        <span class="text-red-500">Sound</span>
                        <span class="text-orange-400"> Monitoring</span>
                    </h2>
                    <div>
                        <canvas id="soundChart" height="110"></canvas>
                        <div class="flex justify-between text-base text-gray-500 mt-6 font-semibold">
                            <span>Last 1 Minute</span>
                            <span>dB</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Data for chart monitoring
    @if($isMonitoring)
    const labels = ['0s','10s','20s','30s','40s','50s','60s'];
    const data = {
        labels: labels,
        datasets: [{
            label: 'dB',
            data: [20, 40, 75, 60, 80, 55, 45],
            borderColor: '#ffa726',
            backgroundColor: 'rgba(255,167,38,0.18)',
            tension: 0.45,
            pointRadius: 5,
            pointBackgroundColor: '#ffa726',
            fill: true,
            borderWidth: 3,
        }]
    };
    @else
    const labels = ['0s','10s','20s','30s','40s','50s','60s'];
    const data = {
        labels: labels,
        datasets: [{
            label: 'dB',
            data: [0,0,0,0,0,0,0],
            borderColor: '#e0e0e0',
            backgroundColor: 'rgba(200,200,200,0.10)',
            tension: 0.45,
            pointRadius: 0,
            fill: true,
            borderWidth: 2,
        }]
    };
    @endif
    const config = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: {{ $isMonitoring ? 'true' : 'false' }},
                    backgroundColor: '#fff',
                    titleColor: '#222',
                    bodyColor: '#222',
                    borderColor: '#ffa726',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    caretSize: 7,
                    caretPadding: 8,
                }
            },
            scales: {
                x: {
                    grid: { color: '#f3f3f3' },
                    ticks: { color: '#888', font: { size: 15, weight: 'bold' } }
                },
                y: {
                    min: 0,
                    max: 100,
                    ticks: { stepSize: 20, color: '#888', font: { size: 15, weight: 'bold' } },
                    grid: { color: '#f3f3f3' }
                }
            }
        }
    };
    new Chart(document.getElementById('soundChart'), config);
    </script>
</x-app-layout>