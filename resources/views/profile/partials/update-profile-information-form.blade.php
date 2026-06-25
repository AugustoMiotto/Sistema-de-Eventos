<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informações do Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Atualize as informações da sua conta, foto de perfil e endereço de e-mail.
        </p>
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
                <input type="file" name="photo" accept="image/*"
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
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full focus:border-[#32A041] focus:ring-[#32A041]" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
            <label for="is_promoter" class="inline-flex items-start">
                <input id="is_promoter" type="checkbox" name="is_promoter" value="1"
                       {{ $user->is_promoter ? 'checked' : '' }}
                       class="mt-1 rounded border-gray-300 text-[#32A041] shadow-sm focus:ring-[#32A041]">
                <div class="ms-3 text-sm">
                    <span class="font-medium text-gray-700">Conta de Promotor</span>
                    <p class="text-gray-500">Desejo criar e gerir os meus próprios eventos na plataforma.</p>
                </div>
            </label>
        </div>

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
