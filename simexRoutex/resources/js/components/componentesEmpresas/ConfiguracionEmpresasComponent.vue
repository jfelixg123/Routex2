<template>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-12">

    <!-- TABLA DE COMPANIES -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <h3 class="font-bold text-slate-800">Empresas</h3>
        <button @click="abrirModal('company')" class="bg-orange-100 text-orange-600 px-3 py-1 rounded-lg text-xs font-bold hover:bg-orange-200 transition">+ Añadir</button>
      </div>
      <table class="w-full text-left">
        <thead class="bg-gray-50/50 text-[10px] uppercase font-bold text-gray-400">
          <tr>
            <th class="px-6 py-3">Nombre</th>
            <th class="px-6 py-3">Industria</th>
            <th class="px-6 py-3 text-right">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="comp in companies" :key="comp.id" class="hover:bg-gray-50/50 transition-colors">
            <td class="px-6 py-4 font-bold text-slate-700 text-sm">{{ comp.company_name }}</td>
            <td class="px-6 py-4 text-xs text-gray-500">{{ comp.industria?.categoria || 'Sin categoría' }}</td>
            <td class="px-6 py-4 text-right space-x-2">
              <button @click="abrirModal('company', comp)" class="text-slate-400 hover:text-blue-500">✏️</button>
              <button @click="eliminar('company', comp.id)" class="text-slate-400 hover:text-red-500">🗑️</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- TABLA DE INDUSTRIAS -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <h3 class="font-bold text-slate-800">Sectores / Industrias</h3>
        <button @click="abrirModal('industria')" class="bg-orange-100 text-orange-600 px-3 py-1 rounded-lg text-xs font-bold hover:bg-orange-200 transition">+ Añadir</button>
      </div>
      <table class="w-full text-left">
        <thead class="bg-gray-50/50 text-[10px] uppercase font-bold text-gray-400">
          <tr>
            <th class="px-6 py-3">Categoría</th>
            <th class="px-6 py-3 text-right">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="ind in industries" :key="ind.id" class="hover:bg-gray-50/50 transition-colors">
            <td class="px-6 py-4 font-bold text-slate-700 text-sm">{{ ind.categoria }}</td>
            <td class="px-6 py-4 text-right space-x-2">
              <button @click="abrirModal('industria', ind)" class="text-slate-400 hover:text-blue-500">✏️</button>
              <button @click="eliminar('industria', ind.id)" class="text-slate-400 hover:text-red-500">🗑️</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- MINI MODAL ÚNICO PARA AMBOS -->
    <div v-if="modal.abierto" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
        <div class="p-4 border-b font-bold text-slate-800 flex justify-between">
          {{ modal.modo === 'edit' ? 'Editar' : 'Añadir' }} {{ modal.tipo === 'company' ? 'Empresa' : 'Industria' }}
          <button @click="modal.abierto = false">&times;</button>
        </div>
        <div class="p-6 space-y-4">
          <!-- Campo Nombre (Para ambos) -->
          <div class="flex flex-col gap-1">
            <label class="text-[10px] font-bold text-gray-400 uppercase">
              {{ modal.tipo === 'company' ? 'Nombre Empresa' : 'Nombre Categoría' }}
            </label>
            <input v-model="modal.form.nombre" type="text" class="border rounded-xl p-2.5 outline-none focus:ring-2 focus:ring-orange-400">
          </div>
          <!-- Selector de Industria (Solo para Company) -->
          <div v-if="modal.tipo === 'company'" class="flex flex-col gap-1">
            <label class="text-[10px] font-bold text-gray-400 uppercase">Sector</label>
            <select v-model="modal.form.industria_id" class="border rounded-xl p-2.5 bg-gray-50">
              <option v-for="ind in industries" :key="ind.id" :value="ind.id">{{ ind.categoria }}</option>
            </select>
          </div>
        </div>
        <div class="p-4 bg-gray-50 flex gap-2">
          <button @click="modal.abierto = false" class="flex-1 py-2 text-gray-500 text-sm font-bold">Cancelar</button>
          <button @click="guardar" class="flex-1 py-2 bg-orange-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-orange-100">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
    import { ref, reactive, onMounted } from 'vue';
    import axios from 'axios';

    const companies = ref([]);
    const industries = ref([]);

    // Gestión del Mini Modal
    const modal = reactive({
    abierto: false,
    tipo: '', // 'company' o 'industria'
    modo: '', // 'add' o 'edit'
    form: { id: null, nombre: '', industria_id: '' }
    });

    const cargarDatos = async () => {
    const [resComp, resInd] = await Promise.all([
        axios.get('company'),
        axios.get('industries')
    ]);
    companies.value = resComp.data;
    industries.value = resInd.data;
    };

    const abrirModal = (tipo, objeto = null) => {
    modal.tipo = tipo;
    modal.modo = objeto ? 'edit' : 'add';
    modal.form = {
        id: objeto?.id || null,
        nombre: tipo === 'company' ? (objeto?.company_name || '') : (objeto?.categoria || ''),
        industria_id: objeto?.industria_id || ''
    };
    modal.abierto = true;
    };

    const guardar = async () => {
        const url = modal.tipo === 'company' ? 'company' : 'industries';

        const data = modal.tipo === 'company'
            ? { company_name: modal.form.nombre, industria_id: modal.form.industria_id }
            : { categoria: modal.form.nombre };

        try {
            if (modal.modo === 'edit') {
            await axios.put(`${url}/${modal.form.id}`, data);
            } else {
            await axios.post(url, data);
            }
            cargarDatos();
            modal.abierto = false;
        } catch (e) { alert("Error al guardar"); }
        };

        const eliminar = async (tipo, id) => {
            if (!confirm("¿Eliminar este registro?")) return;
            const url = tipo === 'company' ? 'company' : 'industries';
            await axios.delete(`${url}/${id}`);
            cargarDatos();
        };

    onMounted(cargarDatos);
</script>

<style lang="scss" scoped>

</style>
