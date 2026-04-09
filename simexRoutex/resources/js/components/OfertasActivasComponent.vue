<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <OfertaCard
      v-for="oferta in ofertas"
      :key="oferta.id"
      :oferta="oferta"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import OfertaCard from './OfertasCard.vue';

const ofertas = ref([
  { id: 1, titulo: 'Oferta Activa', descripcion: 'Descuento 50% en productos seleccionados', vigencia: 'Hasta 30/04/2026', estado: 'Activa' },
  { id: 2, titulo: 'Oferta Próxima', descripcion: 'Promoción limitada', vigencia: 'Hasta 05/05/2026', estado: 'Próxima' },
  { id: 3, titulo: 'Oferta Expirada', descripcion: 'Finalizó la oferta', vigencia: 'Hasta 10/05/2026', estado: 'Expirada' },
]);
</script>

<style scoped>
.grid > * {
  min-width: 200px;
}
</style>
