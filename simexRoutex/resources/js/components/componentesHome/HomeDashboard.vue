<template>
     <div class="flex-1 flex flex-col">
        <div class="bg-gray-100">
            <CabeceraComponent
                :vistaActual="mostrar"
                :pasoFormulario="pasoActualHijo"
                :user="user"
            />
        </div>


        <div class="flex-1 p-6 bg-gray-100">

            <!-- VISTA: DASHBOARD -->
            <div v-if="mostrar === 'dashboard'">

                <!-- Contenido específico por ROL -->
                <div v-if="esOperador">
                    <h2 class="text-2xl font-semibold mb-4">Ofertas Activas</h2>
                    <OfertasActivasComponent :estats="estats"/>
                </div>

                <div v-else>
                    <h2 class="text-2xl font-semibold mb-4">Nuevas Ofertas</h2>
                    <NuevasOfertasClienteCards :ofertas="ofertas" />
                </div>

                <div class="pt-6 space-y-6">
                    <OfertasRecientesComponent @verDetalle="$emit('verDetalle', $event)" />
                </div>
            </div>

            <div v-if="mostrar === 'incoterms'" :key="mostrar">
                <IncotermsComponent />
            </div>

             <!-- VISTA: Ver OFERTA -->
            <div v-if="mostrar === 'ver-oferta'">
                <NuevaOfertaComponent
                    :ofertaAEditar="ofertaSeleccionada"
                    @cambiarVista="$emit('cambiarVista', $event)"
                    @actualizarPasoHeader="sincronizarPaso"
                />
            </div>

            <!-- VISTA: NUEVA OFERTA -->
            <div v-if="mostrar === 'nueva-oferta'">
                <NuevaOfertaComponent
                    @cambiarVista="$emit('cambiarVista', $event)"
                    @actualizarPasoHeader="sincronizarPaso"
                    @refresh="fetchStats"
                />
            </div>

            <!-- VISTA: NUEVA PETICIÓN -->
            <div v-if="mostrar === 'nueva-peticion'">
                <NuevaPeticionComponente @cambiarVista="$emit('cambiarVista', $event)" />
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

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

const ofertas = ref([]);

const getOfertas = async () => {
    try {
        const res = await axios.get('/ofertas');

        ofertas.value = res.data;

        console.log('OFERTAS GUARDADAS:', ofertas.value);
    } catch (error) {
        console.error(error);
    }
};

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

const estats = ref({
    pendientes: 0,
    finalizadas: 0,
    total: 0
});

const fetchStats = async () => {
    try {
        const response = await axios.get('/count/oferta');
        estats.value = response.data;
    } catch (error) {
        console.error("Error cargando estadísticas:", error);
    }
};

onMounted(() => {
    getOfertas();
    fetchStats();
});
</script>

<style scoped></style>
