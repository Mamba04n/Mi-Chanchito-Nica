<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-xl font-bold font-display text-brand-navy-900 flex items-center gap-2">
            <svg class="w-6 h-6 text-brand-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            Información Personal
        </h2>
        <p class="mt-2 text-sm text-brand-soft-textSec">
            Actualiza tu nombre y correo electrónico.
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-8 space-y-6">
        <!-- Nombre -->
        <div>
            <label for="name" class="block text-sm font-bold text-brand-navy-900 mb-2">Nombre Completo</label>
            <input wire:model="name" id="name" name="name" type="text" class="w-full px-4 py-3 rounded-xl border border-brand-soft-border bg-gray-50 focus:bg-white focus:ring-2 focus:ring-brand-green-500/20 focus:border-brand-green-500 transition-colors text-brand-navy-900" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-brand-coral-500" :messages="$errors->get('name')" />
        </div>

        <!-- Correo Electrónico -->
        <div>
            <label for="email" class="block text-sm font-bold text-brand-navy-900 mb-2">Correo Electrónico</label>
            <input wire:model="email" id="email" name="email" type="email" class="w-full px-4 py-3 rounded-xl border border-brand-soft-border bg-gray-50 focus:bg-white focus:ring-2 focus:ring-brand-green-500/20 focus:border-brand-green-500 transition-colors text-brand-navy-900" required autocomplete="username" />
            <x-input-error class="mt-2 text-brand-coral-500" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-4 p-4 rounded-xl bg-yellow-50 border border-yellow-200">
                    <p class="text-sm font-medium text-yellow-800">
                        Tu dirección de correo no está verificada.
                        <button wire:click.prevent="sendVerification" class="underline text-sm text-yellow-900 hover:text-yellow-700 font-bold focus:outline-none">
                            Haz clic aquí para reenviar el correo de verificación.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-brand-green-600">
                            Se ha enviado un nuevo enlace de verificación a tu correo.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="pt-4 border-t border-brand-soft-border flex items-center justify-end gap-4">
            <x-action-message class="me-3 text-sm font-bold text-brand-green-600" on="profile-updated">
                ¡Guardado con éxito!
            </x-action-message>

            <button type="submit" class="inline-flex items-center px-6 py-3 bg-brand-navy-900 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-brand-navy-800 focus:outline-none focus:ring-4 focus:ring-brand-navy-500/30 transition-all shadow-sm">
                Guardar Cambios
            </button>
        </div>
    </form>
</section>
