<x-filament-panels::page.simple class="bg-gray-900 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md sm:max-w-lg md:max-w-md lg:max-w-md p-6 sm:p-8 bg-gray-800 rounded-2xl shadow-xl">

        {{-- Logo --}}
        <div class="text-center mb-6">
            <img src="{{ asset('logo.png') }}" alt="Boqueirão Remates" class="mx-auto h-20 sm:h-24">
            <h1 class="text-2xl sm:text-3xl font-bold text-white mt-4">
                Boqueirão Remates
            </h1>
            <p class="text-gray-300 mt-1 text-sm sm:text-base">
                Plataforma moderna e segura para remates digitais
            </p>
        </div>

        {{-- Mensagem de Primeiro Acesso --}}
        @if ($firstAccess)
            <div class="mb-4 p-4 rounded bg-blue-600 text-white text-center font-semibold text-sm sm:text-base">
                Você está no seu <strong>primeiro acesso</strong>. Por segurança, defina sua nova senha abaixo.
            </div>
        @endif

        {{-- Formulário --}}
        {{ $this->form }}

        {{-- Botão --}}
        <div class="mt-4">
            @if ($firstAccess)
                <x-filament::button wire:click="saveNewPassword" class="w-full text-lg sm:text-xl py-2 sm:py-3">
                    Salvar nova senha
                </x-filament::button>
            @else
                <x-filament::button type="submit" class="w-full text-lg sm:text-xl py-2 sm:py-3">
                    Entrar
                </x-filament::button>
            @endif
        </div>

        {{-- Rodapé --}}
        <p class="text-gray-400 text-xs sm:text-sm mt-6 text-center">
            © {{ date('Y') }} Boqueirão Remates. Todos os direitos reservados.
        </p>
    </div>
</x-filament-panels::page.simple>
