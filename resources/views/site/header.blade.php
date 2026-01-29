<header class="fixed top-0 left-0 w-full shadow-md z-50"
    style="background-image: url('{{ asset('img/fundo_topo.png') }}'); background-size: cover; background-position: center;">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/20"></div>

    <div class="relative container mx-auto px-6 py-1 flex justify-between items-start">
        <!-- Logo esquerda -->
        <div class="flex-shrink-0 ml-4 -mt-2">
            <a href="{{ route('home') }}">
                <img src="{{ asset('img/logo_header_10_anos.png') }}" alt="Logo" class="h-16 md:h-20">
            </a>
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
                <a href="{{ route('home') }}#vendas-permanentes"
                    class="px-3 py-1 text-sm font-medium text-white rounded-full hover:bg-yellow-600 hover:text-black transition">
                    Vendas Diretas
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M20.52 3.48A11.93 11.93 0 0012 0C5.37 0 0 5.37 0 12c0 2.11.55 4.16 1.6 5.95L0 24l6.2-1.62A11.96 11.96 0 0012 24c6.63 0 12-5.37 12-12 0-3.19-1.24-6.2-3.48-8.52zM12 22a9.96 9.96 0 01-5.13-1.46l-.37-.22-3.68.96.99-3.58-.24-.37A9.96 9.96 0 012 12c0-5.52 4.48-10 10-10 2.67 0 5.18 1.04 7.07 2.93A9.96 9.96 0 0122 12c0 5.52-4.48 10-10 10zm5.29-7.71c-.29-.14-1.71-.84-1.97-.93s-.46-.14-.66.14c-.19.29-.76.93-.93 1.12-.17.19-.34.22-.63.07-.29-.14-1.22-.45-2.32-1.43-.86-.76-1.43-1.71-1.6-2-.17-.29-.02-.45.12-.59.12-.12.29-.31.43-.46.14-.14.19-.24.29-.41.1-.19.05-.36-.02-.5-.07-.14-.66-1.59-.91-2.18-.24-.58-.49-.5-.66-.51h-.57c-.19 0-.5.07-.76.36-.26.29-1 1-1 2.43s1.02 2.82 1.16 3.01c.14.19 2 3.05 4.85 4.28.68.29 1.21.46 1.62.59.68.22 1.29.19 1.77.12.54-.08 1.71-.7 1.95-1.38.24-.67.24-1.24.17-1.38-.05-.14-.24-.22-.5-.34z" />
                        </svg>
                        55 9 9733-1395
                    </a>
                    <a href="https://wa.me/5555996058499" target="_blank"
                        class="flex items-center px-3 py-1 text-sm font-medium rounded-md bg-green-600 text-white hover:bg-green-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M20.52 3.48A11.93 11.93 0 0012 0C5.37 0 0 5.37 0 12c0 2.11.55 4.16 1.6 5.95L0 24l6.2-1.62A11.96 11.96 0 0012 24c6.63 0 12-5.37 12-12 0-3.19-1.24-6.2-3.48-8.52zM12 22a9.96 9.96 0 01-5.13-1.46l-.37-.22-3.68.96.99-3.58-.24-.37A9.96 9.96 0 012 12c0-5.52 4.48-10 10-10 2.67 0 5.18 1.04 7.07 2.93A9.96 9.96 0 0122 12c0 5.52-4.48 10-10 10zm5.29-7.71c-.29-.14-1.71-.84-1.97-.93s-.46-.14-.66.14c-.19.29-.76.93-.93 1.12-.17.19-.34.22-.63.07-.29-.14-1.22-.45-2.32-1.43-.86-.76-1.43-1.71-1.6-2-.17-.29-.02-.45.12-.59.12-.12.29-.31.43-.46.14-.14.19-.24.29-.41.1-.19.05-.36-.02-.5-.07-.14-.66-1.59-.91-2.18-.24-.58-.49-.5-.66-.51h-.57c-.19 0-.5.07-.76.36-.26.29-1 1-1 2.43s1.02 2.82 1.16 3.01c.14.19 2 3.05 4.85 4.28.68.29 1.21.46 1.62.59.68.22 1.29.19 1.77.12.54-.08 1.71-.7 1.95-1.38.24-.67.24-1.24.17-1.38-.05-.14-.24-.22-.5-.34z" />
                        </svg>
                        55 9 9605-8499
                    </a>
                </div>

                <div class="flex space-x-3">
                    @if (auth()->check())
                        <span class="px-3 py-1 text-xs font-semibold text-white rounded-md">
                            Bem-vindo, {{ auth()->user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="px-3 py-1 text-xs font-semibold bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                                Sair
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
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
        <div class="flex-shrink-0 mr-4 mt-8 flex items-center">
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
        <a href="{{ route('home') }}#vendas-permanentes" class="hover:text-yellow-400">Vendas Diretas</a>
        <a href="{{ route('home') }}#contato" class="hover:text-yellow-400">Contato</a>

        <div class="mt-6 flex flex-col items-center space-y-3">
            <a href="https://wa.me/5555997331395" target="_blank"
                class="flex items-center px-3 py-1 text-sm font-medium rounded-md bg-green-600 text-white hover:bg-green-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 fill-current" viewBox="0 0 24 24">
                    <path
                        d="M20.52 3.48A11.93 11.93 0 0012 0C5.37 0 0 5.37 0 12c0 2.11.55 4.16 1.6 5.95L0 24l6.2-1.62A11.96 11.96 0 0012 24c6.63 0 12-5.37 12-12 0-3.19-1.24-6.2-3.48-8.52zM12 22a9.96 9.96 0 01-5.13-1.46l-.37-.22-3.68.96.99-3.58-.24-.37A9.96 9.96 0 012 12c0-5.52 4.48-10 10-10 2.67 0 5.18 1.04 7.07 2.93A9.96 9.96 0 0122 12c0 5.52-4.48 10-10 10zm5.29-7.71c-.29-.14-1.71-.84-1.97-.93s-.46-.14-.66.14c-.19.29-.76.93-.93 1.12-.17.19-.34.22-.63.07-.29-.14-1.22-.45-2.32-1.43-.86-.76-1.43-1.71-1.6-2-.17-.29-.02-.45.12-.59.12-.12.29-.31.43-.46.14-.14.19-.24.29-.41.1-.19.05-.36-.02-.5-.07-.14-.66-1.59-.91-2.18-.24-.58-.49-.5-.66-.51h-.57c-.19 0-.5.07-.76.36-.26.29-1 1-1 2.43s1.02 2.82 1.16 3.01c.14.19 2 3.05 4.85 4.28.68.29 1.21.46 1.62.59.68.22 1.29.19 1.77.12.54-.08 1.71-.7 1.95-1.38.24-.67.24-1.24.17-1.38-.05-.14-.24-.22-.5-.34z" />
                </svg>
                55 9 9733-1395
            </a>
            <a href="https://wa.me/5555996058499" target="_blank"
                class="flex items-center px-3 py-1 text-sm font-medium rounded-md bg-green-600 text-white hover:bg-green-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 fill-current" viewBox="0 0 24 24">
                    <path
                        d="M20.52 3.48A11.93 11.93 0 0012 0C5.37 0 0 5.37 0 12c0 2.11.55 4.16 1.6 5.95L0 24l6.2-1.62A11.96 11.96 0 0012 24c6.63 0 12-5.37 12-12 0-3.19-1.24-6.2-3.48-8.52zM12 22a9.96 9.96 0 01-5.13-1.46l-.37-.22-3.68.96.99-3.58-.24-.37A9.96 9.96 0 012 12c0-5.52 4.48-10 10-10 2.67 0 5.18 1.04 7.07 2.93A9.96 9.96 0 0122 12c0 5.52-4.48 10-10 10zm5.29-7.71c-.29-.14-1.71-.84-1.97-.93s-.46-.14-.66.14c-.19.29-.76.93-.93 1.12-.17.19-.34.22-.63.07-.29-.14-1.22-.45-2.32-1.43-.86-.76-1.43-1.71-1.6-2-.17-.29-.02-.45.12-.59.12-.12.29-.31.43-.46.14-.14.19-.24.29-.41.1-.19.05-.36-.02-.5-.07-.14-.66-1.59-.91-2.18-.24-.58-.49-.5-.66-.51h-.57c-.19 0-.5.07-.76.36-.26.29-1 1-1 2.43s1.02 2.82 1.16 3.01c.14.19 2 3.05 4.85 4.28.68.29 1.21.46 1.62.59.68.22 1.29.19 1.77.12.54-.08 1.71-.7 1.95-1.38.24-.67.24-1.24.17-1.38-.05-.14-.24-.22-.5-.34z" />
                </svg>
                55 9 9605-8499
            </a>

            @if (auth()->check())
                <span class="text-sm">Bem-vindo, {{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 rounded-md text-white hover:bg-red-600 transition">
                        Sair
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
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
