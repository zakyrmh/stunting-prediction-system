<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-canvas font-sans text-ink antialiased flex flex-col justify-center items-center p-0 md:p-6">
        
        <!-- Centered Wrapper -->
        <div class="w-full max-w-[480px] flex flex-col items-center justify-center min-h-screen md:min-h-0 gap-6">
            
            <!-- Branding Header -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 hover:scale-102 transition-transform duration-200" wire:navigate>
                <div class="p-1.5 bg-primary-light rounded-md text-primary-teal shadow-xs">
                    <!-- Shield & Growth SVG Logo -->
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="M12 9v7"/>
                        <path d="M8 12v4"/>
                        <path d="M16 11v5"/>
                    </svg>
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-card-title text-ink font-bold leading-tight">SiPakar Stunting</span>
                    <span class="text-[10px] tracking-wider font-semibold text-primary-teal uppercase leading-none">Hybrid AI System</span>
                </div>
            </a>

            <!-- Auth Card -->
            <div class="w-full bg-surface-1 border border-hairline rounded-t-xxl rounded-b-none border-b-0 md:rounded-xxl md:border-b md:border-x p-6 md:p-10 shadow-sm flex flex-col gap-6">
                {{ $slot }}
            </div>

            <!-- Footer Small Link -->
            <div class="text-caption text-ink-subtle text-center px-4 hidden md:block">
                &copy; 2026 SiPakar Team - TRPL Politeknik Negeri Padang.
            </div>

        </div>

        @fluxScripts
    </body>
</html>
