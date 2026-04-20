<template>
    <div class="grid grid-cols-3 gap-6">

        <div v-for="oferta in ofertasRecientes" :key="oferta.id" class="bg-white rounded-2xl shadow-md overflow-hidden">

            <!-- IMAGEN -->
            <div class="relative">

                <img :src="getImagen(oferta.tipus_transport_id)" class="w-full h-65 select-none pointer-events-none"
                    draggable="false" />

                <span class="absolute top-2 right-2 bg-slate-800 text-white text-xs px-3 py-1 rounded-lg">
                    {{ oferta.valor }} €
                </span>
            </div>

            <!-- CONTENIDO -->
            <div class="p-4">

                <!-- RUTA -->
                <h3 class="font-semibold text-lg text-gray-800">
                    {{ getRuta(oferta) }}
                </h3>

                <!-- INFO -->
                <p class="text-sm text-gray-500 mt-1">
                    Cliente: {{ oferta.usuari?.nom || 'Sin cliente' }}
                </p>

                <p class="text-sm text-gray-500">
                    Peso: {{ oferta.pes_brut || '-' }} kg
                </p>

                <!-- BOTÓN -->
                <button @click="$emit('verDetalle', oferta)"
                    class="mt-4 w-full border border-orange-500 text-orange-500 rounded-lg py-2 hover:bg-orange-50">
                    Ver oferta
                </button>

            </div>

        </div>

    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    ofertas: Array
});

// función simple para ruta
const getRuta = (o) => {
    const origen = o.port_origen?.nom || o.aeroport_origen?.nom || 'Origen';
    const destino = o.port_desti?.nom || o.aeroport_desti?.nom || 'Destino';
    return `${origen} → ${destino}`;
};

const ofertasRecientes = computed(() =>
    props.ofertas?.slice().sort((a, b) => new Date(b.data_creacio) - new Date(a.data_creacio)).slice(0, 3)
);

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

</script>