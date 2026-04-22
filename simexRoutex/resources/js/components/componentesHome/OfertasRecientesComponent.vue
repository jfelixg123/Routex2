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
    <div class="space-y-4 max-h-[430px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300">
        <div
            v-for="oferta in ofertasFiltradas"
            :key="oferta.id"
            @click="$emit('verDetalle', oferta)"
            class="cursor-pointer bg-slate-50 rounded-xl border border-transparent hover:border-orange-200 hover:bg-white transition-all group overflow-hidden flex flex-col"
        >
            <div class="h-32 w-full overflow-hidden">
                <img
                    :src="getImagen(oferta.tipus_transport_id)"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                />
            </div>

            <!-- INFO INFERIOR -->
            <div class="p-4 flex justify-between items-center border-t border-gray-100">
            <div class="flex items-center gap-3">
                <!-- El circulito con emoji lo podemos mantener pequeño o quitarlo si ya tienes la imagen -->
                <div class="text-lg">
                {{ { 1: '🚢', 2: '✈️', 3: '🚚', 4: '📦', 5: '🚂' }[Number(oferta.tipus_transport_id)] }}
                </div>

                <div>
                <p class="font-bold text-slate-800 text-sm">
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

  </div>
</template>

<script setup>
    import { ref, onMounted, computed  } from 'vue';
    import axios from 'axios';

    const props = defineProps({
        filtro: String
    });

    const ofertas = ref([]);

    const ofertasFiltradas = computed(() => {
        if (!props.filtro) return ofertas.value;
        const f = props.filtro.toLowerCase();
        return ofertas.value.filter(o => {
            const ruta = getRuta(o).toLowerCase();
            const concepto = (o.concepto || '').toLowerCase();
            return ruta.includes(f) || concepto.includes(f);
        });
    });

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

    const getImagen = (tipo) => {
        const iconos = {
            1: 'icons/barco.jpg',
            2: 'icons/avion.jpg',
            3: 'icons/camion.jpeg',
            4: 'icons/paquete.jpg',
            5: 'icons/tren.jpeg'
        };

        return iconos[Number(tipo)] || '/icons/default.png';
    };

    onMounted(obtenerOfertas);
</script>

<style lang="scss" scoped>

</style>
