<template>
  <div class="p-6">

    <h1 class="text-2xl font-bold mb-4">Histórico de Ofertas</h1>

    <!-- FILTROS -->
    <div class="flex gap-4 mb-4">
      <button @click="filtro = 'todas'">Todas</button>
      <button @click="filtro = 'aceptado'">Aceptadas</button>
      <button @click="filtro = 'declinado'">Declinadas</button>
      <button @click="filtro = 'expirado'">Expiradas</button>
    </div>

    <!-- LISTA -->
    <div class="space-y-4">
      <OfertaHistorialCard
        v-for="oferta in ofertasFiltradas"
        :key="oferta.id"
        :oferta="oferta"
      />
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import OfertaHistorialCard from './OfertaHistorialCard.vue';

const ofertas = ref([]);
const filtro = ref('todas');

onMounted(async () => {
  const response = await axios.get('/historial-ofertas');
  ofertas.value = response.data;
});

const ofertasFiltradas = computed(() => {
  if (filtro.value === 'todas') return ofertas.value;

  return ofertas.value.filter(o => o.estado === filtro.value);
});
</script>
