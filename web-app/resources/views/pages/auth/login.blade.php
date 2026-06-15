<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        
        <!-- Header -->
        <div class="flex flex-col text-center gap-1">
            <h2 class="text-headline font-bold text-ink">Selamat Datang Kembali</h2>
            <p class="text-body-sm text-ink-muted">Masukkan email dan kata sandi Anda untuk mengakses dashboard.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center bg-primary-light border border-hairline text-primary-teal p-3 rounded-md text-body-sm" :status="session('status')" />



        <!-- Form -->
        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <div class="flex flex-col gap-1.5 w-full">
                <label for="email" class="text-body-default font-semibold text-ink">Alamat Email</label>
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="email" 
                    placeholder="nama@email.com" 
                    class="w-full h-12 bg-surface-1 border @error('email') border-2 border-risk-high @else border-hairline @enderror rounded-md px-4 py-3 placeholder:text-ink-tertiary text-body-default focus:outline-none focus:border-2 focus:border-primary-teal transition-all duration-150 shadow-inner"
                />
                @error('email')
                    <span class="text-caption text-risk-high flex items-center gap-1 mt-1">
                        <span class="font-bold">⚠</span>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>

            <!-- Password -->
            <div class="flex flex-col gap-1.5 w-full relative">
                <div class="flex justify-between items-center w-full">
                    <label for="password" class="text-body-default font-semibold text-ink">Kata Sandi</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-body-sm font-semibold text-primary-teal hover:underline transition-colors" wire:navigate>
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>
                <div class="relative w-full" x-data="{ show: false }">
                    <input 
                        id="password" 
                        name="password" 
                        :type="show ? 'text' : 'password'" 
                        required 
                        autocomplete="current-password" 
                        placeholder="••••••••" 
                        class="w-full h-12 bg-surface-1 border @error('password') border-2 border-risk-high @else border-hairline @enderror rounded-md px-4 py-3 placeholder:text-ink-tertiary text-body-default focus:outline-none focus:border-2 focus:border-primary-teal pr-12 transition-all duration-150 shadow-inner"
                    />
                    <!-- Show/Hide Password Toggle -->
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-ink-subtle hover:text-primary-teal focus:outline-none transition-colors">
                        <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.076m3.102-3.007A9.96 9.96 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21m-7-9a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="text-caption text-risk-high flex items-center gap-1 mt-1">
                        <span class="font-bold">⚠</span>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2.5 py-1">
                <input 
                    id="remember" 
                    name="remember" 
                    type="checkbox" 
                    {{ old('remember') ? 'checked' : '' }}
                    class="h-5 w-5 rounded-sm border border-hairline text-primary-teal focus:ring-primary-teal focus:ring-offset-2 accent-primary-teal cursor-pointer"
                />
                <label for="remember" class="text-body-default text-ink-muted cursor-pointer select-none">
                    Ingat saya di perangkat ini
                </label>
            </div>

            <!-- Submit Button -->
            <div class="mt-2">
                <button type="submit" class="w-full h-12 bg-primary-teal hover:bg-[#096B50] active:scale-[0.98] text-white font-semibold rounded-md shadow-sm transition-all text-button-label flex items-center justify-center gap-2 cursor-pointer duration-150">
                    <span>Masuk ke Akun</span>
                    <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-body-sm text-center text-ink-muted mt-2 border-t border-hairline-soft pt-4">
                Belum memiliki akun? 
                <a href="{{ route('register') }}" class="font-bold text-primary-teal hover:underline transition-colors" wire:navigate>
                    Daftar Akun Baru
                </a>
            </div>
        @endif
    </div>
</x-layouts::auth>
