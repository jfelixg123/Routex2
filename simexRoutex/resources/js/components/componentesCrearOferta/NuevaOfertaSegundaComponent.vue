<template>
    <div class="space-y-6 b-d">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Ruta y carga</h2>
            <p class="text-sm text-gray-500">Defina el trayecto y la tipología de mercancía para el cálculo de fletes.</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-2 mb-4 text-orange-600 font-bold">
                <span class="text-lg">ⓘ</span> Información General
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500">Currency</label>
                    <select class="border rounded-lg p-2 text-sm outline-none focus:ring-2 focus:ring-orange-400">
                        <option>EUR - Euro</option>
                        <option>LB - Libras</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500">Sales Representative</label>
                    <input type="text" placeholder="Assign representative" class="border rounded-lg p-2 text-sm outline-none focus:ring-2 focus:ring-orange-400">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500">Incoterm</label>
                    <select class="border rounded-lg p-2 text-sm outline-none focus:ring-2 focus:ring-orange-400">
                        <option>EXW - Ex Works</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-semibold text-gray-500">Pricer</label>
                    <input type="text" value="System Admin" class="border rounded-lg p-2 text-sm bg-gray-50" readonly>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-2 mb-4 text-orange-600 font-bold">
                <span class="text-lg">📦</span> Detalles de Mercancía
            </div>

            <!--Primera seccion-->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500">Customer Reference</label>
                <input type="text" placeholder="Ref: 2024-X-99" class="border rounded-lg p-2 text-sm outline-none">
                </div>
                <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500">Destination free days</label>
                <input type="number" value="7" class="border rounded-lg p-2 text-sm outline-none">
                </div>
                <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500">Value of goods</label>
                <input type="text" placeholder="€ 0.00" class="border rounded-lg p-2 text-sm outline-none">
                </div>
            </div>

            <!--Segunda seccion-->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500">Description of goods</label>
                <input type="text" placeholder="General Cargo / Industrial parts" class="border rounded-lg p-2 text-sm outline-none">
                </div>
                <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500">Gross Weight (kg)</label>
                <input type="number" value="0" class="border rounded-lg p-2 text-sm outline-none">
                </div>
                <div class="flex flex-col gap-1">
                <label class="text-xs font-semibold text-gray-500">Taxable weight (kg)</label>
                <input type="number" value="0" class="border rounded-lg p-2 text-sm outline-none">
                </div>
            </div>

            <!--Tercera seccion-->
            <div class="border-t pt-4">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-sm font-bold text-gray-700">Medidas (Measures)</h4>
                    <button @click="resetearMedidas" class="text-xs text-orange-500 font-bold hover:underline italic">RESET</button>
                </div>

                <div v-for="(item, index) in medidas" :key="index" class="grid grid-cols-7 gap-2">
                    <input v-model="item.cantidad" type="number" class="border rounded p-2 text-xs outline-none focus:border-orange-400" placeholder="1">
                    <input v-model="item.largo" type="text" class="border rounded p-2 text-xs outline-none" placeholder="0">
                    <input v-model="item.ancho" type="text" class="border rounded p-2 text-xs outline-none" placeholder="0">
                    <input v-model="item.alto" type="text" class="border rounded p-2 text-xs outline-none" placeholder="0">
                    <input v-model="item.peso" type="text" class="border rounded p-2 text-xs outline-none" placeholder="0">
                    <select v-model="item.tipo" class="border rounded p-2 text-xs outline-none">
                        <option>Palet</option>
                        <option>Caja</option>
                        <option>Contenedor</option>
                    </select>

                    <!-- Botón para añadir solo en la última fila, o uno de borrar -->
                    <button
                        v-if="index === medidas.length - 1"
                        @click="agregarFila"
                        class="bg-orange-500 text-white rounded font-bold hover:bg-orange-600 transition"
                    >+</button>
                    <button
                        v-else
                        @click="medidas.splice(index, 1)"
                        class="text-gray-300 hover:text-red-500 text-xs"
                    >✕</button>
                </div>


            </div>
        </div>

    </div>
</template>

<script setup>
    import { ref, reactive } from 'vue';

    // Definimos la estructura de una fila vacía
    const nuevaFila = () => ({
    cantidad: 1,
    largo: '',
    ancho: '',
    alto: '',
    peso: '',
    tipo: 'Palet'
    });

    // Iniciamos con una fila por defecto
    const medidas = ref([nuevaFila()]);

    // Función para añadir
    const agregarFila = () => {
    medidas.value.push(nuevaFila());
    };

    // Función para resetear
    const resetearMedidas = () => {
    medidas.value = [nuevaFila()];
};
</script>

<style lang="scss" scoped>

</style>
