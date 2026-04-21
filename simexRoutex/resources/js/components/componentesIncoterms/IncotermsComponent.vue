<template>
    <div class="p-8 bg-gray-50 min-h-screen">

    <!-- 1. Header de la Sección -->
    <div class="flex justify-between items-start mb-8">
      <div>
        <h2 class="text-3xl font-bold text-slate-800">Incoterm</h2>
      </div>
      <div class="flex gap-3">
        <button @click="abrirNuevo" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2 transition shadow-lg shadow-orange-200">
          <span class="text-xl">+</span> Añadir Incoterm
        </button>
      </div>
    </div>

    <!-- 2. Filtros y Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="flex justify-between items-center p-4 border-b border-gray-50">
        <div class="flex gap-6">
          <button v-for="tab in ['Active Terms']" :key="tab"
                  class="pb-2 text-sm font-bold transition-colors"
                  :class="tab === 'Active Terms' ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-400 hover:text-gray-600'">
            {{ tab }}
          </button>
        </div>
        <div class="flex items-center gap-4">
          <div class="relative">
             <input type="text" placeholder="Filter records..." class="pl-10 pr-4 py-2 bg-gray-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-orange-400 outline-none w-64">
             <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
          </div>
        </div>
      </div>

      <!-- 3. Tabla de Incoterms -->
      <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50/50 text-[10px] uppercase tracking-widest text-gray-400 font-bold">
          <tr>
            <th class="px-6 py-4">Code</th>
            <th class="px-6 py-4">Description / Name</th>
            <th class="px-6 py-4 text-center">Transport Mode</th>
            <th class="px-6 py-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="inco in incoterms" :key="inco.id" class="hover:bg-gray-50/80 transition-colors group">
            <td class="px-6 py-5 font-bold text-slate-800 text-lg">{{ inco.tipos_incoterm?.codi }}</td>
            <td class="px-6 py-5">
              <p class="font-bold text-slate-700 text-sm">{{ inco.tipos_incoterm?.nom }}</p>
              <p class="text-xs text-gray-400 mt-1">Seller makes goods available at their premises</p>
            </td>
            <td class="px-6 py-5 text-center">
              <span class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 rounded-full text-[10px] font-bold text-slate-600 uppercase">
                🚢 Sea/Waterway
              </span>
            </td>
            <td class="px-6 py-5 text-right relative">
                <!-- Botón de tres puntos -->
                <button
                    @click="toggleMenu(inco.id)"
                    class="text-gray-400 hover:text-orange-500 transition-colors p-1"
                >
                    <svg xmlns="http://w3.org" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                    </svg>
                </button>

                <!-- El Menú Desplegable (solo se ve si coincide el ID) -->
                <div v-if="menuAbierto === inco.id"
                    class="absolute right-10 top-12 w-40 bg-white border border-gray-100 rounded-xl shadow-xl z-20 overflow-hidden py-1 animate-in fade-in slide-in-from-top-2 duration-200">

                    <button @click="abrirEditar(inco)"
                            class="w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-orange-50 hover:text-orange-600 flex items-center gap-2 font-bold">
                    <span>✏️</span> Edit Term
                    </button>

                    <button @click="eliminarIncoterm(inco.id)"
                            class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 flex items-center gap-2 font-bold">
                    <span>🗑️</span> Delete
                    </button>
                </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- 4. (Resumen inferior) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <div v-for="stat in stats" :key="stat.label" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ stat.label }}</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ stat.value }}</p>
        </div>
    </div>
  </div>

  <ModalIncotermComponent
        v-if="mostrarModal"
        :incotermExistente="incotermAEditar"
        @cerrar="mostrarModal = false"
        @creado="recargarYcerrar"
    />
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';
    import ModalIncotermComponent from './ModalIncotermComponent.vue';

    const incoterms = ref([]);
    const mostrarModal = ref(false);

    const incotermAEditar = ref(null);

    const menuAbierto = ref(null);

    const stats = ref([
        { label: 'Total Terms', value: '11' },
        { label: 'Last Update', value: 'Oct 24' },
        { label: 'Version', value: '2020' },
        { label: 'Consistency', value: '100%' }
    ]);

    const cargarIncoterms = async () => {
    try {
        const res = await axios.get('incoterms');
        incoterms.value = res.data;
    } catch (error) {
        console.error(error);
    }
    };

    const recargarYcerrar = () => {
        cargarIncoterms(); // Función que ya tenemos para traer la lista
    };

    const toggleMenu = (id) => {
    // Si ya está abierto el mismo, lo cerramos. Si no, abrimos el nuevo.
    menuAbierto.value = menuAbierto.value === id ? null : id;
    };

    const eliminarIncoterm = async (id) => {
        if (confirm("¿Estás seguro de que quieres eliminar este Incoterm? Esto borrará también su código asociado.")) {
            try {
                const res = await axios.delete(`incoterms/${id}`);

                if (res.status === 200) {
                    // Recargamos la lista para que desaparezca de la tabla
                    cargarIncoterms();
                    alert("Eliminado con éxito");
                }
            } catch (error) {
                console.error("Error al eliminar:", error);
                alert("No se puede eliminar: es posible que este Incoterm esté siendo usado en una oferta.");
            }
        }
    }

    const abrirNuevo = () => {
        incotermAEditar.value = null; // Limpiamos la selección
        mostrarModal.value = true;
    };

    const abrirEditar = (inco) => {
        incotermAEditar.value = inco; // Guardamos el objeto que clicamos
        mostrarModal.value = true;    // Abrimos el modal
        menuAbierto.value = null;     // Cerramos el menú de los tres puntos
    };

    onMounted(cargarIncoterms);
</script>

<style lang="scss" scoped>

</style>
