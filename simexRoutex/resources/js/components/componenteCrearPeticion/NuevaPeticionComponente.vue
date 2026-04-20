<template>
    <div class="p-8 bg-gray-100 min-h-screen">

        <h1 class="text-3xl font-bold mb-6 text-gray-800">
            Nueva Solicitud
        </h1>

        <!-- RUTA -->
        <div class="bg-white p-6 rounded-2xl shadow-sm mb-6">
            <h2 class="font-semibold text-lg mb-4">📍 Ruta</h2>

            <div class="grid grid-cols-2 gap-6">

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Origen</label>
                    <select v-model="form.puerto_id" class="w-full border border-gray-200 rounded-xl px-4 py-3">
                        <option :value="null">Selecciona origen</option>
                        <option v-for="p in puertos" :key="p.id" :value="p.id">
                            {{ p.nom }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Destino</label>
                    <select v-model="form.puerto_destino_id" class="w-full border border-gray-200 rounded-xl px-4 py-3">
                        <option :value="null">Selecciona destino</option>
                        <option v-for="p in puertos" :key="p.id" :value="p.id">
                            {{ p.nom }}
                        </option>
                    </select>
                </div>

            </div>
        </div>

        <!-- TRANSPORTE -->
        <div class="bg-white p-6 rounded-2xl shadow-sm mb-6">
            <h2 class="font-semibold text-lg mb-4">🚚 Transporte</h2>

            <div class="grid grid-cols-2 gap-6 mb-4">

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Tipo transporte</label>
                    <select v-model="form.transporte_id" class="w-full border border-gray-200 rounded-xl px-4 py-3">
                        <option :value="null">Selecciona transporte</option>
                        <option v-for="t in transportes" :key="t.id" :value="t.id">
                            {{ t.tipus }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600 mb-1 block">Incoterm</label>
                    <select v-model="form.incoterm_id" class="w-full border border-gray-200 rounded-xl px-4 py-3">
                        <option :value="null">Selecciona incoterm</option>
                        <option v-for="i in incoterms" :key="i.id" :value="i.id">
                            {{ i.tipos_incoterm?.nom }}
                        </option>
                    </select>
                </div>
            </div>

            <div>
                    <label class="text-sm text-gray-600 mb-1 block">Tipo flujo</label>
                    <select v-model="form.flujos_id" class="w-full border border-gray-200 rounded-xl px-4 py-3">
                        <option :value="null">Selecciona flujo</option>
                        <option v-for="f in flujos" :key="f.id" :value="f.id">
                            {{ f.tipus }}
                        </option>
                    </select>
            </div>
        </div>



        <!-- CARGA -->
        <div class="bg-white p-6 rounded-2xl shadow-sm mb-6">
            <h2 class="font-semibold text-lg mb-4">📦 Carga</h2>

            <div class="gap-6 mb-4">
                <label class="text-sm text-gray-600 mb-1 block">Tipo carga</label>
                <select v-model="form.cargas_id" class="w-full border border-gray-200 rounded-xl px-4 py-3">
                    <option :value="null">Selecciona carga</option>
                    <option v-for="c in cargas" :key="c.id" :value="c.id">
                        {{ c.tipus }}
                    </option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-4">

                <input v-model="form.peso" type="number" placeholder="Peso (kg)"
                    class="border border-gray-200 rounded-xl px-4 py-3" />

                <input v-model="form.volumen" type="number" placeholder="Volumen (m³)"
                    class="border border-gray-200 rounded-xl px-4 py-3" />

            </div>

            <input v-model="form.concepto" placeholder="Concepto"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 mb-4" />

            <textarea v-model="form.descripcion" placeholder="Descripción"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 h-24"></textarea>
        </div>

        <!-- BOTÓN -->
        <div class="flex justify-center mt-8">
            <button @click="enviar"
                class="bg-orange-500 hover:bg-orange-600 text-white px-10 py-3 rounded-xl font-semibold">
                Enviar solicitud
            </button>
        </div>

    </div>
</template>

<style scoped>
.input {
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 10px;
}
</style>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import axios from 'axios'

const puertos = ref([])
const transportes = ref([])
const incoterms = ref([])
const flujos = ref([])
const cargas = ref([])

const form = reactive({
    transporte_id: null,
    flujos_id: null,
    cargas_id: null,
    incoterm_id: null,
    puerto_id: null,
    puerto_destino_id: null,
    peso: null,
    volumen: null,
    concepto: '',
    descripcion: ''
})

const props = defineProps({
    user: Object
})

onMounted(async () => {
    const [t, i, p, f, c] = await Promise.all([
        axios.get('/tipoTransporte'),
        axios.get('/incoterms'),
        axios.get('/ports'),
        axios.get('/tipoFlujo'),
        axios.get('/tipoCarga')
    ])

    transportes.value = t.data
    incoterms.value = i.data
    puertos.value = p.data
    flujos.value = f.data
    cargas.value = c.data
})

const enviar = async () => {
    try {
        await axios.post('/ofertas/usuario', form)

        alert('Solicitud enviada ✅')
        emit('cambiarVista', 'dashboard');

    } catch (e) {
        if (e.response) {
            console.log('ERROR BACKEND:', e.response.data)
        } else {
            console.log('ERROR GENERAL:', e.message)
        }
    }
}
</script>
