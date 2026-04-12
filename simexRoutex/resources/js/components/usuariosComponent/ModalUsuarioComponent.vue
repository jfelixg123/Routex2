<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in duration-200">

        <!-- Cabecera -->
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-xl font-bold text-slate-800">{{ form.id ? 'Editar Usuario' : 'Nuevo Usuario' }}</h3>
            <button @click="$emit('cerrar')" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>

        <!-- Formulario -->
        <div class="p-8 grid grid-cols-2 gap-4">
            <div class="flex flex-col gap-1">
            <label class="text-[10px] font-bold text-gray-400 uppercase">Nombre</label>
            <input v-model="form.nom" type="text" class="border rounded-xl p-3 outline-none focus:ring-2 focus:ring-orange-400">
            </div>
            <div class="flex flex-col gap-1">
            <label class="text-[10px] font-bold text-gray-400 uppercase">Apellidos</label>
            <input v-model="form.cognoms" type="text" class="border rounded-xl p-3 outline-none focus:ring-2 focus:ring-orange-400">
            </div>
            <div class="flex flex-col gap-1">
            <label class="text-[10px] font-bold text-gray-400 uppercase">Email</label>
            <input v-model="form.correu" autocomplete="none" type="email" class="border rounded-xl p-3 outline-none focus:ring-2 focus:ring-orange-400">
            </div>
            <div class="flex flex-col gap-1">
            <label class="text-[10px] font-bold text-gray-400 uppercase">Teléfono</label>
            <input v-model="form.tlfn" autocomplete="none" type="text" class="border rounded-xl p-3 outline-none focus:ring-2 focus:ring-orange-400">
            </div>

            <!-- Selectores de Maestros -->
            <div class="flex flex-col gap-1">
            <label class="text-[10px] font-bold text-gray-400 uppercase">Rol</label>
            <select v-model="form.rol_id" class="border rounded-xl p-3 bg-gray-50">
                <option v-for="r in roles" :key="r.id" :value="r.id">{{ r.rol }}</option>
            </select>
            </div>
            <div class="flex flex-col gap-1">
            <label class="text-[10px] font-bold text-gray-400 uppercase">Empresa</label>
            <select v-model="form.company_id" class="border rounded-xl p-3 bg-gray-50">
                <option v-for="c in empresas" :key="c.id" :value="c.id">{{ c.company_name }}</option>
            </select>
            </div>

            <div v-if="!form.id" class="flex flex-col gap-1 col-span-2 border-t pt-4 mt-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase">Contraseña Inicial</label>
                <input v-model="form.contrasenya" autocomplete="new-password" type="password" class="border rounded-xl p-3 outline-none focus:ring-2 focus:ring-orange-400">
            </div>
        </div>

        <!-- Footer -->
        <div class="p-6 bg-gray-50 flex gap-3">
            <button @click="$emit('cerrar')" class="flex-1 py-3 text-gray-500 font-bold">Cancelar</button>
            <button @click="guardar" class="flex-1 py-3 bg-orange-500 text-white rounded-2xl font-bold hover:bg-orange-600 shadow-lg shadow-orange-200">
            {{ form.id ? 'Guardar Cambios' : 'Crear Usuario' }}
            </button>
        </div>
        </div>
    </div>
</template>

<script setup>
    import { reactive, onMounted, ref } from 'vue';
    import axios from 'axios';

    const props = defineProps(['usuarioAEditar']);
    const emit = defineEmits(['cerrar', 'actualizado']);

    const roles = ref([]);
    const empresas = ref([]);

    const form = reactive({
        id: null,
        nom: '',
        cognoms: '',
        correu: '',
        tlfn: '',
        rol_id: '',
        company_id: '',
        contrasenya: '',
        status: 1
    });

    const limpiarFormulario = () => {
        form.id = null;
        form.nom = '';
        form.cognoms = '';
        form.correu = '';
        form.tlfn = '';
        form.rol_id = '';
        form.company_id = '';
        form.contrasenya = '';
        form.status = 1;
    };

    onMounted(async () => {
        const [resRoles, resEmp] = await Promise.all([
            axios.get('rols'),
            axios.get('company')
        ]);

        roles.value = resRoles.data;
        empresas.value = resEmp.data;

        // Si editamos, mapeamos los datos
        if (props.usuarioAEditar) {
            Object.assign(form, props.usuarioAEditar);
        }else {
            limpiarFormulario();
        }
    });

    const guardar = async () => {
        try {
            if (form.id) {
            await axios.put(`usuaris/${form.id}`, form);
            } else {
            await axios.post('usuaris', form);
            }
            emit('actualizado');
            emit('cerrar');
        } catch (error) {
            alert("Error al guardar usuario");
        }
    };
</script>

<style lang="scss" scoped>

</style>
