<template>
    <div class="bg-white rounded-2xl shadow-lg p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-semibold text-gray-800">
        Ofertas recientes
      </h2>
      <span class="text-sm text-gray-400">
        {{ fecha }}
      </span>
    </div>

    <!-- LISTA -->
    <div class="space-y-3 max-h-100 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300">

      <div
        v-for="oferta in ofertas"
        :key="oferta.id"
        class="flex justify-between items-center bg-gray-50 p-4 rounded-xl hover:bg-gray-100 transition"
      >
        <!-- INFO -->
        <div>
          <p class="font-semibold text-gray-800">
            {{ oferta.ruta }}
          </p>
          <p class="text-sm text-gray-500">
            {{ oferta.id }}
          </p>
        </div>

        <!-- ESTADO -->
        <span
          class="px-3 py-1 text-xs rounded-full font-medium"
          :class="getEstadoClass(oferta.estado)"
        >
          {{ oferta.estado }}
        </span>
      </div>

    </div>

  </div>
</template>

<script setup>
    import { ref } from 'vue';

    const fecha = new Date().toLocaleDateString();

    const ofertas = ref([
    { id: 'OF-1023', ruta: 'Madrid → Barcelona', estado: 'Activa' },
    { id: 'OF-1024', ruta: 'Valencia → Sevilla', estado: 'Pendiente' },
    { id: 'OF-1025', ruta: 'Bilbao → Madrid', estado: 'Cerrada' },
    { id: 'OF-1023', ruta: 'Madrid → Barcelona', estado: 'Activa' },
    { id: 'OF-1024', ruta: 'Valencia → Sevilla', estado: 'Pendiente' },
    { id: 'OF-1025', ruta: 'Bilbao → Madrid', estado: 'Cerrada' },
    { id: 'OF-1023', ruta: 'Madrid → Barcelona', estado: 'Activa' },
    { id: 'OF-1024', ruta: 'Valencia → Sevilla', estado: 'Pendiente' },
    { id: 'OF-1025', ruta: 'Bilbao → Madrid', estado: 'Cerrada' },
    ]);

    // 🧠 FP: función pura (no modifica nada)
    const getEstadoClass = (estado) => {
    if (estado === 'Activa') return 'bg-green-100 text-green-600';
    if (estado === 'Pendiente') return 'bg-yellow-100 text-yellow-600';
    return 'bg-gray-200 text-gray-600';
    };
</script>

<style lang="scss" scoped>

</style>
