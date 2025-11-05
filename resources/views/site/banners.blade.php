<!-- Carrossel de Banners -->
<section class="relative h-auto pt-32 md:pt-48 bg-green-900 bg-repeat-x bg-top flex items-center justify-center"
    style="background-image: url('{{ asset('img/madeira.png') }}'); background-size: auto 100%;">
    <div class="absolute inset-0 bg-black/50"></div>
    <div class="relative z-10 max-w-[930px] w-full mx-auto px-4">
        <div class="swiper-container rounded-2xl shadow-2xl overflow-hidden">
            <div class="swiper-wrapper">
                @foreach ($events as $event)
                    <div class="swiper-slide relative flex justify-center items-center bg-black">
                        <a href="{{ route('events.show', $event) }}" class="block w-full">
                            <img src="{{ asset("storage/{$event->banner}") }}" alt="Banner"
                                class="w-full h-[400px] md:h-[500px] object-cover object-center">
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
