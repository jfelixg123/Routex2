<template>
    <div class="p-8 bg-gray-50">
    <!-- 1. Indicador de Pasos (Stepper) -->
    <StepperComponent :paso="pasoActual" />

    <!-- 2. Formulario Principal -->
    <div class="max-w-8xl mx-auto bg-white rounded-xl shadow-sm p-8">
        <div v-if="!cargandoDatos">
            <div v-if="pasoActual === 1">
                <NuevaOfertaPrimeraComponent :form="form" :estados="estados" :divisas="divisas" :incoterms="incoterms" :key="form.id" ></NuevaOfertaPrimeraComponent>
            </div>

            <!-- PASO 2: RUTA Y CARGAS -->
            <div v-else-if="pasoActual === 2">
                <NuevaOfertaSegundaComponent :form="form" :transportes="transportes" :flujos="flujos" :cargas="cargas" :lines="lines" :key="form.id"></NuevaOfertaSegundaComponent>
            </div>

            <div v-else>
                <NuevaOfertaTerceraComponent :form="form" :key="form.id"  @eliminar="eliminarOferta"></NuevaOfertaTerceraComponent>
            </div>
        </div>
        <!-- Opcional: un spinner o mensaje de espera -->
        <div v-else class="text-center py-10 text-gray-500">
            Cargando datos de la oferta...
        </div>

        <!-- Botones de Acción -->
        <NavegacionBotonesComponent :paso ="pasoActual" @atras="irAtras" @siguiente="irSiguiente" @volverHome="cancelarYSalir"></NavegacionBotonesComponent>
    </div>

    <InfoCardsComponent />

  </div>
</template>

<script setup>
    import { ref, reactive, onMounted } from 'vue';
    import StepperComponent from './PasosOferta.vue';
    import InfoCardsComponent from './InfoCardsComponent.vue';
    import NavegacionBotonesComponent from './NavegacionBotonesComponent.vue';
    import NuevaOfertaPrimeraComponent from './NuevaOfertaPrimeraComponent.vue';
    import NuevaOfertaSegundaComponent from './NuevaOfertaSegundaComponent.vue';
    import NuevaOfertaTerceraComponent from './NuevaOfertaTerceraComponent.vue';

    // Definimos los eventos que este componente puede enviar al AppWrapper
    const emit = defineEmits(['cambiarVista', 'cambiarPaso', 'actualizarPasoHeader']);

    const props = defineProps(['ofertaAEditar']);

    const pasoActual = ref(1);

    const irSiguiente = () => {
        if (pasoActual.value < 3) {
            pasoActual.value++;
            emit('actualizarPasoHeader', pasoActual.value);
            console.log("paaaaaaaaaaaaaaaaaaasa");
        } else {
            console.log("Guuuuuuuuuuuuuuuarda");
            guardarOferta();
            cancelarYSalir();
        }
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

    const form = reactive({
        id: '',
        transporte_id : '',
        flujos_id : '',
        cargas_id : '',
        incoterm_id : '',
        cliente_id : '',
        descripcion : '',
        vendedor_id : '',
        transporte_id : '',
        peso : '',
        volumen : '',
        tipus_validacio_id : '',
        puerto_id : '',
        puerto_destino_id : '',
        aeropuerto_id : '',
        aeropuerto_destino_id : '',
        lineaTransporte_id : '',
        estat_oferta_id : '',
        data_creacio : '',
        valid_desde : '',
        valid_fins : '',
        operador_id : '',
        divisas_id : '',
        concepto : '',
        bultos : '',
        valor : '',
        comentarios_internos : '',
        comentarios_imprimir : '',
        cliente_nombre : '',
        vendedor_nombre : '',
        medidas: []
    });

    const estados = ref([]);

    const cargarEstados = async () => {
        try {
            const response = await axios.get('estats');
            estados.value = response.data;
        } catch (error) {
            console.error("Error cargando estados:", error);
        }
    };

    const divisas = ref([]);

    const cargarDivisas = async () => {
        try {
            const response = await axios.get('divisas');
            divisas.value = response.data;
        } catch (error) {
            console.error("Error cargando divisas:", error);
        }
    };

    const incoterms = ref([]);

    const cargarIncoterms = async () => {
        try {
            const response = await axios.get('incoterms');
            incoterms.value = response.data;
        } catch (error) {
            console.error("Error cargando incoterms:", error);
        }
    };

    const transportes = ref([]);

    const cargarTipoTransporte = async () => {
        try {
            const response = await axios.get('tipoTransporte');
            transportes.value = response.data;
        } catch (error) {
            console.error("Error cargando tipo transporte:", error);
        }
    };

    const flujos = ref([]);

    const cargarFlujos = async () => {
        try {
            const response = await axios.get('tipoFlujo');
            flujos.value = response.data;
        } catch (error) {
            console.error("Error cargando tipo flujos:", error);
        }
    };

    const cargas = ref([]);

    const cargarCargas = async () => {
        try {
            const response = await axios.get('tipoCarga');
            cargas.value = response.data;
        } catch (error) {
            console.error("Error cargando tipo flujos:", error);
        }
    };

    const lines = ref([]);

    const cargarLineas = async () => {
        try {
            const response = await axios.get('lines');
            lines.value = response.data;
        } catch (error) {
            console.error("Error cargando tipo lineas:", error);
        }
    };

    const cargandoDatos = ref(true);

    onMounted(async  () => {
        await Promise.all([
            cargarEstados(),
            cargarDivisas(),
            cargarIncoterms(),
            cargarTipoTransporte(),
            cargarFlujos(),
            cargarCargas(),
            cargarLineas()
        ]);


        if (props.ofertaAEditar) {
            console.log("DATOS DESDE LA API:", JSON.parse(JSON.stringify(props.ofertaAEditar)));
            const o = props.ofertaAEditar;

            // PASO 1: Datos generales
            form.id = o.id;
            form.concepto = o.concepto || 'S/REF';
            form.data_creacio = o.data_creacio;
            form.valid_desde = o.data_validessa_inicial;
            form.valid_fins = o.data_validessa_fina;
            form.estat_oferta_id = o.estat_oferta_id;
            form.divisas_id = o.divisas_id;
            form.incoterm_id = o.incoterm_id;
            form.cliente_nombre = o.usuari?.nom || '';
            form.cliente_id = o.client_id;
            form.vendedor_nombre = o.agent_comercial?.nom || '';
            form.vendedor_id = o.agent_comercial_id;

            // PASO 2: Transporte y Ubicaciones (Nombres para los buscadores)
            form.transporte_id = o.tipus_transport_id;
            form.flujos_id = o.tipus_fluxe_id;
            form.cargas_id = o.tipus_carrega_id;

            // Nombres de Puertos (para que el input no salga vacío)
            form.puerto_nombre = o.port_origen?.nom || '';
            form.puerto_id = o.port_origen_id;
            form.puerto_destino_nombre = o.port_desti?.nom || '';
            form.puerto_destino_id = o.port_desti_id;

            // Nombres de Aeropuertos
            form.aeropuerto_nombre = o.aeroport_origen?.nom || '';
            form.aeropuerto_id = o.aeroport_origen_id;
            form.aeropuerto_destino_nombre = o.aeroport_desti?.nom || '';
            form.aeropuerto_destino_id = o.aeroport_desti_id;

            // Otros campos
            form.peso = o.pes_brut;
            form.volumen = o.volum;
            form.bultos = o.bultos;
            form.descripcion = o.descrip_mercancia;
            form.comentarios_internos = o.comentarios_internos;
            form.comentarios_imprimir = o.comentarios_imprimir;
        }

        cargandoDatos.value = false;

    });

    const guardarOferta = async () => {
        try {
            console.log("Enviando datos a Laravel:", form);
            let response = null;

            if (form.id) {
                console.log("Actualizando oferta existente ID:", form.id);
                response = await axios.put(`ofertas/${form.id}`, form);
                if (response.status === 201 || response.status === 200) {
                    setTimeout(() => {
                        cancelarYSalir();
                    }, 2000);
                    alert("¡Oferta guardada correctamente!");
                }
            } else{
                // Enviamos el objeto 'form' completo a tu API
                const response = await axios.post('ofertas', form);

                if (response.status === 201 || response.status === 200) {
                    setTimeout(() => {
                        cancelarYSalir();
                    }, 2000);
                    alert("¡Oferta guardada correctamente!");
                }
            }
        } catch (error) {
            console.error("ERROR COMPLETO:", error);
            if (error.response) {
                console.error("DATOS DEL ERROR:", error.response.data);
                alert("Error del servidor: " + (error.response.data.error || "Error desconocido"));
            } else {
                alert("Error de conexión o de red.");
            }
        }
    };

    const eliminarOferta = async () => {
        if (confirm("¿Estás seguro de que quieres eliminar esta oferta permanentemente?")) {
            try {
                const response = await axios.delete(`ofertas/${form.id}`);

                if (response.status === 200 || response.status === 204) {
                    setTimeout(() => {
                        alert("Oferta eliminada con éxito");
                        cancelarYSalir();
                    }, 2000);
                }
            } catch (error) {
                console.error("Error al eliminar:", error);
                alert("No se pudo eliminar la oferta. Revisa la consola.");
            }
        }
    };


</script>

<style lang="scss" scoped>

</style>
