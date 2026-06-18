<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 md:p-6 bg-canvas text-ink font-sans">
        
        <x-dashboard.welcome />

        @if(auth()->user()->isBidan())
            <x-dashboard.bidan :data="$bidanData" />
        @elseif(auth()->user()->isKader())
            <x-dashboard.kader />
        @elseif(auth()->user()->isOrangTua())
            <x-dashboard.orang-tua />
        @endif

    </div>
</x-layouts::app>
