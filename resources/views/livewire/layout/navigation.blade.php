<?php
use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav class="bg-white border-b border-gray-200">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left Side: Search (Placeholder) -->
            <div class="flex flex-1 items-center">
                <div class="max-w-md w-full relative hidden sm:block">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-3 py-2 border-0 bg-[#F8F9FA] rounded-lg text-sm placeholder-gray-500 focus:ring-1 focus:ring-brand-green-500 focus:bg-white transition-colors" placeholder="Buscar módulos, atajos (⌘+K)...">
                </div>
            </div>

            <!-- Right Side: Profile & Actions -->
            <div class="flex items-center gap-4">
                
                <!-- Notification Bell -->
                <button class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-brand-coral-500 ring-2 ring-white"></span>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-brand-green-50 text-brand-green-700 font-bold flex items-center justify-center text-sm border border-brand-green-100">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                        <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Mi Perfil</a>
                        <button wire:click="logout" class="block w-full text-left px-4 py-2 text-sm text-brand-coral-500 hover:bg-red-50">Cerrar Sesión</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
