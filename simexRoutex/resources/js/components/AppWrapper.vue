<template>
    <div class="flex min-h-screen w-full">
        <!-- 1. Menú Lateral: Escuchamos el evento 'cambiarVista' -->
        <MenuLateralComponent :vistaActual="vistaActual" @cambiarVista="actualizarVista" :user="user" />

        <!-- 2. Contenido Principal: Le pasamos la vista actual como Prop -->
        <div class="flex-1 flex flex-col">
            <CabeceraComponent
                :vistaActual="vistaActual"
                :user="user"
                @update:search="searchQuery = $event"
            />
            <div class="flex-1 p-6 bg-gray-100 overflow-y-auto">
                <HomeDashboard
                    v-if="vistaActual === 'dashboard' || vistaActual === 'nueva-oferta' || vistaActual === 'ver-oferta' || vistaActual === 'nueva-peticion'"
                    :mostrar="vistaActual" :key="dashboardKey" @cambiarVista="actualizarVista"
                    :ofertaSeleccionada="ofertaSeleccionada" @verDetalle="abrirDetalle" :user="user" :filtro="searchQuery" />

                <IncotermsComponent v-if="vistaActual === 'incoterms'" :filtro="searchQuery" ></IncotermsComponent>
                <UsuariosComponent v-if="vistaActual === 'usuaris'" :filtro="searchQuery"></UsuariosComponent>
                <ComunicacionesComponent v-if="vistaActual === 'comunicaciones'"></ComunicacionesComponent>
                <SeguimientoController v-if="vistaActual === 'seguimiento'" :filtro="searchQuery"></SeguimientoController>
                <PerfilUsuarioComponent v-if="vistaActual === 'perfil'" :user="user" @actualizarUser="user = $event"/>
                <HistorialOfertasComponent v-if="vistaActual === 'historico'" />
            </div>
        </div>
        <ChatBotComponent />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import MenuLateralComponent from './componentesMenu/MenuLateralComponent.vue';
import HomeDashboard from './componentesHome/HomeDashboard.vue';
import IncotermsComponent from './componentesIncoterms/IncotermsComponent.vue';
import UsuariosComponent from './usuariosComponent/UsuariosComponent.vue';
import ComunicacionesComponent from './componentesComunicacion/ComunicacionesComponent.vue';
import SeguimientoController from './componentesTracks/SeguimientoController.vue';
import PerfilUsuarioComponent from './usuariosComponent/PerfilUsuarioComponent.vue';
import HistorialOfertasComponent from './componentesHome/HistorialOfertas.vue';
import ChatBotComponent from './componentesChatBot/ChatBotComponent.vue';
import CabeceraComponent from './componentesCabecera/CabeceraComponent.vue';



const user = ref(JSON.parse(localStorage.getItem('user')));

// Estado que controla qué se ve en pantalla
const vistaActual = ref(localStorage.getItem('ultimaVista') || 'dashboard');

const dashboardKey = ref(0);
// Función para cambiar la vista
const actualizarVista = (nuevaVista) => {
    vistaActual.value = nuevaVista;
    localStorage.setItem('ultimaVista', nuevaVista);

    if (nuevaVista === 'dashboard') {
        dashboardKey.value++;
    }
};

const ofertaSeleccionada = ref(null);

const abrirDetalle = (oferta) => {
    ofertaSeleccionada.value = oferta; // Guardamos los datos de la oferta clicada
    vistaActual.value = 'ver-oferta'; // Cambiamos la vista
};

const searchQuery = ref('');

</script>

<style lang="scss" scoped></style>
