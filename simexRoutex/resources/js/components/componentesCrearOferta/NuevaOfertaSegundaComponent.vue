<template>
    <div class="space-y-6 b-d">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Ruta y carga</h2>
            <p class="text-sm text-gray-500">Defina el trayecto y la tipología de mercancía para el cálculo de fletes.</p>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-400">Modo Transporte</label>
                <select v-model="form.transporte_id" class="border rounded-lg p-2 outline-none">
                    <option v-for="e in transportes" :key="e.id" :value="e.id">{{ e.tipus }}</option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-400">Flujo (Flow)</label>
                <select v-model="form.flujos_id" class="border rounded-lg p-2 outline-none">
                    <option v-for="e in flujos" :key="e.id" :value="e.id">{{ e.tipus }}</option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-400">Tipo Carga</label>
                <select v-model="form.cargas_id" class="border rounded-lg p-2 outline-none">
                    <option v-for="e in cargas" :key="e.id" :value="e.id">{{ e.tipus }}</option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-400">Customer Ref.</label>
                <input v-model="form.customerRef" type="text" class="border rounded-lg p-2 text-sm outline-none" placeholder="Referencia interna">
            </div>

            <template v-if="form.transporte_id == 1">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-500 uppercase">Puerto Origen</label>
                    <input
                        list="puertosList"
                        v-model="form.puerto_nombre"
                        @input="buscarPuerto"
                        class="border p-2 rounded-lg outline-none">
                    <datalist id="puertosList">
                        <option v-for="c in puertosFiltrados" :key="c.id" :value="c.nom"></option>
                    </datalist>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-500 uppercase">Puerto Destino</label>
                    <input
                        list="puertosListDes"
                        v-model="form.puerto_nombre_destino"
                        @input="buscarPuertoDestino"
                        class="border p-2 rounded-lg outline-none">
                    <datalist id="puertosListDes">
                        <option v-for="c in puertosFiltradosDestino" :key="c.id" :value="c.nom"></option>
                    </datalist>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-400">Línea Transporte</label>
                    <select v-model="form.lineaTransporte_id" class="border rounded-lg p-2 outline-none">
                        <option v-for="e in lines" :key="e.id" :value="e.id">{{ e.nom }}</option>
                    </select>
                </div>
            </template>

            <template v-else-if="form.transporte_id == 2">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-500 uppercase">Aeropuerto Origen</label>
                    <input
                        list="aeropuertosList"
                        v-model="form.aeropuerto_nombre"
                        @input="buscarAeropuerto"
                        class="border p-2 rounded-lg outline-none">
                    <datalist id="aeropuertosList">
                        <option v-for="c in aeropuertosFiltrados" :key="c.id" :value="c.nom"></option>
                    </datalist>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-500 uppercase">Aeropuerto Destino</label>
                    <input
                        list="aeropuertosDestinoList"
                        v-model="form.aeropuerto_nombre_destino"
                        @input="buscarAeropuertoDestino"
                        class="border p-2 rounded-lg outline-none">
                    <datalist id="aeropuertosDestinoList">
                        <option v-for="c in aeropuertosFiltradosDestino" :key="c.id" :value="c.nom"></option>
                    </datalist>
                </div>
            </template>

            <template v-else-if="form.transporte_id == 3">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-500 uppercase">Transportista</label>
                    <input
                        list="transportistaList"
                        v-model="form.transportista_nombre"
                        @input="buscrTransportistas"
                        class="border p-2 rounded-lg outline-none">
                    <datalist id="transportistaList">
                        <option v-for="c in tranportistasFiltrados" :key="c.id" :value="c.nom"></option>
                    </datalist>
                </div>
            </template>

        </div>

            <!-- Especificaciones de Mercancía -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-400 uppercase">Descripción de la mercancía</label>
                <textarea v-model="form.descripcion" rows="4" class="border rounded-lg p-3 text-sm outline-none bg-gray-50 focus:bg-white"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Peso Bruto (kg)</label>
                    <input v-model="form.peso" type="number" class="border rounded-lg p-2 outline-none">
                </div>
                    <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Volumen (CBM)</label>
                    <input v-model="form.volumen" type="number" class="border rounded-lg p-2 outline-none">
                </div>
                    <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Bultos (Packages)</label>
                    <input v-model="form.bultos" type="number" class="border rounded-lg p-2 outline-none">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Valor Mercancía</label>
                    <input v-model="form.valor" type="number" class="border rounded-lg p-2 outline-none">
                </div>
            </div>
        </div>

        <MeasurasComponent v-if="form.transporte_id == 2" :form="form"/>

    </div>
</template>

<script setup>
    import MeasurasComponent from './MesurasComponent.vue';
    import { reactive, ref } from 'vue';

    const props = defineProps(['form', 'transportes', 'flujos', 'cargas', 'lines']);

    const puertosFiltrados = ref([]);
    const puertosFiltradosDestino = ref([]);
    const aeropuertosFiltrados = ref([]);
    const aeropuertosFiltradosDestino = ref([]);
    const tranportistasFiltrados = ref([]);

    let timeoutBusqueda = null;

    const buscarPuerto = async () => {
        clearTimeout(timeoutBusqueda);

        if (props.form.puerto_nombre.length >= 3) {
            timeoutBusqueda = setTimeout(async () => {
                try{
                    const res = await axios.get('port/buscar', {
                        params: { q: props.form.puerto_nombre }
                    });

                    puertosFiltrados.value = res.data;

                    const puertoSeleccionado = puertosFiltrados.value.find(c => c.nom === props.form.puerto_nombre);

                    if (puertoSeleccionado) {
                        props.form.puerto_id = puertoSeleccionado.id;
                        console.log("ID del cliente guardado:", props.form.puerto_id);
                    } else {
                        props.form.puerto_id = null;
                    }

                }catch(error){
                    console.error("Error real:", error.response?.data);
                }
            }, 500);
        }else{
            puertosFiltrados.value = [];
        }
    };

    const buscarPuertoDestino = async () => {
        clearTimeout(timeoutBusqueda);

        if (props.form.puerto_nombre_destino.length >= 3) {
            timeoutBusqueda = setTimeout(async () => {
                try{
                    const res = await axios.get('port/buscar', {
                        params: { q: props.form.puerto_nombre_destino }
                    });

                    puertosFiltradosDestino.value = res.data;

                    const puertoSeleccionado = puertosFiltradosDestino.value.find(c => c.nom === props.form.puerto_nombre_destino);

                    if (puertoSeleccionado) {
                        props.form.puerto_destino_id = puertoSeleccionado.id;
                        console.log("ID del cliente guardado:", props.form.puerto_destino_id);
                    } else {
                        props.form.puerto_destino_id = null;
                    }

                }catch(error){
                    console.error("Error real:", error.response?.data);
                }
            }, 500);
        }else{
            puertosFiltradosDestino.value = [];
        }
    };

    const buscarAeropuerto = async () => {
        clearTimeout(timeoutBusqueda);

        if (props.form.aeropuerto_nombre.length >= 3) {
            timeoutBusqueda = setTimeout(async () => {
                try{
                    const res = await axios.get('aeropuerto/buscar', {
                        params: { q: props.form.aeropuerto_nombre }
                    });

                    aeropuertosFiltrados.value = res.data;

                    const aeropuertoSeleccionado = aeropuertosFiltrados.value.find(c => c.nom === props.form.aeropuerto_nombre);

                    if (aeropuertoSeleccionado) {
                        props.form.aeropuerto_id = aeropuertoSeleccionado.id;
                        console.log("ID del cliente guardado:", props.form.aeropuerto_id);
                    } else {
                        props.form.aeropuerto_id = null;
                    }

                }catch(error){
                    console.error("Error real:", error.response?.data);
                }
            }, 500);
        }else{
            aeropuertosFiltrados.value = [];
        }
    };

    const buscarAeropuertoDestino = async () => {
        clearTimeout(timeoutBusqueda);

        if (props.form.aeropuerto_nombre_destino.length >= 3) {
            timeoutBusqueda = setTimeout(async () => {
                try{
                    const res = await axios.get('aeropuerto/buscar', {
                        params: { q: props.form.aeropuerto_nombre_destino }
                    });

                    aeropuertosFiltradosDestino.value = res.data;

                    const aeropuertoDestinoSeleccionado = aeropuertosFiltradosDestino.value.find(c => c.nom === props.form.aeropuerto_nombre_destino);

                    if (aeropuertoDestinoSeleccionado) {
                        props.form.aeropuerto_destino_id = aeropuertoDestinoSeleccionado.id;
                        console.log("ID del cliente guardado:", props.form.aeropuerto_destino_id);
                    } else {
                        props.form.aeropuerto_destino_id = null;
                    }

                }catch(error){
                    console.error("Error real:", error.response?.data);
                }
            }, 500);
        }else{
            aeropuertosFiltradosDestino.value = [];
        }
    };

    const buscrTransportistas = async () => {
        clearTimeout(timeoutBusqueda);

        if (props.form.transportista_nombre.length >= 3) {
            timeoutBusqueda = setTimeout(async () => {
                try{
                    const res = await axios.get('transportista/buscar', {
                        params: { q: props.form.transportista_nombre }
                    });

                    tranportistasFiltrados.value = res.data;

                    const transportistaSeleccionado = tranportistasFiltrados.value.find(c => c.nom === props.form.transportista_nombre);

                    if (transportistaSeleccionado) {
                        props.form.transportista_id = transportistaSeleccionado.id;
                        console.log("ID del cliente guardado:", props.form.transportista_id);
                    } else {
                        props.form.transportista_id = null;
                    }

                }catch(error){
                    console.error("Error real:", error.response?.data);
                }
            }, 500);
        }else{
            tranportistasFiltrados.value = [];
        }
    };
</script>

<style lang="scss" scoped>

</style>
