<template>


    <div class="p-6 bg-gray-100 min-h-screen">

        <h1 class="text-2xl font-bold mb-2">Solicitud de Cotización a Medida</h1>
        <p class="text-gray-500 mb-6">
            Proporcione los detalles de su carga para recibir una oferta personalizada
        </p>

        <!-- DETALLES RUTA -->
        <div class="bg-white p-6 rounded-xl shadow mb-6">
            <h2 class="font-semibold mb-4">📍 Detalles de la Ruta</h2>

            <div class="grid grid-cols-2 gap-4">
                <input v-model="form.origen" placeholder="Origen" class="input" />
                <input v-model="form.destino" placeholder="Destino" class="input" />
            </div>
        </div>

        <!-- LOGISTICA -->
        <div class="bg-white p-6 rounded-xl shadow mb-6">
            <h2 class="font-semibold mb-4">📦 Logística y Transporte</h2>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <select v-model="form.transporte" class="input">
                    <option value="">Seleccione modalidad</option>
                    <option>Marítimo</option>
                    <option>Aéreo</option>
                </select>

                <select v-model="form.incoterm" class="input">
                    <option>EXW</option>
                    <option>FOB</option>
                </select>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <input v-model="form.tipo" placeholder="Tipo mercancía" class="input" />
                <input v-model="form.peso" placeholder="Peso" class="input" />
                <input v-model="form.volumen" placeholder="Volumen" class="input" />
            </div>
        </div>

        <!-- REQUERIMIENTOS -->
        <div class="bg-white p-6 rounded-xl shadow mb-6">
            <h2 class="font-semibold mb-4">⚙️ Requerimientos</h2>

            <div class="flex gap-4 mb-4">
                <label><input type="checkbox" v-model="form.aduanas" /> Aduanas</label>
                <label><input type="checkbox" v-model="form.seguro" /> Seguro</label>
                <label><input type="checkbox" v-model="form.transporteFinal" /> Última milla</label>
            </div>

            <textarea v-model="form.comentarios" placeholder="Comentarios..." class="input"></textarea>
        </div>

        <!-- BOTONES -->
        <div class="flex justify-end gap-4">
            <button @click="$emit('cambiarVista', 'dashboard')" class="px-4 py-2 bg-gray-300 rounded">
                Cancelar
            </button>

            <button @click="enviar" class="px-4 py-2 bg-orange-500 text-white rounded">
                Enviar Solicitud
            </button>
        </div>

    </div>
</template>

<script setup>
import { reactive } from 'vue';
import axios from 'axios';

const emit = defineEmits(['cambiarVista']);

const form = reactive({
    origen: '',
    destino: '',
    transporte: '',
    incoterm: '',
    tipo: '',
    peso: '',
    volumen: '',
    aduanas: false,
    seguro: false,
    transporteFinal: false,
    comentarios: ''
});

const enviar = async () => {
    try {
        await axios.post('/peticiones', form);

        alert('Solicitud enviada ✅');

        emit('cambiarVista', 'dashboard');

    } catch (error) {
        console.log(error);
    }
};
</script>
