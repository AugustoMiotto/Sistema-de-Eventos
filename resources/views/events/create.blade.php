<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Criar Novo Evento
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-100">

                <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="name" class="block font-medium text-sm text-gray-700">Nome do Evento</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                       <div x-data="categoryForm()">
                                <div class="flex items-center justify-between mb-1">
                                    <label for="category_id" class="block font-medium text-sm text-gray-700">Categoria</label>
                                    <button type="button" @click="openModal = true" class="text-xs text-[#32A041] hover:text-green-800 hover:underline font-bold transition">
                                        Não achou a categoria? Crie agora mesmo!
                                    </button>
                                </div>

                                <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">
                                    <option value="">Selecione uma categoria...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $event->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />

                                <x-category-modal />
                            </div>

                        <div>
                            <label for="start_at" class="block font-medium text-sm text-gray-700">Data e Hora de Início</label>
                            <input type="datetime-local" id="start_at" name="start_at" value="{{ old('start_at') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">
                            <x-input-error :messages="$errors->get('start_at')" class="mt-2" />
                        </div>

                        <div>
                            <label for="location" class="block font-medium text-sm text-gray-700">Localização</label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}" required placeholder="Ex: Auditório Principal, Bloco A"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div>
                            <label for="target_audience" class="block font-medium text-sm text-gray-700">Público Alvo</label>
                            <input type="text" id="target_audience" name="target_audience" value="{{ old('target_audience') }}" required placeholder="Ex: Estudantes de TI"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">
                            <x-input-error :messages="$errors->get('target_audience')" class="mt-2" />
                        </div>

                        <div>
                            <label for="max_slots" class="block font-medium text-sm text-gray-700">Total de Vagas</label>
                            <input type="number" id="max_slots" name="max_slots" value="{{ old('max_slots') }}" required min="1"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">
                            <x-input-error :messages="$errors->get('max_slots')" class="mt-2" />
                        </div>

                        <div>
                            <label for="price" class="block font-medium text-sm text-gray-700">Valor (R$)</label>
                            <input type="number" step="0.01" id="price" name="price" value="{{ old('price') }}" placeholder="0.00 para gratuito"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">
                            <p class="text-xs text-gray-500 mt-1">Deixe em branco ou 0 para eventos gratuitos.</p>
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block font-medium text-sm text-gray-700">Descrição do Evento</label>
                            <textarea id="description" name="description" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-error :messages="$errors->get('speaker_ids')" class="mt-2" />

                            <x-speaker-modal :speakers="$speakers" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-medium text-sm text-gray-700 mb-2">Foto de Capa</label>
                            <input type="file" name="cover_photo" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-[#32A041] hover:file:bg-green-100">
                            <x-input-error :messages="$errors->get('cover_photo')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-gray-100 pt-6">
                        <a href="{{ route('dashboard') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#32A041] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-[#32A041] focus:ring-offset-2 transition">
                            Salvar Evento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<x-event-modals-scripts />
</x-app-layout>
