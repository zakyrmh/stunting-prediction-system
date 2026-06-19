<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 md:p-6 bg-canvas text-ink font-sans">
        
        <x-dashboard.welcome />

        @if(auth()->user()->isBidan())
            <x-dashboard.bidan :data="$bidanData" />
        @elseif(auth()->user()->isKader())
            @if(empty($kaderData))
                <div class="bg-risk-medium-surface border border-l-4 border-risk-medium-border border-l-risk-medium rounded-xl p-6 flex items-start gap-4">
                    <span class="text-2xl shrink-0 leading-none mt-0.5">⚠️</span>
                    <div>
                        <h3 class="text-headline font-bold text-ink">Akun Belum Terhubung ke Posyandu</h3>
                        <p class="text-body-default text-ink-muted mt-2 leading-relaxed">
                            Akun Kader Anda belum dikaitkan dengan Posyandu manapun.
                            Silakan hubungi <strong class="font-semibold text-ink">Bidan</strong> atau
                            administrator sistem untuk menetapkan Posyandu pada akun Anda
                            agar dapat mengakses data dan statistik dashboard.
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
