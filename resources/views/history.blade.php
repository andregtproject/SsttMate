<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-colorBackgroundLight dark:bg-colorBackgroundDark rounded-lg" style="box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);">

                <div class="text-center">
                    <h2 class="text-2xl inline-block bg-gradient-to-r from-colorHigh to-colorNormal bg-clip-text text-transparent mb-8">
                        Log History
                    </h2>
                </div>

                @if(isset($histories) && count($histories) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-300">
                            <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-3">Date/Time</th>
                                    <th class="px-6 py-3">dB Average</th>
                                    <th class="px-6 py-3">Safety Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($histories as $history)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ $history->created_at->format('d-m-Y H:i') }}</td>
                                        <td class="px-6 py-4">{{ $history->db_average }} dB</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $level = '';
                                                $color = '';
                                                if($history->db_average <= 55) {
                                                    $level = 'Safe';
                                                    $color = 'text-green-500';
                                                } elseif($history->db_average <= 70) {
                                                    $level = 'Caution';
                                                    $color = 'text-yellow-500';
                                                } elseif($history->db_average <= 85) {
                                                    $level = 'Low Risk';
                                                    $color = 'text-orange-500';
                                                } else {
                                                    $level = 'Danger';
                                                    $color = 'text-red-500';
                                                }
                                            @endphp
                                            <span class="font-bold {{ $color }}">{{ $level }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex justify-center items-center py-16">
                        <div class="w-80 h-80 flex flex-col justify-center items-center text-center p-4">
                            <div class="text-7xl mb-4">😵</div>
                            <h3 class="text-2xl font-bold text-gray-200 mb-2">
                                Data Not Found
                            </h3>
                            <p class="text-gray-400 max-w-xs font-normal">
                                Sorry, no history data exists yet.
                                Please check back later.
                            </p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>