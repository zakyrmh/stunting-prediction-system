{{-- Desktop User Profile Menu (bottom of sidebar) --}}
<flux:dropdown position="top" align="start">

    {{-- Trigger: profile card di bawah sidebar --}}
    <button
        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-[10px] hover:bg-surface-2 transition-colors group focus:outline-none focus:ring-2 focus:ring-primary-teal focus:ring-offset-1"
        data-test="sidebar-menu-button"
    >
        {{-- Avatar circle --}}
        <div class="flex-shrink-0 flex items-center justify-center w-9 h-9 rounded-full bg-primary-light text-primary-teal font-semibold text-sm">
            {{ auth()->user()->initials() }}
        </div>

        {{-- Name + role --}}
        <div class="flex-1 text-left min-w-0">
            <p class="text-[14px] font-semibold text-ink truncate leading-tight">{{ auth()->user()->name }}</p>
            <p class="text-[12px] text-ink-muted truncate leading-tight mt-0.5">
                @if(auth()->user()->isBidan())
                    Bidan / Tenaga Kesehatan
                @elseif(auth()->user()->isKader())
                    Kader Posyandu
                @else
                    Orang Tua Balita
                @endif
            </p>
        </div>

        {{-- Chevron icon --}}
        <svg class="w-4 h-4 text-ink-muted group-hover:text-ink transition-colors flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8 9 4-4 4 4m0 6-4 4-4-4"/>
        </svg>
    </button>

    {{-- Dropdown Menu --}}
    <flux:menu class="min-w-[220px] shadow-lg border border-hairline">

        {{-- User info header --}}
        <div class="px-3 py-3 border-b border-hairline-soft">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-primary-light text-primary-teal font-semibold text-sm">
                    {{ auth()->user()->initials() }}
                </div>
                <div class="min-w-0">
                    <p class="text-[14px] font-semibold text-ink truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[12px] text-ink-muted truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            {{-- Role badge --}}
            <div class="mt-2.5">
                <span class="inline-flex items-center px-2 py-0.5 bg-primary-light text-primary-teal rounded text-[11px] font-semibold">
                    @if(auth()->user()->isBidan())
                        🏥 Bidan / Super User
                    @elseif(auth()->user()->isKader())
                        📋 Kader Posyandu
                    @else
                        👨‍👩‍👧 Orang Tua Balita
                    @endif
                </span>
            </div>
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
                class="w-full cursor-pointer"
                data-test="logout-button"
            >
                {{ __('Keluar') }}
            </flux:menu.item>
        </form>

    </flux:menu>

</flux:dropdown>
