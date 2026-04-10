<template>
  <div class="w-64 bg-gray-800 text-white flex flex-col justify-between min-h-screen p-6">
    <div>
      <h2 class="text-xl font-semibold mb-6">SimexRouteX</h2>
      <ul class="space-y-3">
        <li><a href="#" @click.prevent="$emit('cambiarVista', 'dashboard')"
            :class="[
                'flex items-center gap-2 p-2 rounded transition-all duration-200',
                vistaActual === 'dashboard'
                ? 'bg-orange-500 text-white shadow-md'
                : 'text-gray-400 hover:bg-gray-700 hover:text-white'
            ]">🏠 Dashboard</a></li>
        <li><a href="#" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">💼 Ofertas</a></li>
        <li><a href="#" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">📦 Incoterms</a></li>
        <li><a href="#" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">👥 Usuarios</a></li>
        <li><a href="#" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">📢 Comunicaciones</a></li>
        <li><a href="#" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">⚠️ Incidencias</a></li>
        <li><a href="#" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">⚙️ Configuración</a></li>
      </ul>
    </div>
    <div>
      <button
      @click="$emit('cambiarVista', 'nueva-oferta')"
      class="w-full px-4 py-2 bg-orange-500 text-white font-semibold rounded hover:bg-orange-600 transition">
        Crear Oferta
      </button>
    </div>
  </div>
</template>

<script setup>
const name = 'MenuLateralComponent';
defineProps({
  vistaActual: {
    type: String,
    default: 'dashboard'
  }
});
</script>

<style scoped>
</style>
