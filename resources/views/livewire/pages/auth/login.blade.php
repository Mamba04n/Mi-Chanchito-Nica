<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public bool $showPassword = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Title --}}
    <h2 class="font-display text-3xl sm:text-4xl text-brand-navy-900 tracking-tight mb-2">
        Iniciar sesión
    </h2>
    <p class="text-brand-soft-textSec text-sm sm:text-base mb-8">
        Ingresa tus credenciales para acceder al portal.
    </p>

    <form wire:submit="login">
        {{-- Email --}}
        <div class="mb-5">
            <label for="email" class="block text-sm font-semibold text-brand-navy-900 mb-1.5">Correo</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-brand-soft-textSec">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                </span>
                <input wire:model="form.email"
                       id="email"
                       type="email"
                       name="email"
                       required
                       autofocus
                       autocomplete="username"
                       placeholder="tu@correo.com"
                       class="w-full pl-11 pr-4 py-3 rounded-xl border border-brand-soft-border bg-white text-brand-navy-900 placeholder-brand-soft-textSec/60
                              focus:border-brand-green-700 focus:ring-2 focus:ring-brand-green-700/20 focus:outline-none
                              transition duration-150" />
            </div>
            <x-input-error :messages="$errors->get('form.email')" class="mt-1.5" />
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold text-brand-navy-900 mb-1.5">Contraseña</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-brand-soft-textSec">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25z" />
                    </svg>
                </span>
                <input wire:model="form.password"
                       id="password"
                       type="{{ $showPassword ? 'text' : 'password' }}"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••••"
                       class="w-full pl-11 pr-12 py-3 rounded-xl border border-brand-soft-border bg-white text-brand-navy-900 placeholder-brand-soft-textSec/60
                              focus:border-brand-green-700 focus:ring-2 focus:ring-brand-green-700/20 focus:outline-none
                              transition duration-150" />
                <button type="button"
                        wire:click="$toggle('showPassword')"
                        aria-label="{{ $showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña' }}"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-brand-soft-textSec hover:text-brand-navy-900 transition duration-150">
                    @if($showPassword)
                        {{-- Eye open --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    @else
                        {{-- Eye closed --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c1.658 0 3.222-.39 4.61-1.088M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    @endif
                </button>
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-1.5" />
        </div>

        {{-- Forgot password --}}
        <div class="flex items-center justify-end mb-6">
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-brand-green-700 hover:underline hover:text-brand-green-500 transition duration-150 focus:outline-none focus:ring-2 focus:ring-brand-green-700/30 rounded"
                   href="{{ route('password.request') }}" wire:navigate>
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        {{-- Submit --}}
        <button type="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-75 cursor-wait"
                class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl
                       bg-brand-navy-900 text-white font-semibold text-base
                       hover:bg-[#1e2d4a] active:bg-[#0f1824]
                       focus:outline-none focus:ring-2 focus:ring-brand-navy-900/40 focus:ring-offset-2
                       transition duration-150 disabled:opacity-60">
            <span wire:loading.remove>Entrar al panel</span>
            <span wire:loading.remove>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </span>
            <span wire:loading class="flex items-center gap-2">
                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Ingresando...
            </span>
        </button>
    </form>

    {{-- Divider --}}
    <div class="flex items-center my-6">
        <div class="flex-1 border-t border-brand-soft-border"></div>
        <span class="px-3 text-xs text-brand-soft-textSec">o</span>
        <div class="flex-1 border-t border-brand-soft-border"></div>
    </div>

    {{-- Register link --}}
    @if (Route::has('register'))
        <p class="text-center text-sm text-brand-soft-textSec">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" wire:navigate
               class="font-semibold text-brand-green-700 hover:underline hover:text-brand-green-500 transition duration-150">
                Regístrame como cliente
            </a>
        </p>
    @endif
</div>
