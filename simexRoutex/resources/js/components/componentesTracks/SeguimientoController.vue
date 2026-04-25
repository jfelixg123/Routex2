<template>
    <div class="p-8 flex gap-6 h-[calc(100vh-120px)] overflow-hidden">

    <!-- LISTA DE OFERTAS ACTIVAS -->
    <div class="flex-1 bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
      <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex-shrink-0">
        <h2 class="text-xl font-bold text-slate-800 font-mono italic">CONTROL DE TRÁFICO</h2>
      </div>
      <div class="flex-1 overflow-y-auto custom-scroll">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-[10px] uppercase font-bold text-gray-400 tracking-widest">
            <tr>
                <th class="px-6 py-4">Oferta ID</th>
                <th class="px-6 py-4">Cliente</th>
                <th class="px-6 py-4">Ruta</th>
                <th class="px-6 py-4">Estado</th>
                <th class="px-6 py-4">Acción</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            <tr v-for="o in ofertasFiltradas" :key="o.id" class="hover:bg-orange-50/30 transition-colors">
                <td class="px-6 py-4 font-bold text-slate-700">#{{ o.id }}</td>
                <td class="px-6 py-4 text-sm">{{ o.usuari?.nom }}</td>
                <td class="px-6 py-4 text-xs text-gray-500">
                {{ o.port_origen?.nom || 'Origen' }} ➔ {{ o.port_desti?.nom || 'Destino' }}
                </td>
                <td class="px-6 py-4">
                <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-[10px] font-bold uppercase">
                    {{ o.estats_ofertes?.estat }}
                </span>
                </td>
                <td class="px-6 py-4">
                <button @click="verPasos(o)" class="text-orange-500 font-bold text-sm hover:underline">Gestionar Pasos</button>
                </td>
            </tr>
            </tbody>
        </table>
      </div>
    </div>

    <!-- PANEL LATERAL DE PASOS -->
    <div v-if="ofertaSeleccionada" class="w-120 bg-white rounded-3xl shadow-2xl border border-orange-100 flex flex-col overflow-hidden animate-in slide-in-from-right duration-300">
      <div class="flex justify-between items-center p-6">
        <h3 class="font-bold text-slate-800">Seguimiento #{{ ofertaSeleccionada.id }}</h3>
        <button @click="ofertaSeleccionada = null" class="text-gray-400 text-2xl">&times;</button>
      </div>

      <!-- LINEA DE TIEMPO -->
      <div class="flex-1 overflow-y-auto p-6 custom-scroll">
        <div class="relative space-y-6 before:absolute before:inset-0 before:ml-4 before:-translate-x-px before:h-full before:w-0.5 before:bg-gray-100">
            <div v-for="p in pasos" :key="p.id" class="relative flex items-start gap-4 pb-4">

                <!-- 1. Indicador (Check) -->
                <div @click="togglePaso(p)"
                    :class="p.esta_completado == 1 ? 'bg-orange-500 border-orange-500 shadow-orange-200' : 'bg-white border-gray-300'"
                    class="z-10 flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full border-2 cursor-pointer transition-all shadow-md mt-1">
                <span v-if="p.esta_completado" class="text-white text-[10px]">✓</span>
                </div>

                <!-- 2. Contenido (Texto + Botón) alineado en Grid -->
                <div class="flex-1 grid grid-cols-12 gap-4 items-center bg-gray-50/50 p-4 rounded-2xl border border-gray-100">

                <!-- Lado Izquierdo: Texto del paso -->
                <div class="col-span-7 flex flex-col justify-center">
                    <span :class="p.esta_completado == 1 ? 'text-slate-800 font-bold' : 'text-gray-400'" class="text-sm">
                    {{ p.step?.nom }}
                    </span>
                    <span v-if="p.fecha_completado" class="text-[9px] text-orange-400 font-mono mt-1">
                    {{ new Date(p.fecha_completado).toLocaleString() }}
                    </span>
                </div>

                <!-- Lado Derecho: Zona de Documentos (Ancho fijo y Cuadrada) -->
                <div class="col-span-5 flex flex-col gap-2">
                    <!-- Indicador de que ya hay archivo -->
                    <div v-if="p.documento_path" class="flex items-center justify-between bg-white px-2 py-1 rounded border border-blue-100">
                        <span class="text-[8px] font-bold text-blue-600 truncate uppercase">Archivo OK</span>
                        <a :href="'http://localhost:8080/storage/' + p.documento_path" target="_blank" class="text-[8px] text-blue-500 underline font-bold">VER</a>
                    </div>

                    <!-- Botón Cuadrado -->
                    <label class="cursor-pointer flex items-center justify-center h-10 w-full bg-white border border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-all group">
                        <span class="text-[9px] font-bold text-gray-400 group-hover:text-orange-600 uppercase text-center px-2">
                            {{ p.documento_path ? 'Cambiar' : 'Subir Doc' }}
                        </span>
                        <input type="file" class="hidden" @change="subeArchivo($event, p.id)">
                    </label>

                    <button v-if="p.documento_path"
                            @click="borrarArchivo(p.id)"
                            class="w-full py-1 text-[8px] font-bold text-red-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors uppercase tracking-tighter">
                        Eliminar Archivo Actual
                    </button>
                </div>

                </div>
            </div>
        </div>
      </div>
  </div>
</div>
</template>

<script setup>
    import { ref, onMounted, computed } from 'vue';
    import axios from 'axios';

    const props = defineProps({
        filtro: {
            type: String,
            default: ''
        }
    });

    const ofertas = ref([]);

    const ofertasFiltradas = computed(() => {
        if (!props.filtro) return ofertas.value;

        const f = props.filtro.toLowerCase();
        return ofertas.value.filter(o => {
            const id = String(o.id);
            const cliente = (o.usuari?.nom || '').toLowerCase();
            const origen = (o.port_origen?.nom || '').toLowerCase();
            const destino = (o.port_desti?.nom || '').toLowerCase();

            return id.includes(f) ||
                   cliente.includes(f) ||
                   origen.includes(f) ||
                   destino.includes(f);
        });
    });

    const ofertaSeleccionada = ref(null);
    const pasos = ref([]);

    const cargarOfertasActivas = async () => {
    const res = await axios.get('trafico');
    ofertas.value = res.data;
    };

    const verPasos = async (oferta) => {
    ofertaSeleccionada.value = oferta;
    const res = await axios.get(`trafico/${oferta.id}/pasos`);
    pasos.value = res.data;
    };

    const togglePaso = async (paso) => {
    // Cambiamos el estado localmente para respuesta instantánea
    paso.esta_completado = !paso.esta_completado;
    try {
        await axios.post(`trafico/check/${paso.id}`, { completado: paso.esta_completado });
        // Recargamos para traer la fecha_completado del servidor
        const res = await axios.get(`trafico/${ofertaSeleccionada.value.id}/pasos`);
        pasos.value = res.data;
    } catch (e) {
        paso.esta_completado = !paso.esta_completado; // Revertir si falla
    }
    };

    const subeArchivo = async (event, seguimientoId) => {
        const file = event.target.files[0];
        const formData = new FormData();
        formData.append('documento', file);

        try {
            await axios.post(`trafico/subir-doc/${seguimientoId}`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            // En lugar de resetear todo, solo actualizamos los datos de los pasos
            const res = await axios.get(`trafico/${ofertaSeleccionada.value.id}/pasos`);
            pasos.value = res.data;

            alert("Documento actualizado correctamente");
        } catch (e) {
            console.error(e.response.data.errors);
            alert("Error al subir");
        }
    };

    const borrarArchivo = async (seguimientoId) => {
        if (!confirm("¿Seguro que quieres eliminar este documento?")) return;

        try {
            await axios.delete(`trafico/borrar-doc/${seguimientoId}`);

            // Actualizamos la lista de pasos para que desaparezca el documento de la vista
            const res = await axios.get(`trafico/${ofertaSeleccionada.value.id}/pasos`);
            pasos.value = res.data;

            alert("Documento eliminado correctamente");
        } catch (e) {
            console.error("Error al borrar:", e);
            alert("No se pudo eliminar el archivo");
        }
    };

    onMounted(cargarOfertasActivas);
</script>

<style lang="scss" scoped>

</style>
