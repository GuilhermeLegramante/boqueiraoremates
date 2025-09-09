 <!-- Carrossel de Banners -->
 <br><br><br><br>
 <section class="relative h-[500px] bg-green-900 bg-repeat-x bg-top flex items-center justify-center"
     style="background-image: url('{{ asset('img/madeira.png') }}'); background-size: auto 100%;">
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
