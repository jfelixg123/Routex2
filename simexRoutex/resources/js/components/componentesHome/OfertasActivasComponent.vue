<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <OfertasCard
      titulo="Ofertas Activas"
      :valor="estats.total"
      icono="🏷️"
      bgIcono="bg-orange-50 text-orange-600"
      :tendencia="5.2"
      subtitulo="vs. mes anterior"
    />

    <OfertasCard
      titulo="Ofertas finalizadas"
      :valor="estats.finalizadas"
      icono="✅"
      bgIcono="bg-green-50 text-green-600"
      :tendencia="2.1"
      subtitulo="Total completadas"
    />

    <OfertasCard
      titulo="Ofertas pendientes"
      :valor="estats.pendientes"
      icono="⏳"
      bgIcono="bg-orange-50 text-orange-600"
      :tendencia="1.2"
      subtitulo="Requieren atención"
    />
  </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';
    import OfertasCard from './OfertasCard.vue';

    const props = defineProps({
        estats: {
            type: Object,
            required: true,
            default: () => ({ pendientes: 0, finalizadas: 0, total: 0 })
        }
    });

</script>

<style scoped>
.grid > * {
  min-width: 200px;
}
</style>
