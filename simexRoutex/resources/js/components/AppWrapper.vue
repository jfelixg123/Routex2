<template>
    <div class="flex min-h-screen w-full">
        <!-- 1. Menú Lateral: Escuchamos el evento 'cambiarVista' -->
        <MenuLateralComponent :vistaActual="vistaActual" @cambiarVista="actualizarVista" />

        <!-- 2. Contenido Principal: Le pasamos la vista actual como Prop -->
        <div class="flex-1 flex flex-col">
        <HomeDashboard :mostrar="vistaActual" @cambiarVista="actualizarVista"  />
        </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import MenuLateralComponent from './componentesMenu/MenuLateralComponent.vue';
    import HomeDashboard from './componentesHome/HomeDashboard.vue';

    // Estado que controla qué se ve en pantalla
    const vistaActual = ref('dashboard');

    // Función para cambiar la vista
    const actualizarVista = (nuevaVista) => {
    vistaActual.value = nuevaVista;
    };
</script>

<style lang="scss" scoped>

</style>
