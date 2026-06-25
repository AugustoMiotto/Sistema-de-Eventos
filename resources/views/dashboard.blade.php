<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Explorar Eventos
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-4 rounded-lg shadow-sm mb-8 border border-gray-100">
                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col md:flex-row gap-4">

                    <div class="flex-1">
                        <label for="search" class="sr-only">Pesquisar eventos</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   class="block w-full pl-10 border-gray-300 focus:border-[#32A041] focus:ring-[#32A041] rounded-md shadow-sm sm:text-sm"
                                   placeholder="Buscar pelo nome do evento...">
                        </div>
                    </div>

                    <div class="w-full md:w-64">
                        <label for="category" class="sr-only">Categoria</label>
                        <select name="category" id="category" class="block w-full border-gray-300 focus:border-[#32A041] focus:ring-[#32A041] rounded-md shadow-sm sm:text-sm">
                            <option value="">Todas as Categorias</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#32A041] hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#32A041]">
                            Filtrar
                        </button>

                        @if(request()->has('search') || request()->has('category'))
                            <a href="{{ route('dashboard') }}" class="w-full md:w-auto inline-flex justify-center items-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col group">

                            <div class="h-48 bg-gray-200 relative overflow-hidden">
                                @if($event->cover_photo)
                                    <img src="{{ asset('storage/' . $event->cover_photo) }}" alt="{{ $event->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif

                                @if($event->category)
                                    <div class="absolute top-4 left-4 bg-[#32A041] text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                        {{ $event->category->name }}
                                    </div>
                                @endif
                            </div>

                            <div class="p-5 flex-1 flex flex-col">
                                <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2" title="{{ $event->name }}">
                                    {{ $event->name }}
                               </h3>

                                <div class="text-sm text-gray-500 mb-4 space-y-2 flex-1">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 mr-2 text-[#32A041]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $event->start_at->format('d/m/Y \à\s H:i') }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 mr-2 text-[#32A041]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="line-clamp-1" title="{{ $event->location }}">{{ $event->location }}</span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                    <div class="text-lg font-black text-gray-900">
                                        @if($event->is_free)
                                            <span class="text-[#32A041]">Gratuito</span>
                                        @else
                                            R$ {{ number_format($event->price, 2, ',', '.') }}
                                        @endif
                                    </div>

                                    <a href="{{ route('events.show', $event->id) }}" class="text-sm font-semibold text-white bg-gray-900 hover:bg-gray-800 px-4 py-2 rounded-md transition-colors">
                                        Ver Mais
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $events->appends(request()->query())->links() }}
                </div>

            @else
                <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-100">
                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Nenhum evento disponível</h3>
                    <p class="mt-1 text-gray-500">
                        @if(request()->has('search') || request()->has('category'))
                            Não encontramos nenhum evento com os filtros selecionados.
                        @else
                            Ainda não temos eventos cadastrados na plataforma. Volte em breve!
                        @endif
                    </p>

                    @if(request()->has('search') || request()->has('category'))
                        <div class="mt-6">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-[#32A041] bg-green-50 hover:bg-green-100 focus:outline-none">
                                Limpar filtros de pesquisa
                            </a>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
