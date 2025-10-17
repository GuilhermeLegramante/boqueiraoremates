   <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
       <div class="w-full max-w-md rounded-2xl shadow-2xl border border-green-700 overflow-hidden">
           <!-- Cabeçalho -->
           <div class="bg-green-800 text-white px-6 py-4">
               <h3 class="text-xl font-bold">Confirmação de Lance</h3>
           </div>

           <!-- Corpo -->
           <div class="bg-white px-6 py-6 text-green-900">
               <div class="space-y-2 mb-4">
                   <p>Usuário: <span class="font-semibold">{{ Auth::user()->name }}</span></p>
                   <p>Evento: <span class="font-semibold">{{ $event->name }}</span></p>
                   <p>Animal: <span class="font-semibold">{{ $animal->name }}</span></p>
                   <p>Valor do Lance:
                       <span class="font-semibold" id="modalBidValue">
                           R$ {{ number_format($animal->next_bid, 2, ',', '.') }}
                       </span>
                   </p>
               </div>

               <p class="text-sm mb-6">
                   Ao confirmar, o lance será registrado e passará por validação pela mesa, conforme
                   regulamento do evento.
               </p>

               <div class="flex justify-end gap-4">
                   <button id="cancelBtn"
                       class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">Cancelar</button>
                   <button id="confirmBtn"
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 transition">Confirmar</button>
               </div>
           </div>
       </div>
   </div>

   <script>
       const bidInput = document.getElementById('bidInput');
       const form = document.getElementById('bidForm');
       const modal = document.getElementById('confirmModal');
       const cancelBtn = document.getElementById('cancelBtn');
       const confirmBtn = document.getElementById('confirmBtn');
       const bidError = document.getElementById('bidError');

       // Valor mínimo vindo do backend
       const minBid = {{ $animal->next_bid }};

       // Função para formatar valor em reais
       function formatBRL(value) {
           return value.toLocaleString('pt-BR', {
               minimumFractionDigits: 2,
               maximumFractionDigits: 2
           });
       }

       // Converte string pt-BR "1.234,56" para float 1234.56
       function parseBRL(value) {
           return parseFloat(value.replace(/\./g, '').replace(',', '.'));
       }

       // Formatar enquanto digita
       bidInput.addEventListener('input', () => {
           let value = bidInput.value.replace(/\D/g, '');

           if (!value) {
               bidInput.value = '';
               bidInput.classList.remove('border-red-600');
               bidError.classList.add('hidden');
               return;
           }

           value = (parseInt(value) / 100).toFixed(2);
           bidInput.value = formatBRL(parseFloat(value));

           // Verifica se é menor que o mínimo
           if (parseFloat(value) < minBid) {
               bidInput.classList.add('border-red-600');
               bidError.classList.remove('hidden');
           } else {
               bidInput.classList.remove('border-red-600');
               bidError.classList.add('hidden');
           }
       });

       // Intercepta submit e valida antes de abrir modal
       form.addEventListener('submit', (e) => {
           e.preventDefault();

           const numericValue = parseBRL(bidInput.value);

           if (!numericValue || numericValue < minBid) {
               bidInput.classList.add('border-red-600');
               bidError.classList.remove('hidden');
               return; // Não abre a modal
           }

           // Atualiza valor na modal
           document.getElementById('modalBidValue').innerText = 'R$ ' + bidInput.value;

           modal.classList.remove('hidden');
       });

       // Confirmar envio
       confirmBtn.addEventListener('click', () => {
           bidInput.value = parseBRL(bidInput.value); // Converte para float padrão antes de enviar
           form.submit();
       });

       // Cancelar modal
       cancelBtn.addEventListener('click', () => {
           modal.classList.add('hidden');
       });
   </script>
