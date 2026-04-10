<template>
    <div class="flex-1 flex flex-col">
        <!-- Parte del home -->
        <div class="bg-gray-100">
             <CabeceraComponent :vistaActual="mostrar === 'nueva-oferta' ? 'ofertas' : mostrar" />
        </div>

        <!-- Controlamos el contenido dinamico en  esta parte ya quer lo vamos a controlar con la props recibida emitida por el propio boton -->
        <div class="flex-1 p-6 bg-gray-100">

            <!-- Primera vista -->
            <div v-if="mostrar === 'dashboard'">
                <h2 class="text-2xl font-semibold mb-4">Ofertas Activas</h2>
                <OfertasActivasComponent />
                <div class="pt-6 space-y-6">
                    <!-- otras cosas -->
                    <OfertasRecientesComponent />
                </div>
            </div>
            <div v-if="mostrar === 'nueva-oferta'">
                <NuevaOfertaComponent></NuevaOfertaComponent>
            </div>


        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

import OfertasActivasComponent from './OfertasActivasComponent.vue';
import CabeceraComponent from '../componentesCabecera/CabeceraComponent.vue';
import OfertasRecientesComponent from './OfertasRecientesComponent.vue';

import NuevaOfertaComponent from '../componentesCrearOferta/NuevaOfertaComponent.vue';

const props = defineProps({
    mostrar: {
        type: String,
        default: 'dashboard' // Por defecto muestra el panel
    }
});
</script>

<style scoped>
</style>
