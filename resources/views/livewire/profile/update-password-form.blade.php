<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header>
        <h2 class="text-xl font-bold font-display text-brand-navy-900 flex items-center gap-2">
            <svg class="w-6 h-6 text-brand-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            Seguridad
        </h2>
        <p class="mt-2 text-sm text-brand-soft-textSec">
            Actualiza tu contraseña para mantener tu cuenta protegida.
        </p>
    </header>

    <form wire:submit="updatePassword" class="mt-8 space-y-6">
        <div>
            <label for="update_password_current_password" class="block text-sm font-bold text-brand-navy-900 mb-2">Contraseña Actual</label>
            <input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" class="w-full px-4 py-3 rounded-xl border border-brand-soft-border bg-gray-50 focus:bg-white focus:ring-2 focus:ring-brand-green-500/20 focus:border-brand-green-500 transition-colors text-brand-navy-900" autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2 text-brand-coral-500" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-bold text-brand-navy-900 mb-2">Nueva Contraseña</label>
            <input wire:model="password" id="update_password_password" name="password" type="password" class="w-full px-4 py-3 rounded-xl border border-brand-soft-border bg-gray-50 focus:bg-white focus:ring-2 focus:ring-brand-green-500/20 focus:border-brand-green-500 transition-colors text-brand-navy-900" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-brand-coral-500" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-bold text-brand-navy-900 mb-2">Confirmar Nueva Contraseña</label>
            <input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full px-4 py-3 rounded-xl border border-brand-soft-border bg-gray-50 focus:bg-white focus:ring-2 focus:ring-brand-green-500/20 focus:border-brand-green-500 transition-colors text-brand-navy-900" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-brand-coral-500" />
            <p class="mt-3 text-xs text-brand-soft-textSec flex items-start gap-1.5">
                <svg class="w-4 h-4 shrink-0 mt-0.5 text-brand-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Usa una contraseña segura y fácil de recordar para ti.
            </p>
        </div>

        <div class="pt-4 border-t border-brand-soft-border flex items-center justify-end gap-4">
            <x-action-message class="me-3 text-sm font-bold text-brand-green-600" on="password-updated">
                ¡Contraseña actualizada!
            </x-action-message>

            <button type="submit" class="inline-flex items-center px-6 py-3 bg-brand-navy-900 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-brand-navy-800 focus:outline-none focus:ring-4 focus:ring-brand-navy-500/30 transition-all shadow-sm">
                Guardar Cambios
            </button>
        </div>
    </form>
</section>
