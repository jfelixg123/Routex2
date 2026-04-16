<template>
    <div class="w-64 bg-gray-800 text-white flex flex-col justify-between min-h-screen p-6">
        <div>
            <div class="flex items-center gap-2 mb-6">
                <div class="bg-orange-500 text-white w-8 h-8 flex items-center justify-center rounded-lg font-bold">R</div>
                <span class="text-xl font-bold text-white">Route<span class="text-orange-500">X</span></span>
            </div>

            <!-- MENU LATERAL OPERADOR -->

            <ul v-if="esOperador" class="space-y-3">
                <li><a href="#" @click.prevent="$emit('cambiarVista', 'dashboard')" :class="[
                    'flex items-center gap-2 p-2 rounded transition-all duration-200',
                    vistaActual === 'dashboard'
                        ? 'bg-orange-500 text-white shadow-md'
                        : 'text-gray-400 hover:bg-gray-700 hover:text-white'
                ]">🏠 Dashboard</a></li>
                <li><a href="#" @click.prevent="$emit('cambiarVista', 'incoterms')" :class="[
                    'flex items-center gap-2 p-2 rounded transition-all duration-200',
                    vistaActual === 'incoterms'
                        ? 'bg-orange-500 text-white shadow-md'
                        : 'text-gray-400 hover:bg-gray-700 hover:text-white'
                ]">📦 Incoterms</a></li>
                <li><a href="#" @click.prevent="$emit('cambiarVista', 'usuaris')" :class="[
                    'flex items-center gap-2 p-2 rounded transition-all duration-200',
                    vistaActual === 'usuaris'
                        ? 'bg-orange-500 text-white shadow-md'
                        : 'text-gray-400 hover:bg-gray-700 hover:text-white'
                ]">👥 Usuarios</a></li>
                <li><a href="#" @click.prevent="$emit('cambiarVista', 'comunicaciones')" :class="[
                    'flex items-center gap-2 p-2 rounded transition-all duration-200',
                    vistaActual === 'comunicaciones'
                        ? 'bg-orange-500 text-white shadow-md'
                        : 'text-gray-400 hover:bg-gray-700 hover:text-white'
                ]">📢 Comunicaciones</a></li>
                <li><a href="#" @click.prevent="$emit('cambiarVista', 'seguimiento')" :class="[
                    'flex items-center gap-2 p-2 rounded transition-all duration-200',
                    vistaActual === 'seguimiento'
                        ? 'bg-orange-500 text-white shadow-md'
                        : 'text-gray-400 hover:bg-gray-700 hover:text-white'
                ]">⚠️ Seguimiento</a></li>
                <li><a href="#" class="flex items-center gap-2 p-2 rounded hover:bg-gray-700">⚙️ Configuración</a></li>
            </ul>

            <!-- MENU LATERAL CLIENTE -->

            <ul v-else class="space-y-3">

                <li>
                    <a href="#" @click.prevent="$emit('cambiarVista', 'dashboard')" :class="[
                        'flex items-center gap-2 p-2 rounded',
                        vistaActual === 'dashboard'
                            ? 'bg-orange-500 text-white'
                            : 'text-gray-400 hover:bg-gray-700'
                    ]">
                        🏠 Dashboard
                    </a>
                </li>

                <li>
                    <a href="#" @click.prevent="$emit('cambiarVista', 'ofertas')"
                        class="flex items-center gap-2 p-2 rounded text-gray-400 hover:bg-gray-700">
                        💼 Ofertas
                    </a>
                </li>

                <li>
                    <a href="#" @click.prevent="$emit('cambiarVista', 'historico')"
                        class="flex items-center gap-2 p-2 rounded text-gray-400 hover:bg-gray-700">
                        📜 Histórico
                    </a>
                </li>

                <li>
                    <a href="#" @click.prevent="$emit('cambiarVista', 'perfil')"
                        class="flex items-center gap-2 p-2 rounded text-gray-400 hover:bg-gray-700">
                        👤 Perfil
                    </a>
                </li>

            </ul>
        </div>

        <!-- CAMBIOS DE BOTONES DEPENDIENDO EL ROL QUE TENGA -->

        <div v-if="props.vistaActual !== 'nueva-oferta'">
            <button v-if="esOperador" @click="$emit('cambiarVista', 'nueva-oferta')"
                class="w-full px-4 py-2 bg-orange-500 text-white font-semibold rounded hover:bg-orange-600 transition">
                Crear Oferta
            </button>

            <button v-else @click="$emit('cambiarVista', 'nueva-peticion')"
                class="w-full px-4 py-2 bg-orange-500 text-white font-semibold rounded hover:bg-orange-600 transition">
                Nueva peticion
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const name = 'MenuLateralComponent';
const props = defineProps({
    user: Object,
    vistaActual: {
        type: String,
        default: 'dashboard'
    }
});

const esOperador = computed(() => Number(props.user?.rol_id) === 1);

</script>

<style scoped></style>
