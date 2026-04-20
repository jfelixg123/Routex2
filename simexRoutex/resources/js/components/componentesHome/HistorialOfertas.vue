<template>
    <div class="p-6">

        <h1 class="text-2xl font-bold mb-4">Histórico de Ofertas</h1>

        <!-- FILTROS -->
        <div class="flex gap-4 mb-4">
            <button @click="filtro = 'todas'">Todas</button>
            <button @click="filtro = 'Aceptada'">Aceptadas</button>
            <button @click="filtro = 'Pendent'">Pendiente</button>
            <button @click="filtro = 'Cancel·lada'">Cancelada</button>
        </div>

        <spinner-component v-if="estaCargando"/>
        <!-- LISTA -->
        <div class="space-y-4">
            <OfertaHistorialCard v-for="oferta in ofertasFiltradas" :key="oferta.id" :oferta="oferta" />
        </div>

    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import OfertaHistorialCard from './OfertaHistorialCard.vue';
import SpinnerComponent from '../utiles/SpinnerComponent.vue';

const ofertas = ref([]);
const filtro = ref('todas');
const estaCargando = ref();

const historial = async () => {
    estaCargando.value = true;
    const response = await axios.get('/ofertas/historial');
    ofertas.value = response.data;
    estaCargando.value = false;
}

onMounted(historial);

const ofertasFiltradas = computed(() => {
    if (filtro.value === 'todas') return ofertas.value;

    return ofertas.value.filter(o => o.estats_ofertes?.estat === filtro.value);
});
</script>
