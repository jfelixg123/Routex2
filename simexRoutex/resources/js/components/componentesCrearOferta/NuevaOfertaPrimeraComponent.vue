<template>
    <h2 class="text-3xl font-bold text-gray-800 mb-2">Nueva Oferta Comercial</h2>
            <p class="text-gray-500 mb-8">Configure los términos comerciales y condiciones de transporte.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Concepto -->
                <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-700">Concepto (OFR)</label>
                <input type="text" placeholder="Ej: OFR-2024-001" class="border p-2 rounded-lg focus:ring-2 focus:ring-orange-400 outline-none">
                </div>

                <!-- Transporte desde -->
                <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-700">Transporte desde</label>
                <select class="border p-2 rounded-lg outline-none"><option>Seleccionar origen</option></select>
                </div>

                <!-- Hijo -->
                <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-700">Hijo</label>
                <select class="border p-2 rounded-lg outline-none"><option>Seleccionar hijo</option></select>
                </div>

                <!-- Fila 2: Carga y Destinatario -->
                <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-700">Carga Tipo</label>
                <select class="border p-2 rounded-lg outline-none"><option>Seleccionar tipo</option></select>
                </div>
                <div class="flex items-center gap-2 pt-8">
                    <input type="checkbox" id="terminal" class="w-4 h-4 accent-orange-500">
                    <label for="terminal" class="text-sm text-gray-600">Por terminal/tipo</label>
                </div>
                <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-700">Destinatario</label>
                <input type="text" placeholder="Buscar cliente..." class="border p-2 rounded-lg outline-none">
                </div>
            </div>

            <!-- Notas -->
            <div class="mt-6">
                <label class="text-sm font-semibold text-gray-700">Notas del apartado</label>
                <textarea rows="3" class="w-full border rounded-lg mt-2 p-3 outline-none" placeholder="Añada términos adicionales..."></textarea>
            </div>
</template>

<script setup>

</script>

<style lang="scss" scoped>

</style>
