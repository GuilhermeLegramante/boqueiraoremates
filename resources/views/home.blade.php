<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boqueirão Remates</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/icon">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-v2QYF7y5rWq3JyZrR3fY+tRj+y0TmX1UyE1y7ZpXsK2eHqKZ7bZ5jXkOqjP3kTmFQHxgKXk1v1kMGG0zW0X/A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-[#fdfbf7] text-gray-800 font-sans" style="font-family: 'Poppins', sans-serif;">

    <!-- Header -->
    <header id="main-header" class="fixed top-0 w-full z-20 bg-transparent text-white transition-all duration-500">
        <div class="container mx-auto flex justify-between items-center py-4 px-6" id="header-content">

            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('img/logo_completa.png') }}" alt="Boqueirão Remates"
                    class="h-20 transition-all duration-500">
            </div>

            <!-- Menu + Ações -->
            <div class="flex items-center space-x-6">

                <!-- Menu -->
                <nav class="hidden md:flex space-x-6">
                    <a href="#" class="hover:text-[#e4c46f] transition">Início</a>
                    <a href="#proximos-remates" class="hover:text-[#e4c46f] transition">Próximos Eventos</a>
                    <a href="#quem-somos" class="hover:text-[#e4c46f] transition">Quem somos</a>
                    <a href="#equipe" class="hover:text-[#e4c46f] transition">Equipe</a>
                    <a href="#contato" class="hover:text-[#e4c46f] transition">Contato</a>
                </nav>

                <!-- WhatsApps -->
                <div class="hidden md:flex flex-col text-sm space-y-2">
                    <a href="https://wa.me/5555997331395" target="_blank"
                        class="flex items-center gap-2 bg-[#34D399] bg-opacity-90 text-white px-3 py-1.5 rounded-full shadow-md hover:bg-[#10B981] transition">
                        <!-- SVG WhatsApp -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M12 .5C5.648.5.5 5.648.5 12c0 2.092.547 4.078 1.586 5.844L.5 23.5l5.828-1.547A11.42 11.42 0 0 0 12 23.5c6.352 0 11.5-5.148 11.5-11.5S18.352.5 12 .5Zm0 20.875c-1.867 0-3.688-.5-5.27-1.449l-.375-.219-3.449.914.922-3.36-.242-.375A9.57 9.57 0 0 1 2.437 12c0-5.27 4.293-9.563 9.563-9.563S21.563 6.73 21.563 12 17.27 21.375 12 21.375Zm5.23-7.672c-.293-.148-1.73-.859-2-1-.266-.098-.461-.148-.66.148-.195.293-.754 1-.926 1.199-.172.195-.34.219-.633.074-.293-.148-1.23-.453-2.348-1.445-.867-.77-1.453-1.73-1.625-2.023-.172-.293-.02-.453.129-.602.133-.133.293-.348.441-.52.145-.172.193-.293.293-.488.098-.195.049-.367-.023-.516-.074-.148-.66-1.598-.906-2.191-.242-.586-.488-.508-.66-.516-.172-.008-.367-.01-.562-.01-.195 0-.52.074-.793.367-.273.293-1.043 1.02-1.043 2.484 0 1.465 1.07 2.883 1.219 3.086.148.195 2.105 3.223 5.105 4.52.713.309 1.27.492 1.707.629.715.227 1.363.195 1.875.117.574-.086 1.73-.707 1.977-1.395.242-.684.242-1.27.168-1.395-.07-.125-.266-.195-.559-.34Z" />
                        </svg>
                        (55) 9 9733-1395
                    </a>
                    <a href="https://wa.me/5555996058499" target="_blank"
                        class="flex items-center gap-2 bg-[#34D399] bg-opacity-90 text-white px-3 py-1.5 rounded-full shadow-md hover:bg-[#10B981] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M12 .5C5.648.5.5 5.648.5 12c0 2.092.547 4.078 1.586 5.844L.5 23.5l5.828-1.547A11.42 11.42 0 0 0 12 23.5c6.352 0 11.5-5.148 11.5-11.5S18.352.5 12 .5Zm0 20.875c-1.867 0-3.688-.5-5.27-1.449l-.375-.219-3.449.914.922-3.36-.242-.375A9.57 9.57 0 0 1 2.437 12c0-5.27 4.293-9.563 9.563-9.563S21.563 6.73 21.563 12 17.27 21.375 12 21.375Zm5.23-7.672c-.293-.148-1.73-.859-2-1-.266-.098-.461-.148-.66.148-.195.293-.754 1-.926 1.199-.172.195-.34.219-.633.074-.293-.148-1.23-.453-2.348-1.445-.867-.77-1.453-1.73-1.625-2.023-.172-.293-.02-.453.129-.602.133-.133.293-.348.441-.52.145-.172.193-.293.293-.488.098-.195.049-.367-.023-.516-.074-.148-.66-1.598-.906-2.191-.242-.586-.488-.508-.66-.516-.172-.008-.367-.01-.562-.01-.195 0-.52.074-.793.367-.273.293-1.043 1.02-1.043 2.484 0 1.465 1.07 2.883 1.219 3.086.148.195 2.105 3.223 5.105 4.52.713.309 1.27.492 1.707.629.715.227 1.363.195 1.875.117.574-.086 1.73-.707 1.977-1.395.242-.684.242-1.27.168-1.395-.07-.125-.266-.195-.559-.34Z" />
                        </svg>
                        (55) 9 9605-8499
                    </a>
                </div>

                <!-- Botões -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('filament.admin.auth.login') }}"
                        class="bg-[#d1b85f] hover:bg-[#e4c46f] text-[#064e3b] font-semibold px-4 py-2 rounded-lg transition">
                        Login
                    </a>
                    <a href="{{ route('filament.admin.auth.login') }}"
                        class="bg-[#064e3b] hover:bg-[#053d30] border border-[#d1b85f] text-white font-semibold px-4 py-2 rounded-lg transition">
                        Cadastre-se
                    </a>
                </div>

                <!-- Logo secundária -->
                <img src="{{ asset('img/logo_rodrigo.png') }}" alt="Rodrigo" class="h-20 transition-all duration-500">
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="relative h-[600px] bg-cover bg-center flex items-center justify-center"
        style="background-image: url('{{ asset('img/animais.png') }}');">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative z-10 max-w-[930px] w-full mx-auto px-4">
            <div class="swiper-container rounded-2xl shadow-2xl overflow-hidden">
                <div class="swiper-wrapper">
                    <div class="swiper-slide relative flex justify-center">
                        <img src="{{ asset('img/banner_1.jpg') }}" alt="Remate 1"
                            class="max-w-full max-h-[400px] object-contain">
                    </div>
                    <div class="swiper-slide relative flex justify-center">
                        <img src="{{ asset('img/banner_2.jpg') }}" alt="Remate 2"
                            class="max-w-full max-h-[400px] object-contain">
                    </div>
                    <div class="swiper-slide relative flex justify-center">
                        <img src="{{ asset('img/banner_3.png') }}" alt="Remate 3"
                            class="max-w-full max-h-[400px] object-contain">
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Próximos Remates -->
    <section id="proximos-remates" class="py-20 text-white"
        style="background: linear-gradient(to bottom, #064e3b 0%, #2f7a5e 100%);">
        <div class="max-w-6xl mx-auto text-center px-6">
            <h2 class="text-4xl font-bold mb-12 text-white">Próximos Eventos</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">
                <div class="bg-[#065f46] rounded-2xl shadow-lg overflow-hidden hover:scale-105 transition">
                    <img src="{{ asset('img/remate_1.png') }}" alt="Remate de Gado" class="w-full h-52 object-cover">
                    <div class="p-6 text-left">
                        <h3 class="text-xl font-bold mb-2">1º Leilão da Página BOTA e VIBRA</h3>
                        <span
                            class="inline-block bg-[#d1b85f] text-[#064e3b] font-semibold px-3 py-1 rounded-full mb-2">
                            30/08/2025 - 20h
                        </span>
                        <br>
                        <a href="#" class="text-[#d1b85f] hover:text-[#e4c46f] hover:underline">Ver detalhes</a>
                    </div>
                </div>

                <div class="bg-[#065f46] rounded-2xl shadow-lg overflow-hidden hover:scale-105 transition">
                    <img src="{{ asset('img/remate_2.png') }}" alt="Remate de Ovinos" class="w-full h-52 object-cover">
                    <div class="p-6 text-left">
                        <h3 class="text-xl font-bold mb-2">2º Leilão Cabanha COSTA VERDE MAR</h3>
                        <span
                            class="inline-block bg-[#d1b85f] text-[#064e3b] font-semibold px-3 py-1 rounded-full mb-2">
                            06/09/2025 - 20h30
                        </span>
                        <br>
                        <a href="#" class="text-[#d1b85f] hover:text-[#e4c46f] hover:underline">Ver detalhes</a>
                    </div>
                </div>

                <div class="bg-[#065f46] rounded-2xl shadow-lg overflow-hidden hover:scale-105 transition">
                    <img src="{{ asset('img/remate_3.png') }}" alt="Remate Especial de Touros"
                        class="w-full h-52 object-cover">
                    <div class="p-6 text-left">
                        <h3 class="text-xl font-bold mb-2">5º Leilão Virtual CRIOULOS DAS COXILHAS</h3>
                        <span
                            class="inline-block bg-[#d1b85f] text-[#064e3b] font-semibold px-3 py-1 rounded-full mb-2">
                            09/09/2025 - 20h
                        </span>
                        <br>
                        <a href="#" class="text-[#d1b85f] hover:text-[#e4c46f] hover:underline">Ver detalhes</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quem Somos -->
    <section id="quem-somos" class="py-20 bg-[#f0f5f1]">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-4xl font-bold text-[#064e3b] mb-12 text-center">Quem Somos</h2>

            <!-- Primeiro parágrafo com imagem à esquerda -->
            <div class="flex flex-col md:flex-row items-center mb-12 gap-8">
                <img src="{{ asset('img/martelo.jpg') }}" alt="Nossa Equipe"
                    class="w-full md:w-1/3 rounded-2xl shadow-lg">
                <p class="text-lg text-gray-700 md:w-2/3">
                    Há anos no mercado, a <span class="font-semibold text-[#064e3b]">Boqueirão Remates</span> se
                    consolidou como referência
                    em leilões e negociações agropecuárias, sempre pautada pela
                    <span class="font-semibold">seriedade, transparência e compromisso</span>
                    com nossos clientes. Atuamos com <span class="font-semibold">profissionalismo e dedicação</span>,
                    garantindo qualidade em cada remate e fortalecendo a confiança de produtores e compradores.
                </p>
            </div>

            <!-- Segundo parágrafo com imagem à direita -->
            <div class="flex flex-col md:flex-row items-center gap-8">
                <p class="text-lg text-gray-700 md:w-2/3 order-2 md:order-1">
                    Nossa missão é promover negócios justos e seguros, valorizando o campo e seus profissionais,
                    sempre mantendo um atendimento humanizado e soluções eficientes. Com anos de experiência,
                    buscamos inovar e oferecer excelência em todos os processos, consolidando parcerias duradouras
                    e contribuindo para o crescimento do setor agropecuário.
                </p>
                <img src="{{ asset('img/animais.png') }}" alt="Negócios Agropecuários"
                    class="w-full md:w-1/3 rounded-2xl shadow-lg order-1 md:order-2">
            </div>
        </div>
    </section>


    <!-- Equipe -->
    <section id="equipe" class="max-w-6xl mx-auto mt-20 px-4 mb-16">
        <h2 class="text-4xl font-bold mb-10 text-[#064e3b] text-center">Nossa Equipe</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-10">
            <div class="text-center">
                <img src="{{ asset('img/ze.png') }}"
                    class="w-32 h-32 object-cover rounded-full mx-auto border-4 border-[#065f46] shadow-lg hover:border-[#d1b85f] transition">
                <h4 class="mt-4 font-semibold">Emerson Hoisler da Rosa</h4>
                <p class="text-gray-500">Leiloeiro</p>
            </div>
            <div class="text-center">
                <img src="{{ asset('img/edson.png') }}"
                    class="w-32 h-32 object-cover rounded-full mx-auto border-4 border-[#065f46] shadow-lg hover:border-[#d1b85f] transition">
                <h4 class="mt-4 font-semibold">Edson Vargas</h4>
                <p class="text-gray-500">Administrativo</p>
            </div>
            <div class="text-center">
                <img src="{{ asset('img/rodrigo.png') }}"
                    class="w-32 h-32 object-cover rounded-full mx-auto border-4 border-[#065f46] shadow-lg hover:border-[#d1b85f] transition">
                <h4 class="mt-4 font-semibold">Rodrigo Bauer</h4>
                <p class="text-gray-500">Leiloeiro</p>
            </div>
            <div class="text-center">
                <img src="{{ asset('img/gilmar.png') }}"
                    class="w-32 h-32 object-cover rounded-full mx-auto border-4 border-[#065f46] shadow-lg hover:border-[#d1b85f] transition">
                <h4 class="mt-4 font-semibold">Gilmar Barbosa</h4>
                <p class="text-gray-500">Leiloeiro</p>
            </div>
            <div class="text-center">
                <img src="{{ asset('img/ale.png') }}"
                    class="w-32 h-32 object-cover rounded-full mx-auto border-4 border-[#065f46] shadow-lg hover:border-[#d1b85f] transition">
                <h4 class="mt-4 font-semibold">Alessandra Roggia</h4>
                <p class="text-gray-500">Financeiro</p>
            </div>
            <div class="text-center">
                <img src="{{ asset('img/kaue.png') }}"
                    class="w-32 h-32 object-cover rounded-full mx-auto border-4 border-[#065f46] shadow-lg hover:border-[#d1b85f] transition">
                <h4 class="mt-4 font-semibold">Kaue Cariolato</h4>
                <p class="text-gray-500">Administrativo</p>
            </div>
            <div class="text-center">
                <img src="{{ asset('img/marlin.png') }}"
                    class="w-32 h-32 object-cover rounded-full mx-auto border-4 border-[#065f46] shadow-lg hover:border-[#d1b85f] transition">
                <h4 class="mt-4 font-semibold">Marlyn Lima</h4>
                <p class="text-gray-500">Administrativo</p>
            </div>
        </div>
    </section>

    <!-- Contato -->
    <section id="contato" class="py-20 bg-[#f0f5f1]">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-[#064e3b] mb-8">Fale Conosco</h2>
            <p class="text-gray-700 mb-12">
                Entre em contato conosco para esclarecer dúvidas, solicitar informações ou agendar um atendimento.
                Responderemos o mais rápido possível!
            </p>

            <form action="#" method="POST" class="bg-white rounded-2xl shadow-lg p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nome" class="block text-gray-700 font-medium mb-2">Nome</label>
                        <input type="text" id="nome" name="nome" required
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#064e3b] focus:outline-none transition">
                    </div>
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">E-mail</label>
                        <input type="email" id="email" name="email" required
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#064e3b] focus:outline-none transition">
                    </div>
                </div>
                <div>
                    <label for="assunto" class="block text-gray-700 font-medium mb-2">Assunto</label>
                    <input type="text" id="assunto" name="assunto" required
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#064e3b] focus:outline-none transition">
                </div>
                <div>
                    <label for="mensagem" class="block text-gray-700 font-medium mb-2">Mensagem</label>
                    <textarea id="mensagem" name="mensagem" rows="5" required
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#064e3b] focus:outline-none transition"></textarea>
                </div>
                <button type="submit"
                    class="bg-[#064e3b] hover:bg-[#053d30] text-white font-semibold py-3 px-6 rounded-lg shadow transition">
                    Enviar Mensagem
                </button>
            </form>
        </div>
    </section>

    <!-- Rodapé -->
    <footer class="bg-[#064e3b] text-white py-12">
        <div class="container mx-auto text-center space-y-4">
            <p>&copy; {{ date('Y') }} Boqueirão Remates. Todos os direitos reservados.</p>
            <div class="flex justify-center space-x-6 text-2xl">
                <!-- Facebook -->
                <a href="https://www.facebook.com" target="_blank" class="hover:text-[#e4c46f] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.406.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.797.143v3.24l-1.918.001c-1.504 0-1.796.715-1.796 1.764v2.313h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.676V1.325C24 .593 23.406 0 22.675 0z" />
                    </svg>
                </a>
                <!-- Instagram -->
                <a href="https://www.instagram.com" target="_blank" class="hover:text-[#e4c46f] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.849.07 1.366.062 2.633.335 3.608 1.31.975.975 1.247 2.242 1.31 3.608.058 1.265.07 1.645.07 4.849s-.012 3.584-.07 4.849c-.062 1.366-.335 2.633-1.31 3.608-.975.975-2.242 1.247-3.608 1.31-1.265.058-1.645.07-4.849.07s-3.584-.012-4.849-.07c-1.366-.062-2.633-.335-3.608-1.31-.975-.975-1.247-2.242-1.31-3.608C2.175 15.747 2.163 15.367 2.163 12s.012-3.584.07-4.849c.062-1.366.335-2.633 1.31-3.608C4.518 2.568 5.785 2.296 7.151 2.234 8.416 2.175 8.796 2.163 12 2.163zm0-2.163C8.736 0 8.332.013 7.052.072 5.78.131 4.606.401 3.633 1.374c-.973.973-1.243 2.147-1.302 3.419C2.013 6.668 2 7.072 2 12c0 4.928.013 5.332.072 6.613.059 1.272.329 2.446 1.302 3.419.973.973 2.147 1.243 3.419 1.302C8.668 23.987 9.072 24 12 24s3.332-.013 4.613-.072c1.272-.059 2.446-.329 3.419-1.302.973-.973 1.243-2.147 1.302-3.419.059-1.281.072-1.685.072-6.613s-.013-5.332-.072-6.613c-.059-1.272-.329-2.446-1.302-3.419-.973-.973-2.147-1.243-3.419-1.302C15.332.013 14.928 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zm0 10.162a3.999 3.999 0 1 1 0-7.998 3.999 3.999 0 0 1 0 7.998zm6.406-11.845a1.44 1.44 0 1 1-2.88 0 1.44 1.44 0 0 1 2.88 0z" />
                    </svg>
                </a>
            </div>
        </div>
    </footer>


    <!-- WhatsApp -->
    <a href="https://wa.me/5555999181805?text=Ol%C3%A1!%20Gostaria%20de%20saber%20mais%20sobre%20a%20Nota%20Premiada%20Cacequi."
        target="_blank"
        class="fixed bottom-4 right-4 bg-[#047857] hover:bg-[#065f46] text-white px-4 py-3 rounded-full shadow-lg flex items-center gap-2 z-50 transition-all"
        aria-label="Fale conosco no WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M20.52 3.48A11.8 11.8 0 0 0 3.48 20.52l-1.2 4.32 4.44-1.17A11.8 11.8 0 0 0 20.52 3.48ZM12 21a8.94 8.94 0 0 1-4.46-1.19L6.1 20.9l.74-2.7A8.94 8.94 0 1 1 21 12c0 4.95-4.05 9-9 9Zm3.87-6.27-1.2-.63a6.12 6.12 0 0 1-2.52-2.52l-.63-1.2a.8.8 0 0 0-1.14-.32l-1.5.75a.8.8 0 0 0-.3 1.14 9.25 9.25 0 0 0 4.2 4.2.8.8 0 0 0 1.14-.3l.75-1.5a.8.8 0 0 0-.3-1.14Z" />
        </svg>
        WhatsApp
    </a>

    <!-- Aviso de Cookies -->
    <div id="cookie-consent"
        class="fixed bottom-0 left-0 right-0 bg-[#064e3b] text-white p-4 flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 z-50 shadow-lg">
        <p class="text-sm max-w-xl">
            Usamos cookies para melhorar sua experiência no site. Ao continuar navegando, você concorda com nossa
            <a href="#" class="underline text-[#d1b85f] hover:text-[#e4c46f]" target="_blank">Política de
                Privacidade</a>.
        </p>
        <button id="accept-cookies"
            class="bg-[#d1b85f] hover:bg-[#e4c46f] text-[#064e3b] font-semibold py-2 px-4 rounded shadow transition">
            Aceitar
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const consentKey = 'cookieConsentGiven';
            const consentBanner = document.getElementById('cookie-consent');
            const acceptBtn = document.getElementById('accept-cookies');

            if (localStorage.getItem(consentKey) === 'true') {
                consentBanner.style.display = 'none';
            }

            acceptBtn.addEventListener('click', function() {
                localStorage.setItem(consentKey, 'true');
                consentBanner.style.display = 'none';
            });
        });

        const header = document.getElementById('main-header');
        const logos = header.querySelectorAll('img');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.remove('bg-transparent');
                header.classList.add('bg-[#064e3b]');
                header.style.padding = '0.5rem 1.5rem';
                logos.forEach(logo => logo.style.height = '3rem');
            } else {
                header.classList.remove('bg-[#064e3b]');
                header.classList.add('bg-transparent');
                header.style.padding = '1rem 1.5rem';
                logos.forEach(logo => logo.style.height = '5rem');
            }
        });
    </script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
            loop: true,
            autoplay: {
                delay: 4000
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
        });
    </script>

</body>

</html>
