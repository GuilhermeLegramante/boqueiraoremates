    <!-- Rodapé -->
    <footer class="bg-[#003333] py-12 bg-[url('{{ asset('img/fundofooter.png') }}')] bg-cover bg-center">
        <div class="container mx-auto flex flex-col md:flex-row items-center md:items-start justify-between gap-10">

            <!-- Coluna da esquerda (textos, contatos e redes) -->
            <div class="flex flex-col items-start space-y-4 bg-white/80 p-4 rounded-lg shadow text-black max-w-sm">
                <!-- Contatos -->
                <div class="flex flex-col space-y-3 text-base">
                    <!-- E-mail -->
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 13.065L.015 6h23.97L12 13.065zm0 2.135L24 7v11H0V7l12 8.2z" />
                        </svg>
                        <span>emersonhoisler@gmail.com</span>
                    </div>

                    <!-- Localização -->
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5
                                 2.5 2.5 0 0 1 0 5z" />
                        </svg>
                        <span>Santiago - RS</span>
                    </div>
                </div>

                <!-- Redes sociais -->
                <div class="flex space-x-6 text-2xl">
                    <!-- Facebook -->
                    <a href="https://www.facebook.com" target="_blank" class="hover:text-[#e4c46f] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.675 0H1.325C.593 0 0 .593 0
                            1.325v21.351C0 23.406.593
                            24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1
                            1.893-4.788 4.659-4.788 1.325 0 2.464.099
                            2.797.143v3.24l-1.918.001c-1.504
                            0-1.796.715-1.796 1.764v2.313h3.587l-.467
                            3.622h-3.12V24h6.116C23.406 24 24
                            23.406 24 22.676V1.325C24 .593 23.406
                            0 22.675 0z" />
                        </svg>
                    </a>

                    <!-- Instagram -->
                    <a href="https://www.instagram.com" target="_blank" class="hover:text-[#e4c46f] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012
                            4.849.07 1.366.062 2.633.335 3.608
                            1.31.975.975 1.247 2.242 1.31
                            3.608.058 1.265.07 1.645.07
                            4.849s-.012 3.584-.07
                            4.849c-.062 1.366-.335 2.633-1.31
                            3.608-.975.975-2.242 1.247-3.608
                            1.31-1.265.058-1.645.07-4.849.07s-3.584-.012-4.849-.07c-1.366-.062-2.633-.335-3.608-1.31-.975-.975-1.247-2.242-1.31-3.608C2.175
                            15.747 2.163 15.367 2.163 12s.012-3.584.07-4.849c.062-1.366.335-2.633
                            1.31-3.608C4.518 2.568 5.785 2.296
                            7.151 2.234 8.416 2.175 8.796
                            2.163 12 2.163zm0-2.163C8.736 0
                            8.332.013 7.052.072 5.78.131
                            4.606.401 3.633 1.374c-.973.973-1.243
                            2.147-1.302 3.419C2.013 6.668 2
                            7.072 2 12c0 4.928.013 5.332.072
                            6.613.059 1.272.329 2.446 1.302
                            3.419.973.973 2.147 1.243 3.419
                            1.302C8.668 23.987 9.072 24 12
                            24s3.332-.013 4.613-.072c1.272-.059
                            2.446-.329 3.419-1.302.973-.973
                            1.243-2.147 1.302-3.419.059-1.281.072-1.685.072-6.613s-.013-5.332-.072-6.613c-.059-1.272-.329-2.446-1.302-3.419-.973-.973-2.147-1.243-3.419-1.302C15.332.013 14.928 0 12
                            0zm0 5.838a6.162 6.162 0 1 0
                            0 12.324 6.162 6.162 0 0 0
                            0-12.324zm0 10.162a3.999 3.999 0
                            1 1 0-7.998 3.999 3.999 0 0 1
                            0 7.998zm6.406-11.845a1.44 1.44 0
                            1 1-2.88 0 1.44 1.44 0 0 1
                            2.88 0z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Logo centralizada -->
            <div class="flex flex-col items-center justify-center text-center">
                <img src="{{ asset('img/logobr.png') }}" alt="Logo Boqueirão Remates" class="w-72 h-auto">

                <!-- Texto abaixo da logo -->
                <p class="font-semibold text-black mt-6 bg-white/80 px-4 py-2 rounded-lg shadow">
                    &copy; {{ date('Y') }} Boqueirão Remates. Todos os direitos reservados.
                </p>
            </div>
        </div>
    </footer>
