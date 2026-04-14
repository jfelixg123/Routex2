<template>
    <div class="flex-1 flex flex-col">
        <!-- Parte del home -->
        <div class="bg-gray-100">
            <CabeceraComponent :vistaActual="mostrar === 'nueva-oferta' ? 'ofertas' : mostrar"
                :pasoFormulario="pasoActualHijo" :user="user" />
        </div>

        <!-- Controlamos el contenido dinamico en  esta parte ya quer lo vamos a controlar con la props recibida emitida por el propio boton -->
        <div class="flex-1 p-6 bg-gray-100">

            <!-- Primera vista -->
            <div v-if="mostrar === 'dashboard'">

                <h2 class="text-2xl font-semibold mb-4">Welcome {{ user?.nom }}</h2>

                <!-- DASHBOARD DE OPERADOR -->

                <div v-if="esOperador">
                    <h2 class="text-2xl font-semibold mb-4">Ofertas Activas</h2>
                    <OfertasActivasComponent />

                    <div v-if="mostrar === 'incoterms'">
                        <IncotermsComponent></IncotermsComponent>
                    </div>
                </div>

                <!-- DASHBOARD DE CLIENTE -->

                <div v-else>
                    <h2 class="text-2xl font-semibold mb-4">Nuevas Ofertas</h2>
                        <NuevasOfertasClienteCards />
                </div>

                <div class="pt-6 space-y-6">
                    <OfertasRecientesComponent @verDetalle="$emit('verDetalle', $event)" />
                </div>

            </div>

                <div v-if="mostrar === 'nueva-oferta'">
                        <NuevaOfertaComponent @cambiarVista="$emit('cambiarVista', $event)"
                            @actualizarPasoHeader="sincronizarPaso"></NuevaOfertaComponent>
                </div>

                <div v-if="mostrar === 'nueva-peticion'">
                        <NuevaPeticionComponente @cambiarVista="$emit('cambiarVista', $event)" />
                    </div>

        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

import OfertasActivasComponent from './OfertasActivasComponent.vue';
import CabeceraComponent from '../componentesCabecera/CabeceraComponent.vue';
import OfertasRecientesComponent from './OfertasRecientesComponent.vue';
import IncotermsComponent from '../componentesIncoterms/IncotermsComponent.vue';

import NuevaOfertaComponent from '../componentesCrearOferta/NuevaOfertaComponent.vue';
import NuevasOfertasClienteCards from './NuevasOfertasClienteCards.vue';
import NuevaPeticionComponente from '../componenteCrearPeticion/NuevaPeticionComponente.vue';
const emit = defineEmits(['cambiarVista', 'verDetalle', 'actualizarPasoHeader'])

const props = defineProps({
    user: Object,
    mostrar: {
        type: String,
        default: 'dashboard', // Por defecto muestra el panel

    },
    ofertaSeleccionada: Object
});

const esOperador = computed(() => Number(props.user?.rol_id) === 1);

const pasoActualHijo = ref(1);

const sincronizarPaso = (nuevoPaso) => {
    const nombres = {
        1: 'ofertas', // Paso 1 -> ID 'ofertas'
        2: 'rutas',   // Paso 2 -> ID 'rutas'
        3: 'envios'   // Paso 3 -> ID 'envios'
    };
    pasoActualHijo.value = nombres[nuevoPaso];
};
</script>

<style scoped></style>
