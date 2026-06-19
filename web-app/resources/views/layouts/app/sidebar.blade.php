<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-canvas text-ink font-sans antialiased">

    {{-- ═══════════════════════════════════════════════
         SIDEBAR — Desktop (≥ 1024px), collapsible on mobile
         Background: surface-1 (white), border-right: hairline
    ═══════════════════════════════════════════════ --}}
    <flux:sidebar sticky collapsible="mobile"
        class="border-r border-hairline bg-surface-1 w-[240px]">

        {{-- Sidebar Header: Logo + Brand --}}
        <flux:sidebar.header class="border-b border-hairline-soft py-4 px-4">
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden text-ink-muted" />
        </flux:sidebar.header>

        {{-- Navigation Items --}}
        <flux:sidebar.nav class="py-3 px-2 flex-1">

            {{-- Dashboard (semua role) --}}
            <flux:sidebar.item
                icon="home"
                :href="route('dashboard')"
                :current="request()->routeIs('dashboard')"
                wire:navigate
                class="rounded-[10px] mb-0.5"
            >
                {{ __('Dashboard') }}
            </flux:sidebar.item>

            {{-- ── 1. Bidan / Tenaga Kesehatan ── --}}
            @if (auth()->user()->isBidan())
                <flux:sidebar.group :heading="__('Data & Pengukuran')" class="mt-4">
                    <flux:sidebar.item
                        icon="users"
                        :href="route('balita.index')"
                        :current="request()->routeIs('balita.index')"
                        wire:navigate
                        class="rounded-[10px] mb-0.5"
                    >
                        {{ __('Data Induk Balita') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="document-text"
                        :href="route('prediksi.index')"
                        :current="request()->routeIs('prediksi.index')"
                        wire:navigate
                        class="rounded-[10px] mb-0.5"
                    >
                        {{ __('Riwayat Pengukuran') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Manajemen Sistem')" class="mt-4">
                    <flux:sidebar.item
                        icon="building-office"
                        :href="route('posyandu.index')"
                        :current="request()->routeIs('posyandu.*')"
                        wire:navigate
                        class="rounded-[10px] mb-0.5"
                    >
                        {{ __('Data Posyandu') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="user-group"
                        :href="route('users.index')"
                        :current="request()->routeIs('users.*')"
                        wire:navigate
                        class="rounded-[10px] mb-0.5"
                    >
                        {{ __('Manajemen Kader') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

            {{-- ── 2. Kader Posyandu ── --}}
            @elseif (auth()->user()->isKader())
                <flux:sidebar.group :heading="__('Registrasi & Input')" class="mt-4">
                    <flux:sidebar.item
                        icon="user-plus"
                        :href="route('balita.form')"
                        :current="request()->routeIs('balita.form')"
                        wire:navigate
                        class="rounded-[10px] mb-0.5"
                    >
                        {{ __('Pendaftaran Balita') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="pencil-square"
                        :href="route('prediksi.form')"
                        :current="request()->routeIs('prediksi.form')"
                        wire:navigate
                        class="rounded-[10px] mb-0.5"
                    >
                        {{ __('Pencatatan Bulanan') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Laporan & Data')" class="mt-4">
                    <flux:sidebar.item
                        icon="users"
                        :href="route('balita.index')"
                        :current="request()->routeIs('balita.index')"
                        wire:navigate
                        class="rounded-[10px] mb-0.5"
                    >
                        {{ __('Daftar & Riwayat Anak') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

            {{-- ── 3. Orang Tua Balita ── --}}
            @elseif (auth()->user()->isOrangTua())
                <flux:sidebar.group :heading="__('Informasi & Panduan')" class="mt-4">
                    <flux:sidebar.item
                        icon="book-open"
                        :href="route('edukasi')"
                        :current="request()->routeIs('edukasi')"
                        wire:navigate
                        class="rounded-[10px] mb-0.5"
                    >
                        {{ __('Edukasi Kesehatan') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            @endif

        </flux:sidebar.nav>

        <flux:spacer />

        {{-- Desktop User Menu (bottom of sidebar) --}}
        <div class="border-t border-hairline-soft px-2 py-3 hidden lg:block">
            <x-desktop-user-menu />
        </div>

    </flux:sidebar>

    {{-- ═══════════════════════════════════════════════
         MOBILE HEADER — hanya tampil di < 1024px
    ═══════════════════════════════════════════════ --}}
    <flux:header class="lg:hidden sticky top-0 z-40 border-b border-hairline bg-surface-1 h-14 px-4">
        <flux:sidebar.toggle class="text-ink-muted hover:text-ink transition-colors" icon="bars-2" inset="left" />

        {{-- Brand di tengah pada mobile --}}
        <div class="flex-1 flex justify-center">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
                <div class="flex aspect-square size-7 items-center justify-center rounded-lg bg-primary-teal">
                    <x-app-logo-icon class="size-4 fill-current text-white" />
                </div>
                <span class="text-[15px] font-semibold text-ink tracking-tight">SiPakar Stunting</span>
            </a>
        </div>

        {{-- Mobile user avatar + dropdown --}}
        <flux:dropdown position="top" align="end">
            <button class="flex items-center justify-center w-9 h-9 rounded-full bg-primary-light text-primary-teal font-semibold text-sm hover:bg-surface-2 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-2">
                {{ auth()->user()->initials() }}
            </button>

            <flux:menu class="min-w-[200px]">
                {{-- User info --}}
                <div class="px-3 py-2.5 border-b border-hairline-soft">
                    <p class="text-[15px] font-semibold text-ink truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[13px] text-ink-muted truncate mt-0.5">{{ auth()->user()->email }}</p>
                    <span class="inline-flex items-center mt-2 px-2 py-0.5 bg-primary-light text-primary-teal rounded text-[11px] font-semibold">
                        @if(auth()->user()->isBidan()) Bidan
                        @elseif(auth()->user()->isKader()) Kader
                        @else Orang Tua
                        @endif
                    </span>
                </div>

                <flux:menu.separator />

                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                    {{ __('Pengaturan Akun') }}
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer text-risk-high hover:bg-risk-high-surface"
                        data-test="logout-button"
                    >
                        {{ __('Keluar') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{-- ═══════════════════════════════════════════════
         MAIN CONTENT SLOT
    ═══════════════════════════════════════════════ --}}
    {{ $slot }}

    @fluxScripts
</body>

</html>
