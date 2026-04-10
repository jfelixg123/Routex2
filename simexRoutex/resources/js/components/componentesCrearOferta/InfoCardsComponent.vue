<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10 max-w-8xl mx-auto">
    <div v-for="card in cards" :key="card.title" :class="[card.bg, 'p-5 rounded-xl border flex gap-4', card.border]">
      <span class="text-xl">{{ card.icon }}</span>
      <div>
        <h4 class="font-bold text-gray-800 text-sm">{{ card.title }}</h4>
        <p class="text-xs text-gray-500 mt-1">{{ card.text }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
    const cards = [
        {
            title: 'Documentación necesaria',
            text: 'Asegúrese de tener el RFC y comprobante de domicilio antes de avanzar.',
            icon: 'ℹ️',
            bg: 'bg-orange-50',
            border: 'border-orange-100'
        },
        {
            title: '¿Necesita ayuda?',
            text: 'Contacte con soporte comercial si tiene dudas sobre las tarifas.',
            icon: '❓',
            bg: 'bg-blue-50',
            border: 'border-blue-100'
        },
        {
            title: 'Historial',
            text: 'Última oferta creada hace 2 horas por Juan Pérez.',
            icon: '🕒',
            bg: 'bg-gray-50',
            border: 'border-gray-200'
        }
            ];
</script>

<style lang="scss" scoped>

</style>
