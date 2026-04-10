<template>
    <div class="p-8 bg-gray-50">
    <!-- 1. Indicador de Pasos (Stepper) -->
    <StepperComponent :paso="pasoActual" />

    <!-- 2. Formulario Principal -->
    <div class="max-w-8xl mx-auto bg-white rounded-xl shadow-sm p-8">
        <div v-if="pasoActual === 1">
            <NuevaOfertaPrimeraComponent></NuevaOfertaPrimeraComponent>
        </div>

        <!-- PASO 2: RUTA Y CARGAS -->
        <div v-else-if="pasoActual === 2">
            <NuevaOfertaSegundaComponent></NuevaOfertaSegundaComponent>
        </div>

        <div v-else>
            <NuevaOfertaTerceraComponent></NuevaOfertaTerceraComponent>
        </div>

        <!-- Botones de Acción -->
        <NavegacionBotonesComponent :paso ="pasoActual" @atras="irAtras" @siguiente="irSiguiente" @volverHome="cancelarYSalir"></NavegacionBotonesComponent>
    </div>

    <InfoCardsComponent />

  </div>
</template>

<script setup>
    import { ref } from 'vue';
    import StepperComponent from './PasosOferta.vue';
    import InfoCardsComponent from './InfoCardsComponent.vue';
    import NavegacionBotonesComponent from './NavegacionBotonesComponent.vue';
    import NuevaOfertaPrimeraComponent from './NuevaOfertaPrimeraComponent.vue';
    import NuevaOfertaSegundaComponent from './NuevaOfertaSegundaComponent.vue';
    import NuevaOfertaTerceraComponent from './NuevaOfertaTerceraComponent.vue';

    // Definimos los eventos que este componente puede enviar al AppWrapper
    const emit = defineEmits(['cambiarVista', 'cambiarPaso']);

    const pasoActual = ref(1);

    const irSiguiente = () => {
        if (pasoActual.value < 3) {
            pasoActual.value++;
            emit('actualizarPasoHeader', pasoActual.value);
        };
        emit('actualizarPasoHeader', pasoActual.value);
        console.log("Paso después:", pasoActual.value);
    };

    const irAtras = () => {
        if (pasoActual.value > 1) {
            pasoActual.value--;
            emit('actualizarPasoHeader', pasoActual.value);
        };
    };
    const cancelarYSalir = () => {
        console.log("Reenviando orden al abuelo...");
        emit('cambiarVista', 'dashboard'); // Enviamos la orden de volver al home
    };
</script>

<style lang="scss" scoped>

</style>
