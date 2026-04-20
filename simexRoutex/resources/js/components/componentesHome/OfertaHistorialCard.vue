<template>
    <div class="bg-white p-4 rounded-xl shadow-sm flex justify-between items-center hover:shadow-md transition">

        <!-- IZQUIERDA -->
        <div class="flex items-center gap-3">

            <!-- ICONO ESTADO -->
            <div class="w-10 h-10 rounded-lg flex items-center justify-center">
                <img :src="estadoData.icon" class="w-6 h-6">
            </div>

            <!-- INFO -->
            <div>
                <p class="font-semibold text-gray-800">
                    {{ oferta.transportista?.nom || 'Sin transportista' }}
                    <!-- <pre>{{ oferta }}</pre> -->
                </p>

                <p class="text-sm text-gray-500">
                    {{ getRuta(oferta) }}
                </p>

                <!-- BADGE -->
                <span :class="estadoData.badge" class="text-xs px-2 py-1 rounded-full font-medium">
                    {{ estadoTexto }}
                </span>
            </div>
        </div>

        <div class="text-right">
            <p class="text-sm text-gray-500 flex gap-4 justify-end">
                <span>
                    <span class="font-semibold text-gray-700">PRESUPUESTO:</span>
                    {{ oferta.valor }} €
                </span>

                <span>
                    <span class="font-semibold text-gray-700">FECHA:</span>
                    {{ oferta.data_validessa_inicial }}
                </span>
            </p>
        </div>

    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    oferta: Object
});

// Estado limpio
const estadoTexto = computed(() => {
    return props.oferta.estats_ofertes?.estat || 'Pendent';
});

const estadoData = computed(() => {
    switch (estadoTexto.value) {
        case 'Aceptada':
            return {
                badge: 'bg-green-100 text-green-700',
                icon: '/icons/confirmado.png'
            };

        case 'Cancel·lada':
            return {
                badge: 'bg-red-100 text-red-700',
                icon: '/icons/cancelado.png'
            };

        case 'Pendent':
            return {
                badge: 'bg-yellow-100 text-yellow-700',
                icon: '/icons/pendiente.png'
            };

        case 'Transito':
            return {
                badge: 'bg-purple-100 text-purple-700',
                icon: '/icons/transito.png'
            };

        case 'Finalitzada':
            return {
                badge: 'bg-blue-100 text-blue-700',
                icon: '/icons/finalizado.png'
            };

        case 'Activa':
            return {
                badge: 'bg-green-50 text-green-600',
                icon: '/icons/activa.png'
            };

        default:
            return {
                badge: 'bg-gray-100 text-gray-600',
                icon: '/icons/default.png'
            };
    }
});

const getRuta = (o) => {
    const origen = o.port_origen?.nom || o.aeroport_origen?.nom || 'Origen';
    const destino = o.port_desti?.nom || o.aeroport_desti?.nom || 'Destino';
    return `${origen} → ${destino}`;
};

</script>