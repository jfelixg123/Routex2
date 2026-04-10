<template>
    <div class="max-w-4xl mx-auto mb-10 relative">
    <div class="absolute top-1/2 left-0 w-full h-0.5 bg-gray-200 -translate-y-1/2"></div>
    <div class="flex justify-between relative z-10">
      <div v-for="n in 3" :key="n" class="flex items-center gap-3 bg-gray-50 px-4">
        <span :class="{
            'flex items-center justify-center rounded-full w-10 h-10 font-bold transition-all duration-300': true,
            'bg-orange-500 text-white shadow-lg': paso >= n,
            'bg-gray-200 text-gray-400': paso < n
          }">
          {{ n }}
        </span>
        <span :class="{
            'text-sm transition-colors duration-300': true,
            'font-bold text-orange-600': paso >= n,
            'text-gray-400': paso < n
          }">
          {{ nombres[n-1] }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
    defineProps(['paso']);
    const nombres = ['Oferta', 'Ruta y cargas', 'Envio'];
</script>

<style lang="scss" scoped>

</style>
