<template>
    <h2 class="text-3xl font-bold text-gray-800 mb-2">Nueva Oferta Comercial</h2>
            <p class="text-gray-500 mb-8">Configure los términos comerciales y condiciones de transporte.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Fila 1 -->
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Concepto Oferta</label>
                <input v-model="form.concepto" type="text" placeholder="Ej: OFR-2024-001" class="border rounded-lg p-2 outline-none focus:ring-2 focus:ring-orange-400">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Cliente (Customer)</label>
                <input
                    list="clientesList"
                    v-model="form.cliente_nombre"
                    @input="buscarCliente"
                    placeholder="Escriba para buscar..."
                    class="border rounded-lg p-2 outline-none focus:ring-2 focus:ring-orange-400"
                >
                <datalist id="clientesList">
                    <option v-for="c in clientesFiltrados" :key="c.id" :value="c.nom"></option>
                </datalist>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Estado</label>
                <select v-model="form.estat_oferta_id" class="border rounded-lg p-2 outline-none">
                    <option v-for="e in estados" :key="e.id" :value="e.id">{{ e.estat }}</option>
                </select>
            </div>

            <!-- Fila 2: Fechas -->
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Fecha Oferta</label>
                <input v-model="form.data_creacio" type="date" class="border rounded-lg p-2 outline-none">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Válida desde</label>
                <input v-model="form.valid_desde" type="date" class="border rounded-lg p-2 outline-none">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Válida hasta</label>
                <input v-model="form.valid_fins" type="date" class="border rounded-lg p-2 outline-none">
            </div>

            <!-- Fila 3: Términos -->
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Divisa (Currency)</label>
                <select v-model="form.divisas_id" class="border rounded-lg p-2 outline-none">
                    <option v-for="e in divisas" :key="e.id" :value="e.id">{{ e.tipus }}</option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Incoterm</label>
                <select v-model="form.incoterm_id" class="border rounded-lg p-2 outline-none">
                    <option value="">Seleccionar...</option>
                    <option v-for="i in incoterms" :key="i.id" :value="i.id">
                    {{ i.tipos_incoterm?.codi }} - {{ i.tipos_incoterm?.nom }}
                    </option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Vendedor (Sales Rep)</label>
                <input
                    list="vendedoresList"
                    v-model="form.vendedor_nombre"
                    @input="buscarComercial"
                    placeholder="Buscar vendedor..."
                    class="border rounded-lg p-2 outline-none"
                >
                <datalist id="vendedoresList">
                    <option v-for="v in comercialesFiltrados" :key="v.id" :value="v.nom"></option>
                </datalist>
            </div>
            </div>
</template>

<script setup>
    import { ref, reactive } from 'vue';

    const props = defineProps(['form', 'estados', 'divisas', 'incoterms']);
    const clientesFiltrados = ref([]);
    const comercialesFiltrados = ref([]);

    let timeoutBusqueda = null;

    const buscarCliente = async () => {
        clearTimeout(timeoutBusqueda);

        if (props.form.cliente_nombre.length >= 3) {
            timeoutBusqueda = setTimeout(async () => {
                try{
                    const res = await axios.get('usuari/buscar', {
                        params: { q: props.form.cliente_nombre }
                    });

                    clientesFiltrados.value = res.data;

                    const clienteSeleccionado = clientesFiltrados.value.find(c => c.nom === props.form.cliente_nombre);

                    if (clienteSeleccionado) {
                        props.form.cliente_id = clienteSeleccionado.id;
                        props.form.cliente_apellidos = clienteSeleccionado.cognoms;
                        props.form.cliente_tlfn = clienteSeleccionado.tlfn;
                        props.form.cliente_correu = clienteSeleccionado.correu;
                        console.log("ID del cliente guardado:", props.form.cliente_id);
                    } else {
                        props.form.cliente_id = null;
                        props.form.cliente_apellidos = null;
                        props.form.cliente_tlfn = null;
                        props.form.cliente_correu = null;
                        console.log("ID del cliente guardado:", props.form.cliente_id);
                    }

                }catch(error){
                    console.error("Error real:", error.response?.data);
                }
            }, 500); //Pongo tiermpo para no saturar
        }else{
            clientesFiltrados.value = [];
        }
    };

    const buscarComercial = async () => {
        clearTimeout(timeoutBusqueda);

        if (props.form.vendedor_nombre.length >= 3) {
            timeoutBusqueda = setTimeout(async () => {
                try{
                    const res = await axios.get('comercial/buscar', {
                        params: { q: props.form.vendedor_nombre }
                    });

                    comercialesFiltrados.value = res.data;

                    const comercialSeleccionado = comercialesFiltrados.value.find(c => c.nom === props.form.vendedor_nombre);

                    if (comercialSeleccionado) {
                        props.form.vendedor_id = comercialSeleccionado.id;
                        console.log("ID del comercial guardado:", props.form.vendedor_id);
                    } else {
                        props.form.vendedor_id = null;
                    }
                }catch(error){
                    console.error("Error real:", error.response?.data);
                }
            }, 500); //Pongo tiermpo para no saturar
        }else{
            comercialesFiltrados.value = [];
        }
    };
</script>

<style lang="scss" scoped>

</style>
