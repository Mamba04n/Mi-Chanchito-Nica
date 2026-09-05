<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 bg-[#F7FAF7] min-h-screen">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="font-gliker text-4xl text-brand-navy-900 leading-tight">Tu Progreso</h1>
        <p class="font-sans text-brand-soft-textSec text-lg mt-2">Sube de nivel gestionando tu empresa todos los días.</p>
    </div>

    <!-- Main Stats Banner -->
    <div class="bg-gradient-to-r from-brand-navy-900 via-brand-navy-800 to-brand-green-800 rounded-3xl p-8 shadow-md flex flex-col md:flex-row items-center justify-between gap-8 mb-8 overflow-hidden relative">
        <div class="absolute -right-20 -top-20 opacity-10">
            <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
        </div>
        
        <div class="flex items-center gap-6 relative z-10">
            <div class="relative">
                <svg class="w-24 h-24 text-brand-gold-400" viewBox="0 0 36 36">
                    <path class="text-brand-navy-700/50" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" stroke-dasharray="100, 100" />
                    <path class="text-brand-gold-400 drop-shadow-md" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" stroke-dasharray="75, 100" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center flex-col text-white">
                    <span class="text-xs uppercase tracking-widest font-bold opacity-80">Nivel</span>
                    <span class="text-2xl font-gliker">{{ $profile->current_level }}</span>
                </div>
            </div>
            <div class="text-white">
                <p class="font-gliker text-3xl mb-1">{{ $profile->total_xp }} <span class="text-brand-gold-400 text-xl">XP</span></p>
                <p class="text-white/80 text-sm font-medium">350 XP para el Nivel {{ $profile->current_level + 1 }}</p>
            </div>
        </div>
        
        <div class="flex gap-4 relative z-10">
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4 flex flex-col items-center justify-center min-w-[120px]">
                <svg class="w-8 h-8 text-brand-gold-400 mb-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
                <span class="font-gliker text-2xl text-white">{{ $profile->current_streak ?? 0 }}</span>
                <span class="text-xs text-white/70 font-bold uppercase tracking-wider">Días Racha</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Retos y Logros -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Retos Diarios -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-brand-soft-border">
                <h2 class="font-gliker text-2xl text-brand-navy-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-brand-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Retos del Día
                </h2>
                
                <div class="space-y-4">
                    @forelse($challenges as $userChallenge)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-brand-green-200 transition">
                            <div class="w-12 h-12 rounded-full bg-white border-2 border-brand-green-200 text-brand-green-600 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-brand-navy-900">{{ $userChallenge->challenge->title ?? 'Reto' }}</h3>
                                <p class="text-xs text-brand-soft-textSec mt-1">{{ $userChallenge->challenge->description ?? '' }}</p>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                                    <div class="bg-brand-gold-400 h-2 rounded-full" style="width: {{ ($userChallenge->progress / max($userChallenge->challenge->target_value ?? 1, 1)) * 100 }}%"></div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="block font-bold text-brand-gold-600">+{{ $userChallenge->challenge->xp_reward ?? 0 }} XP</span>
                                <span class="text-xs font-bold text-gray-500">{{ $userChallenge->progress }} / {{ $userChallenge->challenge->target_value ?? 1 }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-brand-soft-textSec font-medium">¡No tienes retos activos hoy! Vuelve mañana.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Logros (Medallas) -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-brand-soft-border">
                <h2 class="font-gliker text-2xl text-brand-navy-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-brand-coral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    Tus Logros
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @forelse($achievements as $userAchievement)
                        <div class="flex flex-col items-center p-4 bg-brand-gold-50/30 rounded-2xl border border-brand-gold-100 hover:scale-105 transition-transform text-center cursor-help" title="{{ $userAchievement->achievement->description ?? '' }}">
                            <div class="w-16 h-16 bg-gradient-to-br from-brand-gold-300 to-brand-gold-500 rounded-full border-4 border-white shadow-md mb-3 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $userAchievement->achievement->icon_svg ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>' !!}
                                </svg>
                            </div>
                            <span class="font-bold text-sm text-brand-navy-900 leading-tight">{{ $userAchievement->achievement->title ?? 'Logro' }}</span>
                            <span class="text-[10px] text-brand-soft-textSec mt-1">{{ $userAchievement->earned_at->format('d M Y') }}</span>
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 grayscale opacity-50">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            </div>
                            <p class="text-brand-soft-textSec font-medium">Sigue aprendiendo y gestionando tu empresa para desbloquear logros.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Leaderboard (Clasificación) -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-brand-soft-border self-start sticky top-8">
            <div class="text-center mb-6">
                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-xs font-bold uppercase tracking-widest rounded-full mb-3">Competición Semanal</span>
                <h2 class="font-gliker text-2xl text-brand-navy-900">Liga Emprendedor</h2>
                <p class="text-sm text-brand-soft-textSec mt-1">Acaba en 2 días</p>
            </div>
            
            <div class="space-y-1">
                @foreach($leaderboard as $index => $boardProfile)
                    <div class="flex items-center gap-3 p-3 rounded-xl {{ $boardProfile->user_id === auth()->id() ? 'bg-brand-green-50 border border-brand-green-100' : 'hover:bg-gray-50' }}">
                        <span class="font-bold w-5 text-center {{ $index === 0 ? 'text-brand-gold-500' : ($index === 1 ? 'text-gray-400' : ($index === 2 ? 'text-orange-400' : 'text-gray-300')) }}">
                            {{ $index + 1 }}
                        </span>
                        <div class="w-10 h-10 rounded-full bg-brand-navy-900 text-white flex items-center justify-center font-bold text-sm shrink-0">
                            {{ substr($boardProfile->user->name ?? '?', 0, 1) }}
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="font-bold text-sm text-brand-navy-900 truncate {{ $boardProfile->user_id === auth()->id() ? 'text-brand-green-700' : '' }}">
                                {{ $boardProfile->user_id === auth()->id() ? 'Tú' : explode(' ', $boardProfile->user->name ?? 'Usuario')[0] }}
                            </p>
                            <p class="text-[10px] text-brand-soft-textSec uppercase tracking-wider">Nivel {{ $boardProfile->current_level }}</p>
                        </div>
                        <span class="font-bold text-sm {{ $boardProfile->user_id === auth()->id() ? 'text-brand-green-700' : 'text-brand-navy-900' }}">
                            {{ $boardProfile->total_xp }} XP
                        </span>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                <p class="text-xs text-brand-soft-textSec">Gana XP creando facturas, registrando compras y completando lecciones.</p>
            </div>
        </div>
    </div>
</div>