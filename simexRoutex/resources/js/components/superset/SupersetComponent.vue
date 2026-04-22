<template>
    <!-- Asegúrate de que el id coincida con el mountPoint -->
    <div id="superset-container" class="superset-frame"></div>
</template>

<script setup>
import { onMounted } from 'vue';
import { embedDashboard } from "@superset-ui/embedded-sdk";
import axios from 'axios';

onMounted(() => {
    embedDashboard({
        id: "8212dbaa-b341-4f72-8580-edadb6088d92", // El UUID que pusiste en el controlador
        supersetDomain: "http://localhost:8088",      // La URL externa de tu Superset
        mountPoint: document.getElementById("superset-container"),
        fetchGuestToken: async () => {
            // Llamada a tu controlador de Laravel
            const response = await axios.get('/superset/token');
            return response.data.token;
        },
        dashboardUiConfig: {
            hideTitle: true,
            filters: { expanded: false },
            urlParams: {
                standalone: "true"
            }
        },
    });
});
</script>

<style scoped>
.superset-frame {
    background: white;
    width: 100%;
    height: 800px;
}

/* Importante: Superset genera un iframe, asegúrate de que ocupe el 100% */
:deep(iframe) {
    width: 100%;
    height: 100%;
    border: none;
}
</style>
