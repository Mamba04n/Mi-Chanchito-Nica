<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 bg-[#F7FAF7] min-h-screen">
    <!-- Header Académico (Tipo Duolingo Dashboard) -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 bg-white rounded-3xl p-6 shadow-sm border border-brand-soft-border">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 bg-brand-green-100 rounded-full flex items-center justify-center border-4 border-white shadow-md">
                <svg class="w-10 h-10 text-brand-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
            </div>
            <div>
                <h1 class="font-gliker text-3xl text-brand-navy-900 leading-tight">¡Hola, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
                <p class="font-sans text-brand-soft-textSec mt-1">Nivel {{ $progress->current_level ?? 1 }} • {{ $progress->total_xp ?? 0 }} XP Acumulados</p>
            </div>
        </div>
        
        <div class="mt-6 md:mt-0 flex gap-4">
            <div class="bg-brand-gold-50 px-4 py-2 rounded-2xl flex items-center gap-2 border border-brand-gold-200">
                <svg class="w-5 h-5 text-brand-gold-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
                <span class="font-bold text-brand-gold-600">{{ $progress->current_streak ?? 0 }} días racha</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Continuar Aprendiendo -->
            @if($enrollments->count() > 0)
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-gliker text-xl text-brand-navy-900">Continuar Aprendiendo</h2>
                        <a href="{{ route('education.my-learning') }}" class="text-sm font-bold text-brand-green-600 hover:text-brand-green-800">Ver todo</a>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($enrollments as $enrollment)
                            <div class="bg-white p-5 rounded-3xl shadow-sm border border-brand-soft-border flex flex-col sm:flex-row gap-5 items-center hover:shadow-md transition">
                                <div class="w-16 h-16 shrink-0 bg-brand-green-50 rounded-2xl flex items-center justify-center border-2 border-brand-green-100">
                                    <svg class="w-8 h-8 text-brand-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                                <div class="flex-1 text-center sm:text-left w-full">
                                    <h3 class="font-bold text-lg text-brand-navy-900">{{ $enrollment->program->title }}</h3>
                                    <div class="mt-2 flex items-center gap-3">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-brand-gold-400 h-2.5 rounded-full" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-brand-soft-textSec min-w-[3ch]">{{ $enrollment->progress_percentage }}%</span>
                                    </div>
                                </div>
                                <a href="{{ route('education.programs.show', $enrollment->program->id) }}" class="w-full sm:w-auto px-6 py-3 bg-brand-green-600 hover:bg-brand-green-700 text-white font-bold rounded-2xl transition shadow-sm text-center">
                                    Continuar
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recomendados para ti -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-gliker text-xl text-brand-navy-900">Recomendados para ti</h2>
                    <a href="{{ route('education.explore') }}" class="text-sm font-bold text-brand-green-600 hover:text-brand-green-800">Explorar catálogo</a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($featuredPrograms as $program)
                        <div class="bg-white rounded-3xl shadow-sm border border-brand-soft-border overflow-hidden hover:shadow-md hover:border-brand-green-300 transition group flex flex-col">
                            <div class="h-32 bg-brand-navy-900 p-6 flex flex-col justify-end relative overflow-hidden">
                                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
                                <span class="absolute top-4 right-4 bg-white/20 backdrop-blur text-white text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-widest">
                                    {{ ucfirst($program->level->value ?? 'Básico') }}
                                </span>
                                <h3 class="font-gliker text-xl text-white relative z-10 leading-tight">{{ $program->title }}</h3>
                            </div>
                            <div class="p-5 flex-1 flex flex-col">
                                <p class="text-sm text-brand-soft-textSec line-clamp-2 mb-4 flex-1">{{ $program->description }}</p>
                                <a href="{{ route('education.programs.show', $program->id) }}" class="block w-full py-2.5 bg-brand-green-50 text-brand-green-700 font-bold text-center rounded-xl hover:bg-brand-green-100 transition">
                                    Ver Programa
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 bg-white rounded-3xl p-8 border border-dashed border-gray-300 text-center">
                            <p class="text-gray-500 font-medium">¡Estás al día con todos nuestros programas destacados!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Derecha (Gamificación Widget) -->
        <div class="space-y-6">
            <!-- Reto del Día -->
            <div class="bg-gradient-to-br from-brand-navy-900 to-brand-navy-800 rounded-3xl p-6 text-white shadow-md relative overflow-hidden">
                <div class="absolute -right-6 -top-6 text-white/10">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                </div>
                <h3 class="font-bold text-brand-gold-400 mb-1 uppercase tracking-widest text-xs relative z-10">Reto Diario</h3>
                <p class="font-gliker text-xl mb-4 relative z-10">Completa 1 lección de Inventario</p>
                <div class="w-full bg-black/20 rounded-full h-2 mb-2 relative z-10">
                    <div class="bg-brand-gold-400 h-2 rounded-full" style="width: 0%"></div>
                </div>
                <p class="text-xs text-white/70 text-right relative z-10">0 / 1 completado</p>
                
                <a href="{{ route('education.explore') }}" class="mt-4 block w-full py-2 bg-white text-brand-navy-900 font-bold text-center rounded-xl hover:bg-gray-100 transition relative z-10">
                    Ir a Lecciones
                </a>
            </div>

            <!-- Liga Actual -->
            <div class="bg-white rounded-3xl border border-brand-soft-border p-6 shadow-sm">
                <h3 class="font-bold text-brand-navy-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Liga Emprendedor
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-gray-400 w-4">1</span>
                        <div class="w-8 h-8 rounded-full bg-gray-200"></div>
                        <span class="font-medium text-sm flex-1">María J.</span>
                        <span class="font-bold text-brand-navy-900 text-sm">450 XP</span>
                    </div>
                    <div class="flex items-center gap-3 bg-brand-green-50 p-2 -mx-2 rounded-xl">
                        <span class="font-bold text-brand-green-700 w-4">2</span>
                        <div class="w-8 h-8 rounded-full bg-brand-green-200 text-brand-green-700 flex items-center justify-center font-bold text-xs">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        <span class="font-bold text-brand-green-700 text-sm flex-1">Tú</span>
                        <span class="font-bold text-brand-green-700 text-sm">{{ $progress->total_xp ?? 0 }} XP</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-gray-400 w-4">3</span>
                        <div class="w-8 h-8 rounded-full bg-gray-200"></div>
                        <span class="font-medium text-sm flex-1">Carlos F.</span>
                        <span class="font-bold text-brand-navy-900 text-sm">120 XP</span>
                    </div>
                </div>
                <a href="{{ route('gamification.index') }}" class="mt-4 block text-center text-sm font-bold text-brand-green-600 hover:text-brand-green-800">Ver Clasificación Completa</a>
            </div>
        </div>
    </div>
</div>