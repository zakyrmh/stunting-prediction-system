<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-canvas text-ink font-sans">
    <flux:sidebar sticky collapsible="mobile"
        class="border-r border-hairline bg-surface-1">
        <flux:sidebar.header class="border-b border-hairline-soft">
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </flux:sidebar.item>

            <!-- 1. Bidan / Tenaga Kesehatan Menu -->
            @if (auth()->user()->isBidan())
                <flux:sidebar.group :heading="__('Data & Pengukuran')" class="grid">
                    <flux:sidebar.item icon="users" :href="route('balita.index')" :current="request()->routeIs('balita.index')" wire:navigate>
                        {{ __('Data Induk Balita') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="document-text" :href="route('prediksi.index')" :current="request()->routeIs('prediksi.index')" wire:navigate>
                        {{ __('Riwayat Pengukuran') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Manajemen Sistem')" class="grid">
                    <flux:sidebar.item icon="building-office" :href="route('posyandu.index')" :current="request()->routeIs('posyandu.*')" wire:navigate>
                        {{ __('Data Posyandu') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="user-group" :href="route('users.index')" :current="request()->routeIs('users.*')" wire:navigate>
                        {{ __('Manajemen Kader') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

            <!-- 2. Kader Posyandu Menu -->
            @elseif (auth()->user()->isKader())
                <flux:sidebar.group :heading="__('Registrasi & Input')" class="grid">
                    <flux:sidebar.item icon="user-plus" :href="route('balita.form')" :current="request()->routeIs('balita.form')" wire:navigate>
                        {{ __('Pendaftaran Balita') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="pencil-square" :href="route('prediksi.form')" :current="request()->routeIs('prediksi.form')" wire:navigate>
                        {{ __('Pencatatan Bulanan') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Laporan & Data')" class="grid">
                    <flux:sidebar.item icon="users" :href="route('balita.index')" :current="request()->routeIs('balita.index')" wire:navigate>
                        {{ __('Daftar & Riwayat Anak') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

            <!-- 3. Orang Tua Balita Menu -->
            @elseif (auth()->user()->isOrangTua())
                <flux:sidebar.group :heading="__('Informasi & Panduan')" class="grid">
                    <flux:sidebar.item icon="book-open" :href="route('edukasi')" :current="request()->routeIs('edukasi')" wire:navigate>
                        {{ __('Edukasi Kesehatan') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            @endif
        </flux:sidebar.nav>

        <flux:spacer />

        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden border-b border-hairline bg-surface-1">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>
