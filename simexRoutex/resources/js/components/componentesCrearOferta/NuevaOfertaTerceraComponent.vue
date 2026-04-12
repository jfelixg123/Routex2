<template>
    <div class="space-y-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Envío</h2>
            <p class="text-gray-500">Complete the shipping details for your cargo</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Comentarios -->
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-1">
            <label class="text-xs font-bold text-gray-400 uppercase">Comentarios Internos</label>
            <textarea v-model="form.comentarios_internos" rows="4" class="border rounded-lg p-3 text-sm outline-none"></textarea>
            </div>
            <div class="flex flex-col gap-1">
            <label class="text-xs font-bold text-gray-400 uppercase">Comentarios para Imprimir (Footer)</label>
            <textarea v-model="form.comentarios_imprimir" rows="4" class="border rounded-lg p-3 text-sm outline-none bg-blue-50 focus:bg-white"></textarea>
            </div>
        </div>

        <!-- Contacto -->
        <div class="bg-gray-50 p-6 rounded-xl space-y-4">
            <h4 class="text-sm font-bold text-slate-700 uppercase">Detalles de Contacto</h4>
            <div class="flex flex-col gap-1">
            <label class="text-xs text-gray-500">Nombre de Contacto</label>
            <input type="text" class="border rounded-lg p-2 bg-white outline-none" :value="`${form.cliente_nombre} - ${form.cliente_apellidos}`">
            </div>
            <div class="grid grid-cols-2 gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-xs text-gray-500">Teléfono</label>
                <input type="text" class="border rounded-lg p-2 bg-white outline-none" v-model="form.cliente_tlfn">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs text-gray-500">Email</label>
                <input type="email" class="border rounded-lg p-2 bg-white outline-none" v-model="form.cliente_correu">
            </div>
            </div>
        </div>
        </div>

        <div v-if="form.id" class="mt-10 p-6 border-t border-red-100 bg-red-50 rounded-xl flex justify-between items-center">
            <div>
                <h4 class="text-red-700 font-bold">Zona de Peligro</h4>
                <p class="text-xs text-red-500">Esta acción no se puede deshacer.</p>
            </div>
            <button
                @click="$emit('eliminar')"
                class="bg-red-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-700 transition shadow-sm"
            >
                Eliminar Oferta
            </button>
        </div>

    </div>
</template>

<script setup>
    import { reactive } from 'vue';
    const props = defineProps(['form']);
    const emit = defineEmits(['eliminar']);
</script>

<style lang="scss" scoped>

</style>
