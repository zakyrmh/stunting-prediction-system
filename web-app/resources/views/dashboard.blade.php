<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 md:p-6 bg-canvas text-ink font-sans">
        
        <x-dashboard.welcome />

        @if(auth()->user()->isBidan())
            <x-dashboard.bidan :data="$bidanData" />
        @elseif(auth()->user()->isKader())
            @if(empty($kaderData))
                <div class="bg-amber-50 border border-amber-300 rounded-xl p-6 flex items-start gap-4 shadow-sm">
                    <span class="text-2xl shrink-0">⚠️</span>
                    <div>
                        <h3 class="font-bold text-amber-800 text-lg">Akun Belum Terhubung ke Posyandu</h3>
                        <p class="text-amber-700 mt-1 text-sm">
                            Akun Kader Anda belum dikaitkan dengan Posyandu manapun. 
                            Silakan hubungi <strong>Bidan</strong> atau administrator sistem untuk 
                            menetapkan Posyandu pada akun Anda agar dapat mengakses dashboard.
                        </p>
                    </div>
                </div>
            @else
                <x-dashboard.kader :data="$kaderData" />
            @endif
        @elseif(auth()->user()->isOrangTua())
            <x-dashboard.orang-tua :data="$parentData" />
        @endif

    </div>
</x-layouts::app>
