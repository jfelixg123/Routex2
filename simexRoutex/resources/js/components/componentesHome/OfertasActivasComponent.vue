<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Tarjeta 1: Datos de tu API -->
    <OfertasCard
      titulo="Ofertas Activas"
      :valor="totalOfertas"
      icono="🏷️"
      bgIcono="bg-orange-50 text-orange-600"
      :tendencia="5.2"
      subtitulo="vs. mes anterior"
    />

    <!-- Tarjeta 2: Estática por ahora -->
    <OfertasCard
      titulo="Envíos Pendientes"
      valor="12"
      icono="📦"
      bgIcono="bg-blue-50 text-blue-600"
      :tendencia="-2.1"
      subtitulo="promedio 1.2h"
    />

    <!-- Tarjeta 3: Estática por ahora -->
    <OfertasCard
      titulo="Tasa de Cierre"
      valor="84%"
      icono="📈"
      bgIcono="bg-green-50 text-green-600"
      :tendencia="1.2"
      subtitulo="objetivo: 90%"
    />
  </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';
    import OfertasCard from './OfertasCard.vue';

    const totalOfertas = ref(0);

    const cargarDatos = async () => {
        try {
            const res = await axios.get('/ofertas');
            // Si tu API devuelve la colección de recursos:
            totalOfertas.value = res.data.length;
        } catch (e) {
            console.error("Error al conectar con la API", e);
        }
    };

    onMounted(cargarDatos);
</script>

<style scoped>
.grid > * {
  min-width: 200px;
}
</style>
