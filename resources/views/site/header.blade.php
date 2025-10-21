<header class="fixed top-0 left-0 w-full shadow-md z-50"
    style="background-image: url('{{ asset('img/fundo_topo.png') }}'); background-size: cover; background-position: center;">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/20"></div>

    <div class="relative container mx-auto px-6 py-1 flex justify-between items-start">
        <!-- Logo esquerda -->
        <div class="flex-shrink-0 ml-4 -mt-2">
            <img src="{{ asset('img/logo_header.png') }}" alt="Logo" class="h-28 md:h-32">
        </div>

        <!-- Conteúdo centralizado (menu + Whats/Login) -->
        <div class="flex flex-col items-center space-y-2 mt-6 md:flex md:items-center">
            <!-- Menu Desktop -->
            <nav
                class="hidden md:flex space-x-3 bg-[url('{{ asset('img/wood_texture.png') }}')] bg-white/10 backdrop-blur-md rounded-full px-3 py-1">
                <a href="{{ route('home') }}"
                    class="px-3 py-1 text-sm font-medium text-white rounded-full hover:bg-yellow-600 hover:text-black transition">
                    Início
                </a>
                <a href="{{ route('home') }}#proximos-remates"
                    class="px-3 py-1 text-sm font-medium text-white rounded-full hover:bg-yellow-600 hover:text-black transition">
                    Próximos Eventos
                </a>
                <a href="{{ route('home') }}#equipe"
                    class="px-3 py-1 text-sm font-medium text-white rounded-full hover:bg-yellow-600 hover:text-black transition">
                    Equipe
                </a>
                <a href="{{ route('home') }}#contato"
                    class="px-3 py-1 text-sm font-medium text-white rounded-full hover:bg-yellow-600 hover:text-black transition">
                    Contato
                </a>
            </nav>

            <!-- WhatsApp + Login Desktop -->
            <div class="hidden md:flex flex-col items-center space-y-1 mt-2">
                <div class="flex space-x-2">
                    <!-- Botões WhatsApp -->
                    <a href="https://wa.me/5555997331395" target="_blank"
                        class="flex items-center px-3 py-1 text-sm font-medium rounded-md bg-green-600 text-white hover:bg-green-700 transition">
                        55 9 9733-1395
                    </a>
                    <a href="https://wa.me/5555996058499" target="_blank"
                        class="flex items-center px-3 py-1 text-sm font-medium rounded-md bg-green-600 text-white hover:bg-green-700 transition">
                        55 9 9605-8499
                    </a>
                </div>

                <div class="flex space-x-3">
                    @if (auth()->check())
                        <span class="px-3 py-1 text-xs font-semibold text-white rounded-md">
                            Bem-vindo, {{ auth()->user()->name }}
                        </span>
                        <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                            @csrf
                            <button type="submit"
                                class="px-3 py-1 text-xs font-semibold bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                                Sair
                            </button>
                        </form>
                    @else
                        <a href="{{ route('filament.admin.auth.login') }}"
                            class="px-3 py-1 text-xs font-semibold bg-yellow-500 text-black rounded-md hover:bg-yellow-600 transition">
                            Login
                        </a>
                        <a href="{{ route('filament.admin.auth.register') }}"
                            class="px-3 py-1 text-xs font-semibold bg-yellow-500 text-black rounded-md hover:bg-yellow-600 transition">
                            Cadastro
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Logo direita + botão mobile -->
        <div class="flex-shrink-0 mr-4 -mt-2 flex items-center">
            <!-- Esconde logo em mobile -->
            <img src="{{ asset('img/logo_rodrigo.png') }}" alt="Logo Secundária" class="h-28 md:h-32 hidden sm:block">

            <!-- Botão menu mobile (apenas mobile) -->
            <button id="menu-toggle"
                class="md:hidden text-white bg-yellow-600 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-400 ml-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div id="mobile-menu"
        class="fixed top-0 left-0 w-full h-screen bg-black/90 backdrop-blur-sm flex flex-col items-center justify-center space-y-4 text-white text-lg font-semibold transform -translate-y-full opacity-0 transition-all duration-300 ease-in-out">

        <!-- Botão fechar -->
        <button id="menu-close"
            class="absolute top-4 right-4 text-white text-3xl font-bold focus:outline-none">&times;</button>

        <a href="{{ route('home') }}" class="hover:text-yellow-400">Início</a>
        <a href="{{ route('home') }}#proximos-remates" class="hover:text-yellow-400">Próximos Eventos</a>
        <a href="{{ route('home') }}#equipe" class="hover:text-yellow-400">Equipe</a>
        <a href="{{ route('home') }}#contato" class="hover:text-yellow-400">Contato</a>

        <div class="mt-6 flex flex-col items-center space-y-3">
            <a href="https://wa.me/5555997331395" target="_blank"
                class="px-4 py-2 bg-green-600 rounded-md text-white hover:bg-green-700 transition">
                55 99733 1395
            </a>
            <a href="https://wa.me/5555996058499" target="_blank"
                class="px-4 py-2 bg-green-600 rounded-md text-white hover:bg-green-700 transition">
                55 99605 8499
            </a>

            @if (auth()->check())
                <span class="text-sm">Bem-vindo, {{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 rounded-md text-white hover:bg-red-600 transition">
                        Sair
                    </button>
                </form>
            @else
                <a href="{{ route('filament.admin.auth.login') }}"
                    class="px-4 py-2 bg-yellow-500 rounded-md text-black hover:bg-yellow-600 transition">
                    Login
                </a>
                <a href="{{ route('filament.admin.auth.register') }}"
                    class="px-4 py-2 bg-yellow-500 rounded-md text-black hover:bg-yellow-600 transition">
                    Cadastro
                </a>
            @endif
        </div>
    </div>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const menuClose = document.getElementById('menu-close');
        const mobileMenu = document.getElementById('mobile-menu');

        function openMenu() {
            mobileMenu.classList.remove('-translate-y-full', 'opacity-0');
            mobileMenu.classList.add('translate-y-0', 'opacity-100');
        }

        function closeMenu() {
            mobileMenu.classList.add('-translate-y-full', 'opacity-0');
            mobileMenu.classList.remove('translate-y-0', 'opacity-100');
        }

        menuToggle.addEventListener('click', openMenu);
        menuClose.addEventListener('click', closeMenu);
    </script>
</header>
