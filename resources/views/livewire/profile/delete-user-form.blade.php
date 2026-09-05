<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6 relative z-10">
    <header>
        <h2 class="text-xl font-bold font-display text-brand-coral-500 flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            Zona de Peligro
        </h2>
        <p class="mt-2 text-sm text-brand-soft-textSec max-w-xl">
            Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán borrados permanentemente de <strong>Mi Chanchito Nica</strong>. Antes de eliminarla, asegúrate de descargar cualquier información que desees conservar.
        </p>
    </header>

    <div>
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center px-6 py-3 bg-white border border-brand-coral-300 rounded-xl font-bold text-sm text-brand-coral-500 hover:bg-brand-coral-50 focus:outline-none focus:ring-4 focus:ring-brand-coral-500/20 transition-all shadow-sm"
        >
            Eliminar cuenta
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-8">
            <h2 class="text-2xl font-display font-bold text-brand-navy-900 mb-2">
                ¿Estás seguro de que deseas eliminar tu cuenta?
            </h2>

            <p class="text-sm text-brand-soft-textSec mb-6">
                Esta acción es irreversible. Todos tus datos, compras y progreso serán eliminados. Por favor ingresa tu contraseña para confirmar la eliminación.
            </p>

            <div>
                <label for="password" class="sr-only">Contraseña</label>
                <input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="w-full px-4 py-3 rounded-xl border border-brand-soft-border bg-gray-50 focus:bg-white focus:ring-2 focus:ring-brand-coral-500/20 focus:border-brand-coral-500 transition-colors text-brand-navy-900"
                    placeholder="Tu contraseña actual"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-brand-coral-500 font-medium" />
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="inline-flex items-center px-6 py-3 bg-white border border-brand-soft-border rounded-xl font-bold text-sm text-brand-navy-900 hover:bg-gray-50 transition-all shadow-sm">
                    Cancelar
                </button>

                <button type="submit" class="inline-flex items-center px-6 py-3 bg-brand-coral-500 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-brand-coral-600 transition-all shadow-sm focus:ring-4 focus:ring-brand-coral-500/30">
                    Sí, Eliminar Cuenta
                </button>
            </div>
        </form>
    </x-modal>
</section>
