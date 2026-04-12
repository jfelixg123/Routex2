<template>
  <div class="bg-white shadow-sm px-8 flex items-center justify-between border-b">

    <!-- IZQUIERDA: LOGO Y MENÚ -->
    <div class="flex items-center gap-10">
      <!-- Logo -->
      <div class="flex items-center gap-2">
        <div class="bg-orange-500 text-white w-8 h-8 flex items-center justify-center rounded-lg font-bold">R</div>
        <span class="text-xl font-bold text-gray-800">Route<span class="text-orange-500">X</span> Transports</span>
      </div>

      <!-- Navegación estilo Pestañas -->
      <nav v-if="vistaActual === 'ofertas'" class="flex gap-8">
        <button
          v-for="item in menuItems"
          :key="item.id"
          @click="$emit('navegar', item.id)"
            :class="[
                'relative pb-5 pt-5 text-sm font-medium transition-all',
                pasoFormulario === item.id ? 'text-gray-900 font-bold' : 'text-gray-400'
            ]"
        >
          {{ item.nombre }}

          <!-- Línea naranja inferior activa -->
          <span
            v-if="pasoFormulario === item.id"
            class="absolute bottom-0 left-0 w-full h-0.5 bg-orange-500"
          ></span>
        </button>
      </nav>

    </div>

    <!-- DERECHA: PERFIL -->
    <div class="flex items-center gap-3">
      <div v-if="vistaActual === 'dashboard'" class="relative">
        <input
          type="text"
          placeholder="Buscar operaciones..."
          class="pl-10 pr-4 py-2 bg-gray-100 rounded-lg border-none focus:ring-2 focus:ring-orange-400 w-64 outline-none text-sm"
        />
        <span class="absolute left-3 top-2 text-gray-400">🔍</span>
      </div>
      <div class="text-right">
        <p class="text-sm font-bold text-gray-800 leading-tight">Admin User</p>
        <p class="text-xs text-gray-400">Logistics Manager</p>
      </div>
      <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-white">
        👤
      </div>
    </div>

  </div>
</template>

<script setup>
    defineProps({
    vistaActual: {
        type: String,
        default: 'nueva-oferta'
    },
    pasoFormulario: [String, Number]
    });
    const menuItems = [
        { id: 'ofertas', nombre: 'Ofertas' },
        { id: 'rutas', nombre: 'Rutas y cargas' },
        { id: 'envios', nombre: 'Envio' },
    ];
</script>
