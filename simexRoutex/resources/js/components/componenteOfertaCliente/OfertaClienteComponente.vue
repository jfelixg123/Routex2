<template>
    <div class="p-6 bg-gray-100 min-h-screen">

        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Envío #{{ oferta?.id }}
            </h1>

            <p class="text-gray-500">
                Referencia: {{ oferta?.concepto || 'Sin referencia' }}
            </p>
        </div>

        <!-- CONTENIDO -->
        <div class="grid grid-cols-3 gap-6">

            <!-- MAPA -->
            <div class="col-span-2">
                <MapboxOfertaClienteComponente />
            </div>

            <!-- DETALLES -->
            <div class="space-y-4">

                <div class="bg-white p-4 rounded-xl shadow">
                    <h2 class="font-semibold mb-3">📦 Detalle</h2>

                    <p><strong>Tipo transporte:</strong> {{ oferta?.tipus_transport?.tipus || '-' }}</p>
                    <p><strong>Tipo carga:</strong> {{ oferta?.tipus_carrega?.tipus || '-' }}</p>
                    <p><strong>Flujo:</strong> {{ oferta?.tipus_fluxe?.tipus || '-' }}</p>

                    <p><strong>Peso:</strong> {{ oferta?.pes_brut || '-' }} kg</p>
                    <p><strong>Volumen:</strong> {{ oferta?.volum || '-' }}</p>

                    <p><strong>Origen:</strong> {{ oferta?.port_origen?.nom || 'No asignado' }}</p>
                    <p><strong>Destino:</strong> {{ oferta?.port_desti?.nom || 'No asignado' }}</p>

                    <p><strong>Cliente:</strong> {{ oferta?.usuari?.nom || '-' }}</p>
                    <p><strong>Comercial:</strong> {{ oferta?.agent_comercial?.nom || '-' }}</p>
                </div>

                <div class="bg-white p-4 rounded-xl shadow">
                    <h2 class="font-semibold mb-2">🚚 Estado</h2>

                    <span class="bg-orange-500 text-white px-3 py-1 rounded-lg text-sm">
                        {{ oferta?.estats_ofertes?.estat || 'Sin estado' }}
                    </span>
                </div>

            </div>
        </div>

        <!-- TIMELINE -->
        <!-- TIMELINE -->
        <div class="mt-6 bg-white p-4 rounded-xl shadow">
            <h2 class="font-semibold mb-4">📍 Seguimiento</h2>

            <!-- LOADING -->
            <SpinnerComponent v-if="estaCargando" />

            <!-- DATOS -->
            <div v-else-if="tracking?.seguimientos?.length" class="space-y-4">

                <div v-for="(seg, index) in tracking.seguimientos" :key="index" class="flex items-start gap-3">

                    <!-- punto -->
                    <div :class="seg.esta_completado == 1 ? 'bg-green-500' : 'bg-gray-300'"
                        class="w-3 h-3 rounded-full mt-2">
                    </div>

                    <!-- texto -->
                    <div>
                        <p :class="seg.esta_completado ? 'font-semibold' : 'text-gray-400'">
                            {{ seg.step?.nom || 'Paso sin nombre' }}
                        </p>

                        <p v-if="seg.fecha_completado" class="text-xs text-gray-400">
                            {{ formatearFecha(seg.fecha_completado) }}
                        </p>

                        <a v-if="seg.documento_path" :href="'http://localhost:8080/storage/' + seg.documento_path"
                            target="_blank" class="text-xs text-blue-500">
                            📎 Ver documento
                        </a>
                    </div>

                </div>

            </div>

            <!-- VACÍO -->
            <div v-else class="text-gray-400">
                Sin seguimiento
            </div>
        </div>

        <!-- BOTÓN -->
        <div class="mt-6">
            <button @click="$emit('cambiarVista', 'dashboard')" class="bg-gray-300 px-4 py-2 rounded-lg">
                Volver
            </button>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import MapboxOfertaClienteComponente from './MapboxOfertaClienteComponente.vue';
import SpinnerComponent from '../utiles/SpinnerComponent.vue';

const props = defineProps({
    oferta: Object
})

defineEmits(['cambiarVista'])

const tracking = ref(null);
const estaCargando = ref();

onMounted(async () => {
    estaCargando.value = true;
    try {
        const res = await axios.get(`/ofertas/${props.oferta.id}/tracking`)
        tracking.value = res.data
        estaCargando.value = false;
    } catch (e) {
        console.error(e)
    }
})

const formatearFecha = (fecha) => {
    return new Date(fecha).toLocaleString()
}
</script>