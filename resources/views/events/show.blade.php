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

                <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Sobre o Evento</h2>
                    <div class="prose max-w-none text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $event->description ?? 'Nenhuma descrição fornecida pelo organizador.' }}
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Público-Alvo</h3>
                        <p class="text-gray-900 font-medium">{{ $event->target_audience }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Vagas Disponíveis</h3>
                        <p class="text-gray-900 font-medium">{{ $event->max_slots }} participantes</p>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden sticky top-8">
                    <div class="p-6 md:p-8">

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

                        <button type="button" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-bold text-white bg-[#32A041] hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#32A041] transition-colors">
                            Garantir minha vaga
                        </button>

                        <p class="text-xs text-center text-gray-500 mt-4">Ao se inscrever, você concorda com os termos do evento.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
