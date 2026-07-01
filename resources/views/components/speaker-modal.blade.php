@props(['speakers', 'currentSpeakers' => []])

<div class="md:col-span-2 border-t border-gray-100 pt-4" x-data="speakerManager()">
    <div class="flex items-center justify-between mb-2">
        <label for="speaker_ids" class="block font-medium text-sm text-gray-700">
            Palestrantes
        </label>
        <button type="button" @click="openSpeakerModal = true" class="text-sm font-semibold text-[#32A041] hover:text-green-700 transition">
            + Registar Novo Palestrante
        </button>
    </div>

    <div id="speakers_checkbox_container" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm p-3 max-h-44 overflow-y-auto space-y-1.5 bg-white">
        @foreach($speakers as $speaker)
            <label class="flex items-center space-x-2.5 cursor-pointer hover:bg-gray-50 p-1 rounded transition-colors">
                <input type="checkbox" name="speaker_ids[]" value="{{ $speaker->id }}"
                       {{ in_array($speaker->id, old('speaker_ids', $currentSpeakers)) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-[#32A041] focus:ring-[#32A041] transition">
                <span class="text-sm text-gray-700 font-medium">
                    {{ $speaker->name }} <span class="text-gray-400 text-xs">({{ $speaker->institution }})</span>
                </span>
            </label>
        @endforeach
    </div>
    <x-input-error :messages="$errors->get('speaker_ids')" class="mt-2" />

    <div x-show="openSpeakerModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div x-show="openSpeakerModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openSpeakerModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="openSpeakerModal" x-transition class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Registar Novo Palestrante</h3>

                    <div id="ajaxSpeakerForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nome Completo</label>
                            <input type="text" data-field="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">
                        </div>
                        <div x-data="{ emailValido: true }">
                            <label class="block text-sm font-medium text-gray-700">E-mail Profissional</label>
                            <input type="email" data-field="email" placeholder="exemplo@email.com"
                                @input="
                                    $event.target.value = $event.target.value.toLowerCase().replace(/\s/g, '');
                                    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                    emailValido = regex.test($event.target.value) || $event.target.value === '';
                                "
                                class="mt-1 block w-full rounded-md shadow-sm transition-colors duration-200"
                                :class="emailValido
                                    ? 'border-gray-300 focus:border-[#32A041] focus:ring-[#32A041]'
                                    : 'border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50/30'"
                            >
                            <p x-show="!emailValido" style="display: none;" class="text-xs text-red-600 mt-1 font-medium">
                                Por favor, insira um formato de e-mail válido.
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telefone / WhatsApp </label>
                            <input type="text" data-field="phone"
                                x-mask:dynamic="$input.length > 14 ? '(99) 99999-9999' : '(99) 99999-9999'"
                                placeholder="(00) 00000-0000"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Titulação (Ex: Doutor, Tech Lead)</label>
                            <input type="text" data-field="expertise_area" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Instituição / Empresa</label>
                            <input type="text" data-field="institution" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Minicurrículo</label>
                            <textarea data-field="bio" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#32A041] focus:ring-[#32A041]"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Fotografia de Perfil</label>
                            <input type="file" data-field="profile_photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500">
                        </div>
                    </div>
                    <p x-show="errorMessage" x-text="errorMessage" class="mt-2 text-sm text-red-600 font-semibold"></p>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="saveSpeaker" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#32A041] text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#32A041] sm:ml-3 sm:w-auto sm:text-sm" :disabled="isLoading">
                        <span x-show="!isLoading">Gravar Palestrante</span>
                        <span x-show="isLoading">A processar...</span>
                    </button>
                    <button type="button" @click="openSpeakerModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#32A041] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
