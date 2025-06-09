<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Kontainer utama halaman dengan background gelap --}}
                       <div class="p-4 sm:p-8 bg-colorBackgroundLight dark:bg-colorBackgroundDark rounded-lg" style="box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);">

                {{-- Judul Halaman dengan Gradasi --}}
                <div class="text-center">
                    <h2 class="text-2xl inline-block bg-gradient-to-r from-colorHigh to-colorNormal bg-clip-text text-transparent mb-8">
                        Log History
                    </h2>
                </div>

                {{-- Bagian Inti: Tampilan "Data Tidak Ditemukan" --}}
                {{-- Div ini akan menengahkan lingkaran ke tengah halaman --}}
                <div class="flex justify-center items-center py-16">

                    {{-- Lingkaran dengan background lebih gelap dan transparan --}}
                    <div class="w-80 h-80 flex flex-col justify-center items-center text-center p-4">

                        {{-- Emoji --}}
                        <div class="text-7xl mb-4">
                            😵
                        </div>

                        {{-- Judul Empty State --}}
                        <h3 class="text-2xl font-bold text-gray-200 mb-2">
                            Data Not Found
                        </h3>

                        {{-- Deskripsi/Subjudul --}}
                        <p class="text-gray-400 max-w-xs font-normal">
                            Sorry, no history data exists yet.
                            Please check back later.
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>