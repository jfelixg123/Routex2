<template>
  <div class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">

    <!-- IZQUIERDA -->
    <div>
      <h1 class="text-2xl font-semibold text-gray-800">
        Panel del operador
      </h1>
      <p class="text-sm text-gray-400">
        Gestión y seguimiento de operaciones
      </p>
    </div>

    <!-- DERECHA -->
    <div class="flex items-center gap-4">

      <!-- BUSCADOR -->
      <div class="relative">
        <input
          type="text"
          placeholder="Buscar..."
          class="pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-400"
        />
        <span class="absolute left-3 top-2.5 text-gray-400">
          🔍
        </span>
      </div>

      <!-- BOTÓN -->
      <button class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
        + Nueva oferta
      </button>

      <!-- USUARIO -->
      <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
        👤
      </div>

    </div>

  </div>
</template>

<script setup>
</script>
