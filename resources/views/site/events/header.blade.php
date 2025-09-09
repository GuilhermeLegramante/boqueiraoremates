    <section class="bg-[#003333] text-white py-12 px-6">
        <div class="container mx-auto grid grid-cols-1 lg:grid-cols-3 gap-10 items-center">

            <!-- Banner -->
            <div class="flex justify-center lg:justify-start">
                <img src="{{ asset('storage/' . $event->banner) }}" alt="Banner do evento"
                    class="w-72 h-56 object-cover rounded-2xl shadow-xl border-4 border-white/20">
            </div>

            <!-- Informações principais -->
            <div class="space-y-3 text-center lg:text-left">
                <h2 class="text-3xl font-extrabold tracking-wide">{{ $event->name }}</h2>
                <p class="text-lg font-medium text-yellow-300">
                    {{ $event->start_date->format('d/m/Y H:i') }}
                </p>
                <div class="bg-white/10 rounded-xl p-3 text-sm">
                    <p>
                        Pré-lance online: <br>
                        <span class="font-semibold text-yellow-200">
                            {{ \Carbon\Carbon::parse($event->pre_start_date)->format('d/m/Y H:i') }}
                        </span>
                        até
                        <span class="font-semibold text-yellow-200">
                            {{ \Carbon\Carbon::parse($event->pre_finish_date)->format('d/m/Y H:i') }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Observações -->
            <div class="bg-white/10 rounded-xl p-5 text-sm md:text-base leading-relaxed shadow-md">
                <p>{{ $event->note }}</p>
            </div>
        </div>
    </section>
