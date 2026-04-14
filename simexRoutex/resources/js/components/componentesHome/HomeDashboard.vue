<template>
    <div class="flex-1 flex flex-col">
        <!-- Parte del home -->
        <div class="bg-gray-100">
            <CabeceraComponent :vistaActual="mostrar === 'nueva-oferta' ? 'ofertas' : mostrar"
                :pasoFormulario="pasoActualHijo" />
        </div>

        <!-- Controlamos el contenido dinamico en  esta parte ya quer lo vamos a controlar con la props recibida emitida por el propio boton -->
        <div class="flex-1 p-6 bg-gray-100">

            <!-- Primera vista -->
            <div v-if="mostrar === 'dashboard'">

                <!-- DASHBOARD DE OPERADOR -->

                <div v-if="props.rol === 1">
                    <h2 class="text-2xl font-semibold mb-4">Ofertas Activas</h2>
                    <OfertasActivasComponent />

                    <div class="pt-6 space-y-6">
                        <OfertasRecientesComponent @verDetalle="$emit('verDetalle', $event)" />
                    </div>

                    <div v-if="mostrar === 'nueva-oferta'">
                        <NuevaOfertaComponent @cambiarVista="$emit('cambiarVista', $event)"
                            @actualizarPasoHeader="sincronizarPaso"></NuevaOfertaComponent>
                    </div>
                    <div v-if="mostrar === 'ver-oferta'">
                        <NuevaOfertaComponent :ofertaAEditar="ofertaSeleccionada"
                            @cambiarVista="$emit('cambiarVista', $event)" />
                    </div>

                    <div v-if="mostrar === 'incoterms'">
                        <IncotermsComponent></IncotermsComponent>
                    </div>
                </div>

                <!-- DASHBOARD DE CLIENTE -->

                <div v-else>
                    <h2 class="text-2xl font-semibold mb-4">New Offers</h2>

                    <div>
                        <p>Dashboard cliente aquí</p>
                    </div>

                    <div>
                        <p></p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

import OfertasActivasComponent from './OfertasActivasComponent.vue';
import CabeceraComponent from '../componentesCabecera/CabeceraComponent.vue';
import OfertasRecientesComponent from './OfertasRecientesComponent.vue';
import IncotermsComponent from '../componentesIncoterms/IncotermsComponent.vue';

import NuevaOfertaComponent from '../componentesCrearOferta/NuevaOfertaComponent.vue';
const emit = defineEmits(['cambiarVista', 'verDetalle', 'actualizarPasoHeader'])

const props = defineProps({
    mostrar: {
        type: String,
        default: 'dashboard', // Por defecto muestra el panel
        rol: Number

    },
    ofertaSeleccionada: Object
});

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
