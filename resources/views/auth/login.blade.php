<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">E-mail</label>
            <input id="email" class="block mt-1 w-full border-gray-300 focus:border-[#32A041] focus:ring-[#32A041] rounded-md shadow-sm"
                   type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password" class="block font-medium text-sm text-gray-700">Senha</label>
            <input id="password" class="block mt-1 w-full border-gray-300 focus:border-[#32A041] focus:ring-[#32A041] rounded-md shadow-sm"
                   type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-gray-300 text-[#32A041] shadow-sm focus:ring-[#32A041]">
                <span class="ms-2 text-sm text-gray-600">Lembrar de mim</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#32A041]" href="{{ route('password.request') }}">
                    Esqueceu sua senha?
                </a>
            @endif

            <button type="submit" class="ms-3 inline-flex items-center px-4 py-2 bg-[#32A041] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-[#32A041] focus:ring-offset-2 transition ease-in-out duration-150">
                Entrar
            </button>
        </div>

        <div class="mt-6 text-center border-t border-gray-100 pt-4">
            <p class="text-sm text-gray-600">
                Não tem uma conta?
                <a href="{{ route('register') }}" class="font-bold text-[#32A041] hover:text-green-800 hover:underline">
                    Crie aqui
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
