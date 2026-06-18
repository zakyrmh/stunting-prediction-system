<!-- Welcome banner common for all roles -->
<div class="bg-surface-1 border border-hairline rounded-xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm">
    <div>
        <h1 class="text-headline font-bold text-ink leading-tight">Selamat Datang, {{ auth()->user()->name }}!</h1>
        <p class="text-body-sm text-ink-muted mt-1">
            Anda masuk sebagai 
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-primary-light text-primary-teal rounded text-caption font-bold">
                @if(auth()->user()->isBidan())
                    Bidan / Tenaga Kesehatan (Super User)
                @elseif(auth()->user()->isKader())
                    Kader Posyandu (Data Entry / Operator)
                @elseif(auth()->user()->isOrangTua())
                    Orang Tua / Ibu Balita (Viewer)
                @endif
            </span>
        </p>
    </div>
    <div class="text-caption text-ink-subtle font-medium bg-canvas border border-hairline px-3 py-1.5 rounded-md">
        Hari ini: {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}
    </div>
</div>
