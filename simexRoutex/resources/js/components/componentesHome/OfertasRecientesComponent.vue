<template>
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-50">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-lg font-semibold text-gray-800">
        Ofertas recientes
      </h2>
      <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded uppercase">
        {{ fecha }}
      </span>
    </div>

    <!-- LISTA -->
    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300">

      <div
        v-for="oferta in ofertas"
        :key="oferta.id"
        @click="$emit('verDetalle', oferta)"
        class="cursor-pointer hover:bg-orange-50 transition flex justify-between items-center bg-slate-50 p-4 rounded-xl border border-transparent hover:border-orange-200 hover:bg-white transition-all group"
      >
        <!-- INFO -->
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm">
            {{ Number(oferta.tipus_transport_id) === 1 ? '🚢' : '✈️' }}
          </div>

          <div>
            <p class="font-bold text-slate-800 text-sm">
              <!-- Lógica para mostrar Puerto o Aeropuerto según disponibilidad -->
              {{ getRuta(oferta) }}
            </p>
            <p class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">
              {{ oferta.concepto || 'OFR-000-00' }}
            </p>
          </div>
        </div>

        <!-- ESTADO -->
        <span
          class="px-3 py-1 text-[10px] rounded-full font-bold uppercase"
          :class="getEstadoClass(oferta.estats_ofertes?.estat)"
        >
          {{ oferta.estats_ofertes?.estat || 'Pendiente' }}
        </span>
      </div>

    </div>

  </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';

    const ofertas = ref([]);

    const fecha = new Date().toLocaleDateString();

    const obtenerOfertas = async () => {
    try {
        const res = await axios.get('/ofertas');
        ofertas.value = res.data;
        console.log("Ofertas cargadas:", ofertas.value);
    } catch (error) {
        console.error("Error cargando ofertas:", error);
    }
    };

    const getRuta = (o) => {
    const origen = o.port_origen?.nom || o.aeroport_origen?.nom || 'Origen';
    const destino = o.port_desti?.nom || o.aeroport_desti?.nom || 'Destino';
    return `${origen} → ${destino}`;
    };

    const getEstadoClass = (estat) => {
    const clases = {
        'Activa': 'bg-green-100 text-green-600',
        'Finalitzada': 'bg-blue-100 text-blue-600',
        'Cancel·lada': 'bg-red-100 text-red-600',
        'Pendent': 'bg-yellow-100 text-yellow-600'
    };
    return clases[estat] || 'bg-gray-100 text-gray-500';
    };

    onMounted(obtenerOfertas);
</script>

<style lang="scss" scoped>

</style>
