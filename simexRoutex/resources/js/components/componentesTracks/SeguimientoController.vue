<template>
    <div class="p-8 bg-gray-50 min-h-screen flex gap-6">

    <!-- LISTA DE OFERTAS ACTIVAS -->
    <div class="flex-1 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="p-6 border-b border-gray-50 bg-gray-50/30">
        <h2 class="text-xl font-bold text-slate-800 font-mono italic">CONTROL DE TRÁFICO</h2>
      </div>
      <table class="w-full text-left">
        <thead class="bg-gray-50 text-[10px] uppercase font-bold text-gray-400 tracking-widest">
          <tr>
            <th class="px-6 py-4">Oferta ID</th>
            <th class="px-6 py-4">Cliente</th>
            <th class="px-6 py-4">Ruta</th>
            <th class="px-6 py-4">Estado</th>
            <th class="px-6 py-4">Acción</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="o in ofertas" :key="o.id" class="hover:bg-orange-50/30 transition-colors">
            <td class="px-6 py-4 font-bold text-slate-700">#{{ o.id }}</td>
            <td class="px-6 py-4 text-sm">{{ o.usuari?.nom }}</td>
            <td class="px-6 py-4 text-xs text-gray-500">
               {{ o.port_origen?.nom || 'Origen' }} ➔ {{ o.port_desti?.nom || 'Destino' }}
            </td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-[10px] font-bold uppercase">
                {{ o.estats_ofertes?.estat }}
              </span>
            </td>
            <td class="px-6 py-4">
              <button @click="verPasos(o)" class="text-orange-500 font-bold text-sm hover:underline">Gestionar Pasos</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- PANEL LATERAL DE PASOS (ORQUESTADOR) -->
    <div v-if="ofertaSeleccionada" class="w-96 bg-white rounded-3xl shadow-2xl border border-orange-100 p-6 animate-in slide-in-from-right duration-300">
      <div class="flex justify-between items-center mb-8">
        <h3 class="font-bold text-slate-800">Seguimiento #{{ ofertaSeleccionada.id }}</h3>
        <button @click="ofertaSeleccionada = null" class="text-gray-400 text-2xl">&times;</button>
      </div>

      <!-- LINEA DE TIEMPO (STEPPER) -->
      <div class="relative space-y-8 before:absolute before:inset-0 before:ml-4 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-orange-500 before:via-gray-200 before:to-gray-100">
        <div v-for="p in pasos" :key="p.id" class="relative flex items-center gap-4">
          <!-- Checkbox Naranja -->
          <div @click="togglePaso(p)"
               :class="p.esta_completado == 1 ? 'bg-orange-500 border-orange-500 shadow-orange-200' : 'bg-white border-gray-300'"
               class="z-10 flex items-center justify-center w-8 h-8 rounded-full border-2 cursor-pointer transition-all shadow-lg">
            <span v-if="p.esta_completado" class="text-white text-xs">✓</span>
          </div>

          <div class="flex flex-col">
            <span :class="p.esta_completado == 1 ? 'text-slate-800 font-bold' : 'text-gray-400'" class="text-sm transition-colors">
              {{ p.step?.nom }}
            </span>
            <span v-if="p.fecha_completado" class="text-[9px] text-orange-400 font-mono">
              {{ new Date(p.fecha_completado).toLocaleString() }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';

    const ofertas = ref([]);
    const ofertaSeleccionada = ref(null);
    const pasos = ref([]);

    const cargarOfertasActivas = async () => {
    const res = await axios.get('trafico');
    ofertas.value = res.data;
    };

    const verPasos = async (oferta) => {
    ofertaSeleccionada.value = oferta;
    const res = await axios.get(`trafico/${oferta.id}/pasos`);
    pasos.value = res.data;
    };

    const togglePaso = async (paso) => {
    // Cambiamos el estado localmente para respuesta instantánea
    paso.esta_completado = !paso.esta_completado;
    try {
        await axios.post(`trafico/check/${paso.id}`, { completado: paso.esta_completado });
        // Recargamos para traer la fecha_completado del servidor
        const res = await axios.get(`trafico/${ofertaSeleccionada.value.id}/pasos`);
        pasos.value = res.data;
    } catch (e) {
        paso.esta_completado = !paso.esta_completado; // Revertir si falla
    }
    };

    onMounted(cargarOfertasActivas);
</script>

<style lang="scss" scoped>

</style>
