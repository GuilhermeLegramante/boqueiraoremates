<x-filament-panels::page>
    @push('filamentScripts')
        <script>
            document.querySelector('aside[data-sidebar]').style.display = 'none';
        </script>
    @endpush
    
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 shadow-md border border-white/10">
            <h2 class="text-2xl font-bold text-center mb-4 text-white">Bem-vindo(a) à nova plataforma da Boqueirão
                Remates!</h2>

            <p class="text-gray-200 text-justify">
                Estamos muito felizes em apresentar o novo sistema de <strong>remates online da Boqueirão
                    Remates</strong>,
                desenvolvido para oferecer mais <strong>modernidade, praticidade e segurança</strong> para nossos
                clientes e parceiros.
                <br><br>
                No seu <strong>primeiro acesso</strong>, é necessário definir uma nova senha pessoal. Essa etapa é
                importante para
                garantir a proteção dos seus dados e o uso seguro da plataforma.
            </p>
        </div>

        <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 shadow-md border border-white/10">
            <h3 class="text-xl font-semibold text-white mb-4 text-center">Defina sua nova senha</h3>

            {{ $this->form }}

            <div class="flex justify-center mt-6">
                <x-filament::button wire:click="save" size="lg">
                    Salvar nova senha
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament-panels::page>
