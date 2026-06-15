<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-canvas text-ink font-sans antialiased flex flex-col selection:bg-primary-light selection:text-primary-teal">
        
        <!-- Header / Navbar -->
        <x-public-header />

        <!-- Main Page Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <x-public-footer />

        @fluxScripts
    </body>
</html>
