<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Boqueirão Remates')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/icon">

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-v2QYF7y5rWq3JyZrR3fY+tRj+y0TmX1UyE1y7ZpXsK2eHqKZ7bZ5jXkOqjP3kTmFQHxgKXk1v1kMGG0zW0X/A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @stack('styles') {{-- para estilos extras em páginas específicas --}}
</head>

<body class="bg-[#fdfbf7] text-gray-800 font-sans" style="font-family: 'Poppins', sans-serif;">

    {{-- Header fixo em todas as páginas --}}
    @include('site.header')

    {{-- Aqui cada página injeta o conteúdo --}}
    @yield('content')

    {{-- Footer fixo em todas as páginas --}}
    @include('site.footer')

    @include('site.extras')

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

    @stack('scripts') {{-- para scripts extras em páginas específicas --}}
</body>

</html>
