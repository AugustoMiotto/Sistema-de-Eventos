<x-app-layout>
    <div class="w-full h-64 md:h-96 bg-gray-200 relative overflow-hidden">
        @if($event->cover_photo)
            <img src="{{ asset('storage/' . $event->cover_photo) }}" alt="{{ $event->name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100">
                <svg class="h-20 w-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Sem foto de capa</span>
            </div>
        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent"></div>

        <div class="absolute bottom-0 left-0 w-full p-6 md:p-12 max-w-7xl mx-auto">
            @if($event->category)
                <span class="inline-block px-3 py-1 bg-[#32A041] text-white text-xs font-bold rounded-full mb-3 shadow-sm">
                    {{ $event->category->name }}
                </span>
            @endif
            <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight drop-shadow-md">
                {{ $event->name }}
            </h1>
        </div>
    </div>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">

            <div class="w-full lg:w-2/3 space-y-8">

                @php
                    // Conta apenas as inscrições ativas e confirmadas
                    $vagasOcupadas = $event->users()
                        ->whereNull('registrations.deleted_at')
                        ->where('registrations.status', 'Registered')
                        ->count();

                    // Calcula quantas restam (garantindo que não fique um número negativo)
                    $vagasRestantes = max(0, $event->max_slots - $vagasOcupadas);
                @endphp

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Público-Alvo</h3>
                        <p class="text-gray-900 font-medium">{{ $event->target_audience }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Vagas Totais</h3>
                        <p class="text-gray-900 font-medium">{{ $event->max_slots }} vagas</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Vagas Restantes</h3>
                        <p class="font-bold {{ $vagasRestantes > 0 ? 'text-[#32A041]' : 'text-red-600' }}">
                            @if($vagasRestantes > 0)
                                {{ $vagasRestantes }} {{ $vagasRestantes === 1 ? 'vaga disponível' : 'vagas disponíveis' }}
                            @else
                                Esgotadas
                            @endif
                        </p>
                    </div>
                </div>

                @if($event->speakers->count() > 0)
                    <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Palestrantes Convidados</h2>

                        <div class="space-y-6">
                            @foreach($event->speakers as $speaker)
                                <div class="flex flex-col sm:flex-row gap-5 p-5 border border-gray-100 rounded-xl bg-gray-50/50 hover:bg-gray-50 transition-colors duration-200">

                                    <div class="flex-shrink-0 flex justify-center sm:justify-start">
                                        @if($speaker->profile_photo_path)
                                            <img src="{{ asset('storage/' . $speaker->profile_photo_path) }}" alt="Foto de {{ $speaker->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-sm">
                                        @else
                                            <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 border-4 border-white shadow-sm">
                                                <svg class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 text-center sm:text-left">
                                        <h3 class="text-lg font-extrabold text-gray-900">{{ $speaker->name }}</h3>

                                        <div class="mt-1 flex flex-wrap items-center justify-center sm:justify-start gap-2 text-sm font-semibold text-[#32A041]">
                                            <span>{{ $speaker->expertise_area }}</span>
                                            <span class="text-gray-300">&bull;</span>
                                            <span class="text-gray-700">{{ $speaker->institution }}</span>
                                        </div>

                                        <p class="mt-3 text-sm text-gray-600 leading-relaxed text-justify sm:text-left">
                                            {{ $speaker->bio }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div> <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden sticky top-8">
                    <div class="p-6 md:p-8">

                        @if(session('success'))
                            <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 text-sm font-semibold rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm font-semibold rounded-lg">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="text-center mb-6">
                            @if($event->is_free)
                                <span class="text-4xl font-black text-[#32A041]">Gratuito</span>
                            @else
                                <span class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Valor da Inscrição</span>
                                <div class="text-4xl font-black text-gray-900 mt-1">
                                    R$ {{ number_format($event->price, 2, ',', '.') }}
                                </div>
                            @endif
                        </div>

                        <hr class="border-gray-100 mb-6">

                        <div class="space-y-4 mb-8">
                            <div class="flex items-start">
                                <svg class="h-6 w-6 text-[#32A041] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $event->start_at->format('d/m/Y') }}</p>
                                    <p class="text-sm text-gray-500">às {{ $event->start_at->format('H:i') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="h-6 w-6 text-[#32A041] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $event->location }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="h-6 w-6 text-[#32A041] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Organizado por</p>
                                    <p class="text-sm text-gray-500">{{ $event->promoter->name ?? 'Organizador' }}</p>
                                </div>
                            </div>
                        </div>
                        @if($event->promoter_id === auth()->id())
                                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl shadow-sm">
                                    <div class="flex items-center space-x-2 text-blue-800 font-bold text-sm mb-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                        <span>Painel do Organizador</span>
                                    </div>
                                    <a href="{{ route('events.attendees', $event->id) }}" class="w-full inline-flex justify-center items-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-gray-800 hover:bg-gray-700 transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        Gerenciar Inscritos
                                    </a>
                                </div>
                                <hr class="border-gray-100 mb-6">
                            @endif
                        @auth
                            @php
                                // Verifica de forma leve se o usuário logado possui inscrição ativa neste evento
                                $jáInscrito = $event->users()->where('user_id', auth()->id())->whereNull('registrations.deleted_at')->where('status', 'Registered')->exists();
                                $vagasEsgotadas = $event->users()->count() >= $event->max_slots;
                            @endphp

                            @if($jáInscrito)
                                <div class="space-y-3">
                                    <div class="p-3 bg-green-50 border border-green-200 text-green-700 text-sm font-bold rounded-lg text-center shadow-sm">
                                        ✓ Sua vaga está garantida!
                                    </div>

                                    <form method="POST" action="{{ route('events.cancel', $event->id) }}"
                                          onsubmit="return confirm('Tem certeza que deseja cancelar sua inscrição neste evento? Isso liberará sua vaga para outros participantes.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-red-200 rounded-md shadow-sm text-sm font-bold text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            Cancelar Inscrição
                                        </button>
                                    </form>
                                </div>
                            @elseif($vagasEsgotadas)
                                <button type="button" disabled class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-bold text-white bg-red-500 cursor-not-allowed">
                                    Vagas Esgotadas
                                </button>
                            @else
                                <form method="POST" action="{{ route('events.register', $event->id) }}">
                                    @csrf
                                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-bold text-white bg-[#32A041] hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#32A041] transition-colors">
                                        Garantir minha vaga
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-bold text-white bg-gray-800 hover:bg-gray-900 text-center transition-colors">
                                Faça login para se inscrever
                            </a>
                        @endauth

                        <p class="text-xs text-center text-gray-500 mt-4">Ao se inscrever, você concorda com os termos do evento.</p>
                    </div>
                </div>
            </div> </div>
    </div>
</x-app-layout>
