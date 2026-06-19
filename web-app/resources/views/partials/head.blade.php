<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="SiPakar Stunting — Sistem Pakar Hybrid untuk Deteksi Dini dan Intervensi Risiko Stunting Balita">

<title>
    {{ filled($title ?? null) ? $title.' — '.config('app.name', 'SiPakar Stunting') : config('app.name', 'SiPakar Stunting') }}
</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|jetbrains-mono:400,500,600,700" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- Paksa light mode: hindari Flux membaca preferensi dark mode sistem --}}
<script>
    // SiPakar Stunting hanya mendukung light mode pada versi ini.
    document.documentElement.classList.remove('dark');
    document.documentElement.setAttribute('data-flux-appearance', 'light');
</script>
