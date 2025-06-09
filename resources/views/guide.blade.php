<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Kontainer utama halaman dengan background gelap --}}
            <div class="p-4 sm:p-8 bg-colorBackgroundLight dark:bg-colorBackgroundDark rounded-lg" style="box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);">

                {{-- Judul Halaman --}}
                <div class="text-center">
                    <h2 class="text-2xl inline-block bg-gradient-to-r from-colorHigh to-colorNormal bg-clip-text text-transparent mb-8">
                        Safety Guide
                    </h2>
                </div>

                {{-- Kontainer Tabel --}}
                <table class="w-full text-sm rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-l text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3 w-16 text-center">
                                No.
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                dB Range
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Safety Level
                            </th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-grey dark:divide-gray-700">
                        <tr class="bg-colorBackgroundLight dark:bg-colorBackgroundDark">
                            <td class="px-4 py-4 text-center font-medium text-gray-900 dark:text-white">1</td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center dark:text-white">
                                0 - 40 dB
                            </th>
                            <td class="px-6 py-4 font-semibold text-colorLow text-center">
                                <span class="inline-block w-3 h-3 rounded-full me-2 bg-colorLow"></span>
                                Safe
                            </td>
                        </tr>
                        
                        <tr class="bg-colorBackgroundLight dark:bg-colorBackgroundDark">
                            <td class="px-4 py-4 text-center font-medium text-gray-900 dark:text-white">2</td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center dark:text-white">
                                41 - 60 dB
                            </th>
                            <td class="px-6 py-4 font-semibold text-colorNormal text-center">
                                <span class="inline-block w-3 h-3 rounded-full me-2 bg-colorNormal"></span>
                                Caution
                            </td>
                        </tr>
                        
                        <tr class="bg-colorBackgroundLight dark:bg-colorBackgroundDark">
                            <td class="px-4 py-4 text-center font-medium text-gray-900 dark:text-white">3</td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center dark:text-white">
                                61 - 80 dB
                            </th>
                            <td class="px-6 py-4 font-semibold text-colorMedium text-center">
                                <span class="inline-block w-3 h-3 rounded-full me-2 bg-colorMedium"></span>
                                Low Risk
                            </td>
                        </tr>
                        
                        <tr class="bg-colorBackgroundLight dark:bg-colorBackgroundDark">
                            <td class="px-4 py-4 text-center font-medium text-gray-900 dark:text-white">4</td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center dark:text-white">
                                81+ dB
                            </th>
                            <td class="px-6 py-4 font-semibold text-colorHigh text-center">
                                <span class="inline-block w-3 h-3 rounded-full me-2 bg-colorHigh"></span>
                                Danger
                            </td>
                        </tr>

                        <tr class="bg-colorBackgroundLight dark:bg-colorBackgroundDark">
                            <td class="px-4 py-4 text-center font-medium text-gray-900 dark:text-white">5</td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center dark:text-white">
                                "No Sound"
                            </th>
                            <td class="px-6 py-4 font-semibold text-gray-500 text-center">
                                <span class="inline-block w-3 h-3 rounded-full me-2 bg-gray-500"></span>
                                None
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
