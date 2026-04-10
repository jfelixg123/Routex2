<template>
    <div class="mt-10 flex justify-between items-center bd-r">
        <button @click="accionAtras" class="px-6 py-2 border rounded-lg hover:bg-gray-50 transition">
            <span v-if="paso === 1">✕ Cancelar </span>
            <span v-else>← Anterior</span>
        </button>
        <button @click="paso >= 3 ? $emit('volverHome') : $emit('siguiente')" class="px-8 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 font-bold transition flex items-center gap-2">
            <span v-if="paso < 3">Siguiente →</span>
            <span v-if="paso >= 3">Guardar</span>
        </button>
    </div>
</template>

<script setup>
const props = defineProps({
  paso: {
    type: Number,
    required: true
  }
});
const emit = defineEmits(['atras', 'siguiente', 'volverHome']);

const accionAtras = () => {
    if (props.paso === 1){
        emit('volverHome');
    }else{
        emit('atras');
    }
};

</script>

<style lang="scss" scoped>

</style>
