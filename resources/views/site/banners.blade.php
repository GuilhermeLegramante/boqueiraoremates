<!-- Carrossel de Banners -->
<section class="relative h-[500px] pt-32 md:pt-48 bg-green-900 bg-repeat-x bg-top flex items-center justify-center"
    style="background-image: url('{{ asset('img/madeira.png') }}'); background-size: auto 100%;">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative z-10 max-w-[930px] w-full mx-auto px-4">
        <div class="swiper-container rounded-2xl shadow-2xl overflow-hidden">
            <div class="swiper-wrapper">
                @foreach ($events as $event)
                    <div class="swiper-slide flex justify-center items-center bg-black/10">
                        <a href="{{ route('events.show', $event) }}" class="block w-full h-[340px] relative">
                            <img src="{{ asset("storage/{$event->banner}") }}" alt="Banner"
                                class="absolute inset-0 w-full h-full object-contain rounded-2xl" />
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
