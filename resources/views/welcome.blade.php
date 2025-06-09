<x-guest-layout>
    <div class="text-center text-white font-inter">
        <p class="text-gray-600 dark: text-gray-400 max-w-md mx-auto mb-8">
            Welcome to <span class="text-colorNormal font-semibold">SsttMate</span> — your microphone sensitivity manager. Easily monitor and adjust audio input levels across all your devices.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('login') }}" class="bg-colorNormal text-black font-bold py-2 px-6 rounded transition duration-200">
                LOG IN
            </a>
            <a href="{{ route('register') }}" class="bg-transparent border border-colorNormal hover:bg-colorNormal hover:text-black text-colorNormal font-bold py-2 px-6 rounded transition duration-200">
                REGISTER
            </a>
        </div>
    </div>
</x-guest-layout>
