<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">Nome</label>
            <input id="name" class="block mt-1 w-full border-gray-300 focus:border-[#32A041] focus:ring-[#32A041] rounded-md shadow-sm"
                   type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="email" class="block font-medium text-sm text-gray-700">E-mail</label>
            <input id="email" class="block mt-1 w-full border-gray-300 focus:border-[#32A041] focus:ring-[#32A041] rounded-md shadow-sm"
                   type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password" class="block font-medium text-sm text-gray-700">Senha</label>
            <input id="password" class="block mt-1 w-full border-gray-300 focus:border-[#32A041] focus:ring-[#32A041] rounded-md shadow-sm"
                   type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirmar Senha</label>
            <input id="password_confirmation" class="block mt-1 w-full border-gray-300 focus:border-[#32A041] focus:ring-[#32A041] rounded-md shadow-sm"
                   type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>


        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-[#32A041] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#32A041]" href="{{ route('login') }}">
                Já possui uma conta?
            </a>

            <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-[#32A041] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-[#32A041] focus:ring-offset-2 transition ease-in-out duration-150">
                Cadastrar
            </button>
        </div>
    </form>
</x-guest-layout>
