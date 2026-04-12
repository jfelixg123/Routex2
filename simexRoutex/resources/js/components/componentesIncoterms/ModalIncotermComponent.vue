<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">

    <!-- Contenedor del Modal -->
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200">

      <!-- Cabecera -->
      <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div>
          <h3 class="text-xl font-bold text-slate-800">Nuevo Incoterm</h3>
          <p class="text-xs text-gray-400">Configuración de términos comerciales</p>
        </div>
        <button @click="$emit('cerrar')" class="text-gray-400 hover:text-gray-600 transition-colors">
          <svg xmlns="http://w3.org" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Formulario -->
      <div class="p-8 space-y-6">

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

        <!-- BLOQUE 2: OPERATIVA (Tabla Azul) -->
        <div class="flex flex-col gap-1">
          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Paso de Seguimiento Inicial</label>
          <div class="relative">
            <select v-model="nuevoInco.tracking_steps_id" class="w-full border border-gray-200 rounded-xl p-3 outline-none focus:ring-2 focus:ring-orange-400 bg-gray-50 appearance-none">
              <option value="">Selecciona un paso...</option>
              <option v-for="p in pasos" :key="p.id" :value="p.id">
                {{ p.nom }} (Orden: {{ p.ordre }})
              </option>
            </select>
            <span class="absolute right-3 top-3.5 text-gray-400 pointer-events-none">▼</span>
          </div>
        </div>

      </div>

      <!-- Footer / Botones -->
      <div class="p-6 bg-gray-50 flex gap-3">
        <button @click="$emit('cerrar')" class="flex-1 px-4 py-3 text-gray-500 font-bold hover:bg-gray-100 rounded-2xl transition">
          Cancelar
        </button>
        <button @click="guardar"
                :disabled="!nuevoInco.codi || !nuevoInco.tracking_steps_id"
                class="flex-1 px-4 py-3 bg-orange-500 text-white font-bold rounded-2xl hover:bg-orange-600 transition shadow-lg shadow-orange-200 disabled:opacity-50 disabled:shadow-none">
          Guardar Incoterm
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

    // Datos Maestros
    const pasos = ref([]);
    const transportes = ref([]);

    // Estado del formulario
    const nuevoInco = reactive({
    codi: '',
    nom: '',
    tracking_steps_id: '',
    });

    const cargarMaestros = async () => {
    try {
        const [resPasos] = await Promise.all([
        axios.get('trackingSteps'),
        ]);
        pasos.value = resPasos.data;
        transportes.value = resTrans.data;
    } catch (error) {
        console.error("Error cargando datos maestros:", error);
    }
    };

    onMounted(async() =>{
        await cargarMaestros();

        if (props.incotermExistente) {
            const i = props.incotermExistente;
            nuevoInco.id = i.id;
            nuevoInco.codi = i.tipos_incoterm?.codi || '';
            nuevoInco.nom = i.tipos_incoterm?.nom || '';
            nuevoInco.tracking_steps_id = i.tracking_steps_id;
            nuevoInco.tipus_inconterm_id = i.tipus_inconterm_id;
        }
    });

    const guardar = async () => {
        console.log("Datos a enviar:", nuevoInco);
    try {
        if (nuevoInco.id) {
            const res = await axios.put(`incoterms/${nuevoInco.id}`, nuevoInco);
            if (res.status === 201 || res.status === 200) {
                emit('creado');
                emit('cerrar');
            }
        }else{
            const res = await axios.post('incoterms', nuevoInco);
            if (res.status === 201 || res.status === 200) {
                emit('creado');
                emit('cerrar');
            }

        }
    } catch (error) {
        console.error("Error al guardar:", error);
        alert("No se pudo guardar el Incoterm. Revisa los datos.");
    }
    };
</script>

<style lang="scss" scoped>

</style>
