<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <!-- Contenedor del Modal -->
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200">

      <!-- Cabecera -->
      <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div>
          <h3 class="text-xl font-bold text-slate-800">{{ nuevoInco.id ? 'Editar Incoterm' : 'Nuevo Incoterm' }}</h3>
          <p class="text-xs text-gray-400">Configuración de términos comerciales</p>
        </div>
        <button @click="$emit('cerrar')" class="text-gray-400 hover:text-gray-600 transition-colors text-2xl">&times;</button>
      </div>

      <!-- Formulario -->
      <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto">
        <!-- BLOQUE 1: IDENTIDAD -->
        <div class="grid grid-cols-3 gap-4">
          <div class="col-span-1 flex flex-col gap-1">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Código (3 letras)</label>
            <input v-model="nuevoInco.codi" type="text" maxlength="3" placeholder="EXW"
                   class="border border-gray-200 rounded-xl p-3 outline-none focus:ring-2 focus:ring-orange-400 transition uppercase font-bold text-center text-slate-700">
          </div>
          <div class="col-span-2 flex flex-col gap-1">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nombre Completo</label>
            <input v-model="nuevoInco.nom" type="text" placeholder="Ex Works"
                   class="border border-gray-200 rounded-xl p-3 outline-none focus:ring-2 focus:ring-orange-400 transition">
          </div>
        </div>

        <!-- NUEVO: CONFIGURAR HOJA DE RUTA COMPLETA -->
        <div class="mt-6 border-t pt-4">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2">Configurar Hoja de Ruta (Varios pasos)</label>
            <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-2 bg-gray-50 rounded-xl border">
                <div v-for="p in pasosMaestros" :key="p.id"
                    @click="gestionarPaso(p.id)"
                    :class="nuevoInco.pasosSeleccionados.includes(p.id) ? 'bg-orange-500 text-white border-orange-600' : 'bg-white text-slate-600 border-gray-200'"
                    class="p-2 rounded-lg border text-[10px] font-bold cursor-pointer transition-all flex justify-between items-center shadow-sm">
                    {{ p.nom }}
                    <span v-if="nuevoInco.pasosSeleccionados.includes(p.id)">✓</span>
                </div>
            </div>
            <p class="text-[9px] text-gray-400 mt-2 italic">* Los pasos se guardarán para el seguimiento de las ofertas.</p>
        </div>
      </div>

      <!-- Footer / Botones -->
      <div class="p-6 bg-gray-50 flex gap-3 border-t">
        <button @click="$emit('cerrar')" class="flex-1 px-4 py-3 text-gray-500 font-bold hover:bg-gray-100 rounded-2xl transition">
          Cancelar
        </button>
        <button @click="guardar"
                :disabled="!nuevoInco.codi || nuevoInco.pasosSeleccionados.length === 0"
                class="flex-1 px-4 py-3 bg-orange-500 text-white font-bold rounded-2xl hover:bg-orange-600 transition shadow-lg shadow-orange-200 disabled:opacity-50 disabled:shadow-none">
          {{ nuevoInco.id ? 'Actualizar' : 'Guardar' }} Incoterm
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
    import { ref, reactive, onMounted } from 'vue';
    import axios from 'axios';

    const emit = defineEmits(['cerrar', 'creado']);
    const props = defineProps(['incotermExistente']);

    const pasosMaestros = ref([]);

    const nuevoInco = reactive({
        id: null,
        codi: '',
        nom: '',
        tipus_inconterm_id: null,
        tracking_steps_id: '',
        pasosSeleccionados: []
    });

    const cargarMaestros = async () => {
        try {
            // Asegúrate de que el endpoint sea 'trackingSteps' como en tus rutas
            const res = await axios.get('trackingSteps');
            pasosMaestros.value = res.data;
        } catch (error) {
            console.error("Error cargando pasos maestros:", error);
        }
    };

    onMounted(async () => {
        await cargarMaestros();

        if (props.incotermExistente) {
            const i = props.incotermExistente;
            nuevoInco.id = i.id;
            nuevoInco.codi = i.tipos_incoterm?.codi || '';
            nuevoInco.nom = i.tipos_incoterm?.nom || '';
            nuevoInco.tracking_steps_id = i.tracking_steps_id;
            nuevoInco.tipus_inconterm_id = i.tipus_inconterm_id;
            // Si ya tiene pasos asociados en la intermedia, deberías cargarlos aquí
            // nuevoInco.pasosSeleccionados = i.pasos_asociados.map(p => p.tracking_step_id);
        }
    });

    const gestionarPaso = (pasoId) => {
        const index = nuevoInco.pasosSeleccionados.indexOf(pasoId);
        if (index > -1) {
            nuevoInco.pasosSeleccionados.splice(index, 1);
        } else {
            nuevoInco.pasosSeleccionados.push(pasoId);
        }
    };

    const guardar = async () => {
        if (!nuevoInco.codi || nuevoInco.pasosSeleccionados.length === 0) {
            alert("Por favor, introduce un código y selecciona al menos un paso de la hoja de ruta.");
            return;
        }
        try {
            nuevoInco.tracking_steps_id = nuevoInco.pasosSeleccionados[0];

            let res;
            // 3. Preparamos los datos para enviar (clonamos para no ensuciar el reactive)
            const datosAEnviar = { ...nuevoInco };

            if (nuevoInco.id) {
                // EDITAR
                res = await axios.put(`incoterms/${nuevoInco.id}`, datosAEnviar);
            } else {
                // CREAR NUEVO
                res = await axios.post('incoterms', datosAEnviar);
            }

            if (res.status === 201 || res.status === 200) {
                emit('creado');
                emit('cerrar');
            }
        } catch (error) {
            console.error("Error al guardar:", error);
            alert("Error al procesar la solicitud. Revisa la consola.");
        }
    };
</script>

<style lang="scss" scoped>

</style>
