  <!-- Contato -->
  <section id="contato" class="py-20 bg-[#f0f5f1]">
      <div class="max-w-4xl mx-auto px-6 text-center">
          <h2 class="text-4xl font-bold text-[#003333] mb-8">Fale Conosco</h2>
          <p class="text-gray-700 mb-12">
              Entre em contato conosco para esclarecer dúvidas, solicitar informações ou agendar um atendimento.
              Responderemos o mais rápido possível!
          </p>

          <form action="#" method="POST" class="bg-white rounded-2xl shadow-lg p-8 space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                      <label for="nome" class="block text-gray-700 font-medium mb-2">Nome</label>
                      <input type="text" id="nome" name="nome" required
                          class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#003333] focus:outline-none transition">
                  </div>
                  <div>
                      <label for="email" class="block text-gray-700 font-medium mb-2">E-mail</label>
                      <input type="email" id="email" name="email" required
                          class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#003333] focus:outline-none transition">
                  </div>
              </div>
              <div>
                  <label for="assunto" class="block text-gray-700 font-medium mb-2">Assunto</label>
                  <input type="text" id="assunto" name="assunto" required
                      class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#003333] focus:outline-none transition">
              </div>
              <div>
                  <label for="mensagem" class="block text-gray-700 font-medium mb-2">Mensagem</label>
                  <textarea id="mensagem" name="mensagem" rows="5" required
                      class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#003333] focus:outline-none transition"></textarea>
              </div>
              <button type="submit"
                  class="bg-[#003333] hover:bg-[#053d30] text-white font-semibold py-3 px-6 rounded-lg shadow transition">
                  Enviar Mensagem
              </button>
          </form>
      </div>
  </section>
