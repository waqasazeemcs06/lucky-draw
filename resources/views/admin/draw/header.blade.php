<header class="w-full p-4 border-b border-white/10 bg-black/20 backdrop-blur-md sticky top-0 z-50">
    <div class="max-w-[1920px] mx-auto flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow-lg shadow-yellow-500/30 animate-pulse">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h1 class="font-display text-xl font-black text-yellow-400 tracking-wider" style="text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);">{{ $draw->title }}</h1>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-xs text-gray-300">Live</span>
            </div>
        </div>
    </div>
</header>
