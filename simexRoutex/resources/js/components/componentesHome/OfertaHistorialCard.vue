<template>
  <div class="bg-white p-4 rounded shadow flex justify-between items-center">

    <div>
      <p class="font-bold">#{{ oferta.nombre }}</p>
      <p class="text-sm text-gray-500">
        {{ oferta.port_desti_id }} → {{ oferta.destino }}
      </p>
    </div>

    <div class="text-right">
      <p class="font-bold">{{ oferta.valor }} €</p>
      <p class="text-sm">{{ oferta.data_validessa_inicial }}</p>
    </div>

    <!-- ESTADO -->
    <span :class="colorEstado">
      {{ oferta.estado }}
    </span>

  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  oferta: Object
});

const colorEstado = computed(() => {
  switch (props.oferta.estado) {
    case 'aceptado':
      return 'text-green-500';
    case 'declinado':
      return 'text-red-500';
    case 'expirado':
      return 'text-yellow-500';
    default:
      return 'text-gray-500';
  }
});
</script>
