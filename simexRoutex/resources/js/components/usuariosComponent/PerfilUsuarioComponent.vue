<template>
    <div v-if="user" class="p-6">

        <h1 class="text-2xl font-bold mb-4">Perfil de Usuario</h1>

        <div class="bg-white p-6 rounded shadow space-y-4 mb-6">

            <div>
                <label>Nombre</label>
                <input v-model="form.nom" class="input" />
            </div>

            <div>
                <label>Apellidos</label>
                <input v-model="form.cognoms" class="input" />
            </div>

            <div>
                <label>Email</label>
                <input v-model="form.correu" class="input" />
            </div>

            <div>
                <label>Teléfono</label>
                <input v-model="form.tlfn" class="input" />
            </div>

            <!-- BOTONES -->
            <div class="flex gap-4 mt-4">

                <button @click="guardar" class="bg-orange-500 text-white px-4 py-2 rounded">
                    Guardar cambios
                </button>

                <button @click="logout" class="bg-gray-400 text-white px-4 py-2 rounded">
                    Cerrar sesión
                </button>

            </div>

        </div>

        <div class="bg-white p-6 rounded shadow space-y-4">

            <h2 class="text-xl font-semibold">Cambiar contraseña</h2>

            <input v-model="passwordForm.current_password" type="password" placeholder="Contraseña actual"
                class="input" />

            <input v-model="passwordForm.new_password" type="password" placeholder="Nueva contraseña" class="input" />

            <input v-model="passwordForm.new_password_confirmation" type="password" placeholder="Confirmar contraseña"
                class="input" />

            <button @click="cambiarPassword" class="bg-orange-500 text-white px-4 py-2 rounded">
                Cambiar contraseña
            </button>

        </div>

    </div>
</template>

<style scoped>
.input {
    width: 100%;
    border: 1px solid #ccc;
    padding: 8px;
    border-radius: 6px;
}
</style>

<script setup>
import { reactive, watch } from 'vue';
const emit = defineEmits(['actualizarUser']);
import axios from 'axios';

const props = defineProps({
    user: Object
});


const form = reactive({
    nom: '',
    cognoms: '',
    correu: '',
    tlfn: ''
});

const passwordForm = reactive({
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
});


watch(() => props.user, (newUser) => {
    if (newUser) {
        form.nom = newUser.nom || '';
        form.cognoms = newUser.cognoms || '';
        form.correu = newUser.correu || '';
        form.tlfn = newUser.tlfn || '';
    }
}, { immediate: true });


const guardar = async () => {
    try {
        const response =  await axios.put('/perfil', form);

        alert('Perfil actualizado ✅');

        emit('actualizarUser', response.data.user);

    } catch (error) {
        console.log(error);
    }
};


const cambiarPassword = async () => {
    try {
        await axios.post('/change-password', passwordForm);

        passwordForm.current_password = '';
        passwordForm.new_password = '';
        passwordForm.new_password_confirmation = '';

    } catch (error) {
        alert(error.response?.data?.error || 'Error al cambiar contraseña');
    }
};

const logout = async () => {
    try{
        await axios.post('/logout')
    }catch (error) {
        alert(error.response?.data?.error || 'No se ha podido hacer el logout');
    }
};
</script>
