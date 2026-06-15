<header class="sticky top-0 z-50 w-full h-16 bg-canvas/95 backdrop-blur-sm border-b border-hairline flex items-center justify-between px-6 md:px-12 transition-all duration-200">
    <!-- Left: Logo & Identity -->
    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div class="p-1.5 bg-primary-light rounded-md text-primary-teal group-hover:scale-105 transition-transform duration-200">
            <!-- Logo Simbol: Shield & Growth Chart representing growth and security -->
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <path d="M12 9v7"/>
                <path d="M8 12v4"/>
                <path d="M16 11v5"/>
            </svg>
        </div>
        <div class="flex flex-col">
            <span class="text-card-title text-ink font-bold leading-tight group-hover:text-primary-teal transition-colors">
                SiPakar Stunting
            </span>
            <span class="text-[10px] tracking-wider font-semibold text-primary-teal/80 uppercase leading-none">
                Hybrid AI System
            </span>
        </div>
    </a>

    <!-- Center: Main Navigation (Middle Links) -->
    <nav class="hidden md:flex items-center gap-8">
        <a href="{{ route('home') }}" 
           class="text-body-default font-medium transition-colors {{ request()->routeIs('home') ? 'text-primary-teal font-semibold' : 'text-ink-muted hover:text-primary-teal' }}">
            Home
        </a>
        <a href="{{ route('edukasi') ?? '#edukasi' }}" 
           class="text-body-default font-medium transition-colors {{ request()->routeIs('edukasi') ? 'text-primary-teal font-semibold' : 'text-ink-muted hover:text-primary-teal' }}">
            Edukasi Gizi
        </a>
    </nav>

    <!-- Right: CTA Button -->
    <div class="flex items-center gap-4">
        @if (Route::has('login'))
            @auth
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-teal hover:bg-[#096B50] text-white text-button-label rounded-md shadow-sm transition-all hover:scale-[1.02] active:scale-95 duration-150">
                    <span>Buka Dashboard</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-teal hover:bg-[#096B50] text-white text-button-label rounded-md shadow-sm transition-all hover:scale-[1.02] active:scale-95 duration-150">
                    <span>Masuk ke Dashboard</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                </a>
            @endauth
        @endif

        <!-- Mobile Menu Toggle -->
        <button id="mobile-menu-toggle" class="md:hidden p-2 text-ink-muted hover:text-primary-teal focus:outline-none focus:ring-2 focus:ring-primary-teal rounded-md transition-colors" aria-label="Toggle Menu">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Drawer (Backdrop & Sidebar) -->
    <div id="mobile-menu" class="hidden fixed inset-0 z-[100] md:hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs transition-opacity duration-300"></div>
        <!-- Menu Content -->
        <div class="fixed right-0 top-0 bottom-0 w-3/4 max-w-sm bg-canvas border-l border-hairline p-6 flex flex-col gap-6 shadow-2xl transition-transform duration-300 transform translate-x-0">
            <div class="flex items-center justify-between">
                <span class="text-headline text-ink font-bold">Menu</span>
                <button id="mobile-menu-close" class="p-2 text-ink-muted hover:text-primary-teal focus:outline-none rounded-md" aria-label="Close Menu">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <nav class="flex flex-col gap-4 mt-4">
                <a href="{{ route('home') }}" 
                   class="px-4 py-3 rounded-md text-subhead font-medium transition-colors {{ request()->routeIs('home') ? 'bg-primary-light text-primary-teal font-semibold' : 'text-ink-muted hover:bg-surface-2 hover:text-primary-teal' }}">
                    Home
                </a>
                <a href="{{ route('edukasi') ?? '#edukasi' }}" 
                   class="px-4 py-3 rounded-md text-subhead font-medium transition-colors {{ request()->routeIs('edukasi') ? 'bg-primary-light text-primary-teal font-semibold' : 'text-ink-muted hover:bg-surface-2 hover:text-primary-teal' }}">
                    Edukasi Gizi
                </a>
            </nav>

            <div class="mt-auto border-t border-hairline pt-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           class="w-full justify-center inline-flex items-center gap-2 px-5 py-3 bg-primary-teal hover:bg-[#096B50] text-white text-button-label rounded-md shadow-sm transition-all">
                            <span>Buka Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="w-full justify-center inline-flex items-center gap-2 px-5 py-3 bg-primary-teal hover:bg-[#096B50] text-white text-button-label rounded-md shadow-sm transition-all mb-3">
                            <span>Masuk ke Dashboard</span>
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="w-full justify-center inline-flex items-center gap-2 px-5 py-3 bg-surface-1 border border-primary-teal text-primary-teal hover:bg-primary-light text-button-label rounded-md shadow-sm transition-all">
                                <span>Daftar Akun</span>
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('mobile-menu-toggle');
            const closeBtn = document.getElementById('mobile-menu-close');
            const menu = document.getElementById('mobile-menu');
            const backdrop = menu ? menu.querySelector('div:first-child') : null;
            const content = menu ? menu.querySelector('div:last-child') : null;

            if (toggleBtn && menu) {
                toggleBtn.addEventListener('click', function() {
                    menu.classList.remove('hidden');
                });
            }

            if (closeBtn && menu) {
                closeBtn.addEventListener('click', function() {
                    menu.classList.add('hidden');
                });
            }

            if (backdrop && menu) {
                backdrop.addEventListener('click', function() {
                    menu.classList.add('hidden');
                });
            }
        });
    </script>
</header>
