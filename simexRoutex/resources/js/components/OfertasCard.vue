<template>
  <div
    class="bg-white shadow rounded-lg p-6 border-l-8 transition hover:shadow-xl flex flex-col justify-between h-full"
    :class="{
      'border-green-500': oferta.estado === 'Activa',
      'border-yellow-500': oferta.estado === 'Próxima',
      'border-red-500': oferta.estado === 'Expirada'
    }"
  >
    <div>
      <h3 class="text-lg font-bold mb-2">{{ oferta.titulo }}</h3>
      <p class="text-gray-600 mb-2">{{ oferta.descripcion }}</p>
      <span class="text-sm text-gray-500">{{ oferta.vigencia }}</span>
    </div>

    <div class="mt-4 flex justify-end gap-2">
      <button class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Editar</button>
      <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Eliminar</button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  oferta: Object
});
</script>

<style scoped>
</style>
