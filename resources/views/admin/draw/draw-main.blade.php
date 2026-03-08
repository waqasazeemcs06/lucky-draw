<div class="min-h-screen flex flex-col relative z-10">
    @include('admin.draw.header')
    <main class="flex-1 w-full max-w-[1920px] mx-auto p-4">
        @if($currentPrize)
            @include('admin.draw.prize-info')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4" style="--current-color: {{ $currentPrize ? $prizeColors[$currentPrize->id] : '#00f3ff' }}">
            @include('admin.draw.sidebar-left')
            @include('admin.draw.winner-slots')
            @include('admin.draw.sidebar-right')
        </div>
    </main>
</div>
