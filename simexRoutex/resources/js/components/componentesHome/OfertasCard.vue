<template>
  <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-48 transition-all hover:shadow-md">
    <!-- PARTE SUPERIOR: Título e Icono -->
    <div class="flex justify-between items-start">
      <h4 class="text-[10px] font-extrabold text-gray-400 uppercase tracking-[0.15em]">
        {{ titulo }}
      </h4>
      <div :class="[bgIcono, 'w-10 h-10 rounded-xl flex items-center justify-center text-lg shadow-inner']">
        {{ icono }}
      </div>
    </div>

    <!-- VALOR PRINCIPAL -->
    <div class="mt-2">
      <span class="text-4xl font-black text-slate-800 tracking-tighter">
        {{ valor }}
      </span>
    </div>

    <!-- TENDENCIA / SUBTÍTULO -->
    <div class="mt-4 flex items-center gap-1">
      <span :class="['text-xs font-bold', tendencia > 0 ? 'text-green-500' : 'text-red-500']">
        {{ tendencia > 0 ? '↗' : '↘' }} {{ Math.abs(tendencia) }}%
      </span>
      <span class="text-[11px] text-gray-400 font-medium ml-1">
        {{ subtitulo }}
      </span>
    </div>
  </div>
</template>

<script setup>
    defineProps({
    titulo: String,
    valor: [String, Number],
    icono: String,
    bgIcono: String, // Ej: 'bg-orange-50 text-orange-500'
    tendencia: Number,
    subtitulo: String
    });
</script>

<style scoped>
</style>
