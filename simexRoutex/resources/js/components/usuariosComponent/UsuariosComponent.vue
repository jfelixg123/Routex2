<template>
    <div class="p-8 bg-gray-50 min-h-screen">
    <!-- 1. Stats Superior (Tarjetas Blancas) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div v-for="stat in stats" :key="stat.label" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div :class="stat.bgIcon" class="p-4 rounded-xl text-white">
          <span class="text-2xl">{{ stat.icon }}</span>
        </div>
        <div>
          <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ stat.label }}</p>
          <div class="flex items-center gap-2">
            <p class="text-2xl font-bold text-slate-800">{{ stat.value }}</p>
            <span :class="stat.trend > 0 ? 'text-green-500' : 'text-red-500'" class="text-xs font-bold">
              {{ stat.trend > 0 ? '+' : '' }}{{ stat.trend }}%
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. Contenedor Principal (Tabla) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <!-- Filtros y botón de invitar -->
      <div class="flex justify-between items-center p-6 border-b border-gray-50">
        <div class="flex gap-4">
          <button v-for="tab in ['Todos los usuarios', 'Administradores', 'Empresas', 'Comerciales', 'Operadores Logísticos']" :key="tab"
                  @click="filtroActual = tab"
                  :class="filtroActual === tab ? 'text-slate-800 border-b-2 border-orange-500' : 'text-gray-400'"
                  class="pb-2 text-sm font-bold transition-all">
            {{ tab }}
          </button>
        </div>
        <button @click="abrirNuevo" class="bg-orange-100 text-orange-600 px-3 py-1 rounded-lg text-xs font-bold hover:bg-orange-200 transition">
          <span class="text-xl">+</span> Crear Usuario
        </button>
      </div>

      <!-- Tabla de Usuarios -->
      <table class="w-full text-left">
        <thead class="bg-gray-50/50 text-[10px] uppercase tracking-widest text-gray-400 font-bold">
          <tr>
            <th class="px-6 py-4">Usuario</th>
            <th class="px-6 py-4">Rol</th>
            <th class="px-6 py-4">Conectado</th>
            <th class="px-6 py-4">Última Conexión</th>
            <th class="px-6 py-4 text-right">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <tr v-for="user in usuariosFiltradosBuscador" :key="user.id" class="hover:bg-gray-50/80 transition-colors group">
                <td class="px-6 py-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                        <!-- Validación de existencia de nombre -->
                        {{ user.nom ? user.nom.charAt(0).toUpperCase() : '?' }}
                    </div>
                    <span class="text-sm font-bold text-slate-800">{{ user.nom || 'Sin nombre' }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase tracking-wider">
                        <!-- Revisa si es user.rol.rol o user.rol.nom según tu relación -->
                        {{ user.rol?.rol || 'Sin Rol' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    {{ user.correu }}
                </td>
                 <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                    <div :class="user.status == 1 ? 'bg-green-500' : 'bg-red-400'" class="w-2 h-2 rounded-full"></div>
                    <span class="text-xs font-bold text-slate-600">
                    {{ user.status == 1 ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <button @click="toggleMenu(user.id)" class="text-gray-300 hover:text-slate-600 p-2">•••</button>

                    <!-- Menú Desplegable -->
                    <div v-if="menuAbierto === user.id"
                        class="absolute right-10 w-40 bg-white border border-gray-100 rounded-xl shadow-xl z-20 py-1 overflow-hidden">
                        <button @click="abrirEditar(user)" class="w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-orange-50 font-bold">
                            ✏️ Editar
                        </button>
                        <button @click="eliminarUsuario(user.id)" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 font-bold">
                            🗑️ Eliminar
                        </button>
                    </div>

                </td>
            </tr>

            <!-- Estado vacío -->
            <tr v-if="usuariosFiltrados.length === 0">
                <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                    No se han encontrado usuarios en esta categoría.
                </td>
            </tr>
        </tbody>
      </table>
    </div>
    <ModalUsuarioComponent
        v-if="mostrarModal"
        :usuarioAEditar="usuarioSeleccionado"
        @cerrar="mostrarModal = false"
        @actualizado="cargarUsuarios"
    />
    <ConfiguracionEmpresasComponent></ConfiguracionEmpresasComponent>
  </div>
</template>

<script setup>
    import { ref, onMounted, computed } from 'vue';
    import axios from 'axios';
    import ModalUsuarioComponent from './ModalUsuarioComponent.vue';
    import ConfiguracionEmpresasComponent from '../componentesEmpresas/ConfiguracionEmpresasComponent.vue';

    const props = defineProps({
        filtro: {
            type: String,
            default: ''
        }
    });

    const usuarios = ref([]);

    const usuariosFiltradosBuscador = computed(() => {
        if (!usuarios.value) return [];

        // Primero: Filtrar por Categoría (Tabs)
        let lista = usuarios.value;
        if (filtroActual.value !== 'Todos los usuarios') {
            const rolesMap = {
                'Administradores': 4,
                'Empresas': 3,
                'Comerciales': 2,
                'Operadores Logísticos': 1
            };
            const idBuscado = rolesMap[filtroActual.value];
            lista = lista.filter(user => Number(user.rol_id) === idBuscado);
        }

        // Segundo: Filtrar por texto del buscador (Nombre o Email)
        if (props.filtro) {
            const f = props.filtro.toLowerCase();
            lista = lista.filter(user => {
                const nombre = (user.nom || '').toLowerCase();
                const correo = (user.correu || '').toLowerCase();
                return nombre.includes(f) || correo.includes(f);
            });
        }

        return lista;
    });

    const filtroActual = ref('Todos los usuarios');

    const stats = ref([
    { label: 'Total Users', value: '1,284', trend: 5.2, icon: '👥', bgIcon: 'bg-indigo-500' },
    { label: 'Active Now', value: '452', trend: -2.1, icon: '🔥', bgIcon: 'bg-orange-500' },
    { label: 'Pending Invites', value: '12', trend: 0.5, icon: '✉️', bgIcon: 'bg-purple-500' }
    ]);

    const cargarUsuarios = async () => {
        try {
            const res = await axios.get('usuaris');
            usuarios.value = res.data;
            console.log("Primer usuario:", res.data[0]);
        } catch (error) {
            console.error("Error al cargar usuarios:", error);
        }
    };

    const usuariosFiltrados = computed(() => {
    //devolvemos la lista completa
        if (!usuarios.value) return [];

        if (filtroActual.value === 'Todos los usuarios') {
            return usuarios.value;
        }

        // Mapeo de nombres de Tab a ids
        const rolesMap = {
            'Administradors': 4,
            'Empresas': 3,
            'Comerciales': 2,
            'Operadores Logísticos': 1
        };

        const idBuscado = rolesMap[filtroActual.value];
        // Filtramos el array comparando el rol_id del usuario
        //return usuarios.value.filter(user => user.rol_id === rolIdBuscado);
        return usuarios.value.filter(user => {
            return Number(user.rol_id) === idBuscado;
        });
    });

    const eliminarUsuario = async (id) => {
        if (confirm("¿Seguro que quieres eliminar este usuario?")) {
            try {
                await axios.delete(`usuaris/${id}`);
                cargarUsuarios(); // Recarga la lista
            } catch (error) {
                alert("No se puede eliminar el usuario (puede tener ofertas asociadas)");
            }
        }
    };

    const mostrarModal = ref(false);
    const usuarioSeleccionado = ref(null);
    const menuAbierto = ref(null);

    // Abrir tres puntos
    const toggleMenu = (id) => {
        menuAbierto.value = menuAbierto.value === id ? null : id;
    };

    // Abrir modal en modo crera
    const abrirNuevo = () => {
        usuarioSeleccionado.value = null;
        mostrarModal.value = true;
    };

    // Abrir modal en modo edit
    const abrirEditar = (user) => {
        usuarioSeleccionado.value = user; // Pasamos los datos del usuario clicado
        mostrarModal.value = true;
        menuAbierto.value = null;         // Cerramos el menú de tres puntos
    };

    onMounted(cargarUsuarios);
</script>

<style lang="scss" scoped>

</style>
