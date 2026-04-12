<template>
    <div class="mt-8 border rounded-xl p-6 bg-gray-50/50 shadow-inner relative">
    <!-- Pestaña de título -->
    <div class="inline-block px-4 py-2 bg-white border border-b-0 rounded-t-lg font-bold text-sm text-gray-700 absolute -top-[37px] left-4">
      Measures
    </div>

    <!-- Formulario de entrada -->
    <div class="grid grid-cols-1 md:grid-cols-8 gap-3 items-end mb-6 bg-white p-4 rounded-lg border shadow-sm">
      <div class="flex flex-col gap-1">
        <label class="text-[10px] font-bold text-gray-500 uppercase">Packages:</label>
        <input v-model.number="currentMeasure.packages" type="number" class="border p-2 rounded text-sm outline-none focus:border-blue-400">
      </div>
      <div class="flex flex-col gap-1">
        <label class="text-[10px] font-bold text-gray-500 uppercase">Length (cm.):</label>
        <input v-model.number="currentMeasure.length" type="number" class="border p-2 rounded text-sm outline-none focus:border-blue-400">
      </div>
      <div class="flex flex-col gap-1">
        <label class="text-[10px] font-bold text-gray-500 uppercase">Width (cm.):</label>
        <input v-model.number="currentMeasure.width" type="number" class="border p-2 rounded text-sm outline-none focus:border-blue-400">
      </div>
      <div class="flex flex-col gap-1">
        <label class="text-[10px] font-bold text-gray-400 uppercase">Height (cm.):</label>
        <input v-model.number="currentMeasure.height" type="number" class="border p-2 rounded text-sm outline-none focus:border-blue-400">
      </div>
      <div class="flex flex-col gap-1">
        <label class="text-[10px] font-bold text-gray-400 uppercase">Unitary gross:</label>
        <input v-model.number="currentMeasure.unitary_weight" type="number" class="border p-2 rounded text-sm outline-none focus:border-blue-400">
      </div>
      <div class="flex flex-col gap-1">
        <label class="text-[10px] font-bold text-gray-300 uppercase">Gross Weight:</label>
        <div class="p-2 text-sm text-gray-400 font-bold bg-gray-50 rounded">{{ computedGrossWeight }}</div>
      </div>
      <div class="flex flex-col gap-1">
        <label class="text-[10px] font-bold text-gray-300 uppercase">Volume:</label>
        <div class="p-2 text-sm text-gray-400 font-bold bg-gray-50 rounded">{{ computedVolume }}</div>
      </div>
      <button @click="addMeasure" class="bg-[#0088cc] text-white py-2 px-4 rounded font-bold hover:bg-blue-600 transition h-10 shadow-sm">
        Add
      </button>
    </div>

    <!-- Tabla Azul de Resultados -->
    <div class="overflow-hidden rounded-lg border shadow-lg bg-white">
      <table class="w-full text-left text-xs">
        <thead class="bg-[#006699] text-white font-bold uppercase tracking-wider">
          <tr>
            <th class="p-3 border-r border-blue-400">Packages</th>
            <th class="p-3 border-r border-blue-400">Length</th>
            <th class="p-3 border-r border-blue-400">Width</th>
            <th class="p-3 border-r border-blue-400">Height</th>
            <th class="p-3 border-r border-blue-400">Unitary Weight</th>
            <th class="p-3 border-r border-blue-400">Total Gross</th>
            <th class="p-3">Total Volume</th>
            <th class="p-3 text-center">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="(m, i) in form.medidas" :key="i" class="hover:bg-blue-50 transition">
            <td class="p-3 font-medium">{{ m.packages }}</td>
            <td class="p-3 text-gray-600">{{ m.length }}</td>
            <td class="p-3 text-gray-600">{{ m.width }}</td>
            <td class="p-3 text-gray-600">{{ m.height }}</td>
            <td class="p-3 text-gray-600">{{ m.unitary_weight }}</td>
            <td class="p-3 text-blue-700 font-bold">{{ m.gross_weight }} kg</td>
            <td class="p-3 text-blue-700 font-bold">{{ m.volume }} m³</td>
            <td class="p-3 text-center">
                <button @click="removeMeasure(i)" class="text-red-400 hover:text-red-600">✕</button>
            </td>
          </tr>
          <tr v-if="!form.medidas || form.medidas.length === 0">
            <td colspan="8" class="p-10 text-center italic text-blue-400 font-medium bg-white">
              No records found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
    import { ref, computed } from 'vue';

    const props = defineProps({
    form: {
        type: Object,
        required: true
    }
    });

    // Estado local para los inputs
    const currentMeasure = ref({
    packages: 1,
    length: null,
    width: null,
    height: null,
    unitary_weight: null
    });

    // Cálculos en tiempo real para la fila actual
    const computedGrossWeight = computed(() => {
        return (currentMeasure.value.packages * (currentMeasure.value.unitary_weight || 0)).toFixed(2);
    });

    const computedVolume = computed(() => {
        const vol = (currentMeasure.value.packages * currentMeasure.value.length * currentMeasure.value.width * currentMeasure.value.height) / 1000000;
        return vol > 0 ? vol.toFixed(3) : "0.000";
    });

    // FUNCIÓN PARA AÑADIR A LA LISTA DEL FORMULARIO PRINCIPAL
    const addMeasure = () => {
    // Validación simple: no añadir si faltan datos clave
    if (!currentMeasure.value.length || !currentMeasure.value.unitary_weight) {
        alert("Por favor, rellene las medidas y el peso.");
        return;
    }

    // Inicializamos el array en el objeto principal si no existe
    if (!props.form.medidas) {
        props.form.medidas = [];
    }

    // Insertamos el objeto calculado
    props.form.medidas.push({
        packages: currentMeasure.value.packages,
        length: currentMeasure.value.length,
        width: currentMeasure.value.width,
        height: currentMeasure.value.height,
        unitary_weight: currentMeasure.value.unitary_weight,
        gross_weight: computedGrossWeight.value,
        volume: computedVolume.value
    });

    // Limpiamos los inputs para la siguiente entrada
    currentMeasure.value = {
        packages: 1,
        length: null,
        width: null,
        height: null,
        unitary_weight: null
    };
    };

    const removeMeasure = (index) => {
        props.form.medidas.splice(index, 1);
    };
</script>

<style lang="scss" scoped>

</style>
