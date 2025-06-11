<x-guest-layout>
    <div class="text-center text-white font-inter">
        <p class="text-gray-300 max-w-md mx-auto mb-8">
            Welcome to <span class="text-yellow-400 font-semibold">SsttMate</span> — your microphone sensitivity manager. Easily monitor and adjust audio input levels across all your devices.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('login') }}" class="bg-yellow-400 hover:bg-yellow-300 text-black font-bold py-2 px-6 rounded transition duration-200">
                LOG IN
            </a>
            <a href="{{ route('register') }}" class="bg-transparent border border-yellow-400 hover:bg-yellow-400 hover:text-black text-yellow-400 font-bold py-2 px-6 rounded transition duration-200">
                REGISTER
            </a>
        </div>
    </div>
</x-guest-layout>