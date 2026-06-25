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

                            <select id="category_id" name="category_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">
                                <option value="">Selecione uma categoria...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />

                            {{-- MODAL DE CRIAR CATEGORIA --}}
                            <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                    <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openModal = false"></div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                    <div x-show="openModal" x-transition class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">Criar Nova Categoria</h3>

                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Nome da Categoria</label>
                                                    <input type="text" x-model="newCategory.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">
                                                    <p x-show="errors.name" x-text="errors.name[0]" class="text-sm text-red-600 mt-1"></p>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Descrição (Opcional)</label>
                                                    <textarea x-model="newCategory.description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="button" @click="submitCategory" :disabled="loading" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#32A041] text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                                                <span x-show="!loading">Salvar Categoria</span>
                                                <span x-show="loading">Salvando...</span>
                                            </button>
                                            <button type="button" @click="openModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
<script>
    function categoryForm() {
        return {
            openModal: false,
            loading: false,
            newCategory: {
                name: '',
                description: ''
            },
            errors: {},

            submitCategory() {
                this.loading = true;
                this.errors = {};

                fetch("{{ route('categories.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Ficha de segurança nativa do Laravel
                    },
                    body: JSON.stringify(this.newCategory)
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(result => {
                    this.loading = false;

                    // Se o Laravel recusar (Erro 422 - Validação)
                    if (result.status === 422) {
                        this.errors = result.body.errors;
                    }
                    // Se a categoria for criada com sucesso
                    else if (result.status === 201) {
                        // Pega o select atual
                        let select = document.getElementById('category_id');

                        // Cria a nova opção
                        let option = new Option(result.body.category.name, result.body.category.id, true, true);

                        // Adiciona na lista e já deixa selecionada!
                        select.add(option);

                        // Fecha o modal e limpa os campos para a próxima vez
                        this.openModal = false;
                        this.newCategory.name = '';
                        this.newCategory.description = '';
                    }
                })
                .catch(error => {
                    this.loading = false;
                    console.error('Erro:', error);
                    alert('Ocorreu um erro ao salvar a categoria. Verifique sua conexão.');
                });
            }
        }
    }
</script>
</x-app-layout>
