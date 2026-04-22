<template>
    <div class="p-8 bg-gray-50 min-h-screen">
        <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Centro de Comunicaciones</h1>
        <p class="text-gray-400 mt-1">Envía alertas de red, mantenimientos o avisos personalizados.</p>
        </div>

        <!-- 1. FORMULARIO DE REDACCIÓN -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-6">

            <!-- SELECTOR DE DESTINATARIOS -->
            <div class="space-y-4">
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Enviar a:</label>
                <select v-model="form.target_type" class="border border-gray-200 rounded-xl p-3 bg-gray-50 outline-none focus:ring-2 focus:ring-orange-400">
                <option value="empresa">Toda una Empresa</option>
                <option value="usuario">Usuario Individual</option>
                </select>
            </div>

            <!-- Select de Empresas -->
            <div v-if="form.target_type === 'empresa'" class="flex flex-col gap-2 animate-in slide-in-from-left duration-200">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Seleccionar Empresa</label>
                <select v-model="form.company_id" class="border border-gray-200 rounded-xl p-3 outline-none focus:ring-2 focus:ring-orange-400">
                <option v-for="c in companies" :key="c.id" :value="c.id">{{ c.company_name }}</option>
                </select>
            </div>

            <!-- Select de Usuarios -->
            <div v-if="form.target_type === 'usuario'" class="flex flex-col gap-2 animate-in slide-in-from-left duration-200">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Seleccionar Usuario</label>
                <select v-model="form.user_id" class="border border-gray-200 rounded-xl p-3 outline-none focus:ring-2 focus:ring-orange-400">
                <option v-for="u in usuarios" :key="u.id" :value="u.id">{{ u.nom }} {{ u.cognoms }}</option>
                </select>
            </div>
            </div>

            <!-- TIPO DE ALERTA -->
            <div class="flex flex-col gap-2">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Prioridad / Tipo</label>
            <div class="grid grid-cols-3 gap-3">
                <button v-for="t in tipos" :key="t.id"
                        @click="form.tipo_id = t.id"
                        :class="form.tipo_id === t.id ? t.activeClass : 'bg-gray-50 text-gray-400 border-gray-100'"
                        class="py-4 rounded-xl border flex flex-col items-center gap-2 transition-all">
                <span class="text-xl">{{ t.icon }}</span>
                <span class="text-[10px] font-bold uppercase">{{ t.nom }}</span>
                </button>
            </div>
            </div>
        </div>

        <!-- TÍTULO Y MENSAJE -->
        <div class="space-y-4">
            <div class="flex flex-col gap-2">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Título del mensaje</label>
            <input v-model="form.titulo" type="text" placeholder="Ej: Mantenimiento programado..." class="border border-gray-200 rounded-xl p-3 outline-none focus:ring-2 focus:ring-orange-400">
            </div>
            <div class="flex flex-col gap-2">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Cuerpo de la notificación</label>
            <textarea v-model="form.mensaje" rows="4" class="border border-gray-200 rounded-xl p-4 outline-none focus:ring-2 focus:ring-orange-400 italic text-sm" placeholder="Escriba aquí el contenido del aviso..."></textarea>
            </div>
        </div>


        <div class="mt-8 flex justify-end items-center gap-4">
            <button
            @click="sendNotification"
            :disabled="loading"
            class="relative flex items-center justify-center px-8 py-3 font-bold text-white transition-all duration-200 bg-[#f97316] rounded-lg shadow-lg hover:bg-[#ea580c] active:transform active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed group"
            >
            <!-- Icono de carga -->
            <svg v-if="loading" class="w-5 h-5 mr-3 animate-spin" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>

            <!-- Icono de correo (opcional) -->
            <svg v-else class="w-5 h-5 mr-2 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>

            <span>{{ loading ? 'Enviando...' : 'Enviar Notificación' }}</span>
            </button>

            <!-- BOTÓN ENVIAR -->

            <button @click="enviar" class="bg-slate-800 text-white px-10 py-3 rounded-2xl font-bold hover:bg-slate-700 transition shadow-lg shadow-slate-200">
            Enviar
            </button>

        </div>


    </div>

        <!-- 2. HISTORIAL DE ENVÍOS (Parte inferior) -->
        <div>
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span>📋</span> Envíos Recientes
        </h3>
        <div class="space-y-3">
            <div v-for="n in historial" :key="n.id" class="bg-white p-5 rounded-2xl border border-gray-100 flex justify-between items-center hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-xl">
                {{ n.tipo?.icon || '🔔' }}
                </div>
                <div>
                <p class="text-sm font-bold text-slate-800">{{ n.titulo }}</p>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">
                    Para: {{ n.destinatarios?.length }} destinatarios • {{ n.tipo?.nom }}
                </p>
                </div>
            </div>
            <div class="relative">
                <button @click="menuAbierto = menuAbierto === n.id ? null : n.id" class="text-gray-300 hover:text-slate-500 font-bold text-xl">•••</button>

                <div v-if="menuAbierto === n.id" class="absolute right-0 mt-2 w-32 bg-white border rounded-xl shadow-xl z-10 py-2">
                    <button @click="prepararEdicion(n)" class="w-full text-left px-4 py-2 text-xs font-bold text-slate-600 hover:bg-gray-50">✏️ Editar</button>
                    <button @click="eliminar(n.id)" class="w-full text-left px-4 py-2 text-xs font-bold text-red-500 hover:bg-red-50">🗑️ Borrar</button>
                </div>
            </div>
            </div>
        </div>
        </div>
  </div>
</template>

<script setup>
    import { ref, reactive, onMounted } from 'vue';
    import axios from 'axios';

    const menuAbierto = ref(null);

    const companies = ref([]);
    const usuarios = ref([]);
    const historial = ref([]);
    const tipos = ref([
        { id: 1, nom: 'Alerta', icon: '⚠️', activeClass: 'bg-red-50 border-red-200 text-red-600 shadow-inner' },
        { id: 2, nom: 'Info', icon: 'ℹ️', activeClass: 'bg-blue-50 border-blue-200 text-blue-600 shadow-inner' },
        { id: 3, nom: 'Sistema', icon: '⚙️', activeClass: 'bg-slate-100 border-slate-300 text-slate-800 shadow-inner' }
    ]);

    const form = reactive({
        target_type: 'empresa',
        company_id: '',
        user_id: '',
        tipo_id: 1,
        titulo: '',
        mensaje: ''
    });

    const cargarDatos = async () => {
        const [resComp, resUser, resHist] = await Promise.all([
            axios.get('company'),
            axios.get('usuaris'),
            axios.get('notificaciones')
        ]);
        companies.value = resComp.data;
        usuarios.value = resUser.data;
        historial.value = resHist.data;
    };

    const modoEdicion = ref(false);

    const prepararEdicion = (notificacion) => {
        modoEdicion.value = true;
        form.id = notificacion.id;
        form.titulo = notificacion.titulo;
        form.mensaje = notificacion.mensaje;
        form.tipo_id = notificacion.tipo_id;

        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const cancelarEdicion = () => {
        modoEdicion.value = false;
        form.id = null;
        form.titulo = '';
        form.mensaje = '';
    };

    const enviar = async () => {
        try {
            let res;
            if (modoEdicion.value) {
                res = await axios.put(`notificaciones/${form.id}`, form);
                alert("Notificación actualizada");
            } else {
                res = await axios.post('notificaciones', form);
                alert("Notificación enviada correctamente");
            }
            cancelarEdicion();
            cargarDatos();
        } catch (error) {
            alert("Error: " + error.response?.data?.error);
        }
    };

    const eliminar = async (id) => {
        if (confirm("¿Eliminar esta notificación del historial y de la bandeja de los usuarios?")) {
            await axios.delete(`notificaciones/${id}`);
            cargarDatos();
        }
    };

    const loading = ref(false);

    const sendNotification = async () => {

        if (!form.titulo || !form.mensaje) {
            alert("⚠️ Por favor, escribe un título y un mensaje.");
            return;
        }
        loading.value = true;

        try {
            let listaEmails = [];
            let nombreDestinatario = "";

            // 2. Lógica para determinar destinatarios
            if (form.target_type === 'usuario') {
                const user = usuarios.value.find(u => u.id === form.user_id);
                if (user && user.correu) {
                    listaEmails.push(user.correu);
                    nombreDestinatario = `${user.nom} ${user.cognoms}`;
                }
            } else {
                // Filtramos usuarios que pertenecen a la empresa seleccionada
                const filtrados = usuarios.value.filter(u => u.empresa_id === form.company_id);
                listaEmails = filtrados.map(u => u.correu).filter(e => !!e);

                const comp = companies.value.find(c => c.id === form.company_id);
                nombreDestinatario = comp ? comp.company_name : "Toda la empresa";
            }

            if (listaEmails.length === 0) {
                throw new Error("No hay emails válidos para el destino seleccionado.");
            }

            // 3. Payload dinámico para n8n
            const payload = {
                to: listaEmails.join(', '), // n8n acepta correos separados por coma
                from: "noreply@routex.com",
                subject: form.titulo,
                message: form.mensaje,
                target_name: nombreDestinatario,
                prioridad: tipos.value.find(t => t.id === form.tipo_id)?.nom || 'Info'
            };

            // 4. Envío al Webhook
            await axios.post('http://localhost:5678/webhook/routex-email',
                payload,
                {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }
            );

            // 5. Guardar también en tu base de datos local (opcional, reutilizando tu lógica)
            await enviar();

            alert(`✅ Notificación enviada a ${listaEmails.length} correo(s)`);

        } catch (error) {
            console.error('Error:', error);
            alert('❌ Error: ' + (error.message || 'No se pudo conectar con el servidor de correos'));
        } finally {
            loading.value = false;
        }
    };

    onMounted(cargarDatos);
</script>

<style lang="scss" scoped>

</style>
