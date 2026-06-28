<script>
    function categoryForm() {
        return {
            openModal: false,
            loading: false,
            newCategory: { name: '', description: '' },
            errors: {},

            submitCategory() {
                this.loading = true;
                this.errors = {};

                fetch("{{ route('categories.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.newCategory)
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(result => {
                    this.loading = false;
                    if (result.status === 422) {
                        this.errors = result.body.errors;
                    }
                    else if (result.status === 201) {
                        let select = document.getElementById('category_id');
                        let option = new Option(result.body.category.name, result.body.category.id, true, true);
                        select.add(option);
                        this.openModal = false;
                        this.newCategory.name = '';
                        this.newCategory.description = '';
                    }
                })
                .catch(async error => {
                    this.loading = false;
                });
            }
        }
    }

    function speakerManager() {
        return {
            openSpeakerModal: false,
            isLoading: false,
            errorMessage: '',

            saveSpeaker() {
                this.isLoading = true;
                this.errorMessage = '';

                let formElement = document.getElementById('ajaxSpeakerForm');
                let formData = new FormData();

                formElement.querySelectorAll('input, textarea').forEach(input => {
                    let fieldName = input.getAttribute('data-field');
                    if (fieldName) {
                        if (input.type === 'file') {
                            if (input.files.length > 0) formData.append(fieldName, input.files[0]);
                        } else {
                            formData.append(fieldName, input.value);
                        }
                    }
                });

                fetch('{{ route('speakers.storeAjax') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) throw response;
                    return response.json();
                })
                .then(data => {
                    // CORREÇÃO: Cria e insere o Checkbox nativo em tempo real
                    let container = document.getElementById('speakers_checkbox_container');
                    let label = document.createElement('label');
                    label.className = 'flex items-center space-x-2.5 cursor-pointer hover:bg-gray-50 p-1 rounded transition-colors';

                    label.innerHTML = `
                        <input type="checkbox" name="speaker_ids[]" value="${data.id}" checked
                               class="rounded border-gray-300 text-[#32A041] focus:ring-[#32A041] transition">
                        <span class="text-sm text-gray-700 font-medium">
                            ${data.name} <span class="text-gray-400 text-xs">(${data.institution})</span>
                        </span>
                    `;

                    container.appendChild(label);
                    this.openSpeakerModal = false;

                    // Limpa os campos do formulário
                    formElement.querySelectorAll('input, textarea').forEach(input => {
                        input.value = '';
                    });
                })
                .catch(async error => {
                    if (error.status === 422) {
                        const data = await error.json();
                        if (data.errors) {
                            const primeiroCampoComErro = Object.keys(data.errors)[0];
                            this.errorMessage = data.errors[primeiroCampoComErro][0];
                        } else {
                            this.errorMessage = data.message || 'Verifique os dados informados.';
                        }
                    } else {
                        this.errorMessage = 'Ocorreu um erro inesperado no servidor.';
                    }
                })
                .finally(() => {
                    this.isLoading = false;
                });
            }
        }
    }
</script>
