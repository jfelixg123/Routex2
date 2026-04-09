<template>
    <div class="flex-1 flex flex-col">
        <div class="bg-gray-100">
            <CabeceraComponent></CabeceraComponent>
        </div>
        <div class="flex-1 p-6 bg-gray-100">
            <h2 class="text-2xl font-semibold mb-4">Ofertas Activas</h2>
            <OfertasActivasComponent />
            <div class="pt-6 space-y-6">
                <!-- otras cosas -->
                <OfertasRecientesComponent />
            </div>
        </div>
    </div>
</template>

<script setup>
import OfertasActivasComponent from './OfertasActivasComponent.vue';
import CabeceraComponent from './CabeceraComponent.vue';
import OfertasRecientesComponent from './OfertasRecientesComponent.vue';
</script>

<style scoped>
</style>
