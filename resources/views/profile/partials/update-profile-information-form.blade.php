<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informações do Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Atualize as informações da sua conta e foto de perfil. </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="flex items-center space-x-6">
            <div class="shrink-0">
                @if ($user->profile_photo_path)
                    <img class="h-16 w-16 object-cover rounded-full border border-gray-200" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Foto de Perfil">
                @else
                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xl">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <label class="block">
                <span class="sr-only">Escolha a foto de perfil</span>
                <input type="file" name="profile_photo" accept="image/*"
                    class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-green-50 file:text-[#32A041]
                    hover:file:bg-green-100" />
            </label>
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <div>
            <x-input-label for="name" value="Nome" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full focus:border-[#32A041] focus:ring-[#32A041]" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="E-mail (Não alterável)" />
            <x-text-input id="email" name="email" type="email"
                          class="mt-1 block w-full bg-gray-100 text-gray-500 cursor-not-allowed border-gray-300 shadow-sm focus:border-gray-300 focus:ring-0"
                          :value="old('email', $user->email)"
                          disabled readonly required autocomplete="username" />
            <span class="text-xs text-gray-400 mt-1 block">O e-mail é vinculado à sua conta institucional e não pode ser alterado.</span>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

       @php
            $email = auth()->user()->email;
            // É aluno?
            $isStudent = preg_match('/@aluno\.[a-zA-Z0-9-]+\.ifrs\.edu\.br$/i', $email);
            // É institucional (termina em ifrs.edu.br)?
            $isInstitutional = preg_match('/@[a-zA-Z0-9-]+\.ifrs\.edu\.br$/i', $email);
            // Só pode ser promotor se for institucional E NÃO for aluno
            $canBePromoter = $isInstitutional && !$isStudent;
        @endphp

        @if($canBePromoter)
            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <label for="is_promoter" class="inline-flex items-center">
                    <input id="is_promoter" type="checkbox" name="is_promoter" value="1"
                           class="rounded border-gray-300 text-[#32A041] shadow-sm focus:ring-[#32A041]"
                           {{ old('is_promoter', $user->is_promoter) ? 'checked' : '' }}>
                    <span class="ms-2 text-sm text-gray-600">Desejo ativar o modo Promotor de Eventos</span>
                </label>
                <p class="text-xs text-gray-500 mt-1 ms-6">
                    Ative esta opção para criar e gerenciar eventos na plataforma institucional.
                </p>
            </div>
        @elseif($isStudent)
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-600 font-medium">Modo Promotor Restrito (Discente)</p>
                <p class="text-xs text-red-500 mt-1">
                    De acordo com as regras institucionais, contas de discentes (alunos) não possuem permissão para criar eventos.
                </p>
            </div>
        @else
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-700 font-medium">Modo Promotor Indisponível (Conta Externa)</p>
                <p class="text-xs text-yellow-600 mt-1">
                    A criação de eventos é restrita a servidores do IFRS utilizando e-mail institucional. Sua conta pode ser usada normalmente para participar de eventos.
                </p>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#32A041] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-[#32A041] focus:ring-offset-2 transition ease-in-out duration-150">
                Guardar
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium"
                >Salvo com sucesso.</p>
            @endif
        </div>
    </form>
</section>
