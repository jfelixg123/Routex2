<template>
    <div class="bg-white rounded-xl shadow p-4">
        <div id="map" class="w-full h-[400px] rounded-xl"></div>
    </div>
</template>

<script setup>
// Importamos funciones de Vue (Composition API)
import { onMounted } from 'vue'

// Importamos la librería de mapas
import mapboxgl from 'mapbox-gl'

// Token de Mapbox (necesario para que funcione el mapa)
mapboxgl.accessToken = 'TU_TOKEN_AQUI'

// Se ejecuta cuando el componente ya está cargado en el DOM
onMounted(() => {

    // Coordenadas de ejemplo
    // const origen = { lat: 31.2304, lng: 121.4737 } // Shanghai
    const destino = { lat: 34.0522, lng: -118.2437 } // Los Angeles
    const actual = { lat: 25.0, lng: -140.0 } // Posición inicial del barco

    // Crear el mapa
    const map = new mapboxgl.Map({
        container: 'map', // ID del div donde se renderiza el mapa
        style: 'mapbox://styles/mapbox/streets-v11', // Estilo visual del mapa
        center: [actual.lng, actual.lat], // Centro inicial del mapa
        zoom: 3 // Nivel de zoom
    })

    // Función para calcular puntos intermedios entre dos coordenadas
    function interpolar(start, end, progress) {
        // Fórmula de interpolación lineal
        return start + (end - start) * progress
    }

    // Progreso de la animación (0 = inicio, 1 = final)
    let progreso = 0

    // Velocidad del movimiento (más alto = más rápido)
    const velocidad = 0.001

    // ------------------------
    // MARCADORES
    // ------------------------

    // ORIGEN (⚠️ ahora mismo está comentado arriba, esto daría error)
    /*
    new mapboxgl.Marker({ color: 'green' })
        .setLngLat([origen.lng, origen.lat])
        .addTo(map)
    */

    // DESTINO
    new mapboxgl.Marker({ color: 'red' })
        .setLngLat([destino.lng, destino.lat])
        .addTo(map)

    // POSICIÓN ACTUAL (barco)
    const barco = new mapboxgl.Marker({ color: 'orange' })
        .setLngLat([actual.lng, actual.lat]) // Posición inicial del barco
        .addTo(map)

    // ------------------------
    // CUANDO EL MAPA CARGA
    // ------------------------
    map.on('load', () => {

        // Crear la fuente de datos (línea de ruta)
        map.addSource('ruta', {
            type: 'geojson',
            data: {
                type: 'Feature',
                geometry: {
                    type: 'LineString',
                    coordinates: [
                        [actual.lng, actual.lat] // Empieza en posición actual
                    ]
                }
            }
        })

        // Crear la capa visual de la línea
        map.addLayer({
            id: 'ruta',
            type: 'line',
            source: 'ruta',
            paint: {
                'line-color': '#ff6600', // Color naranja
                'line-width': 4 // Grosor de la línea
            }
        })

        // Obtener referencia a la fuente para poder actualizarla
        const rutaSource = map.getSource('ruta')

        // ------------------------
        // ANIMACIÓN
        // ------------------------
        const interval = setInterval(() => {

            // Aumenta el progreso
            progreso += velocidad

            // Si llega al final, se detiene
            if (progreso >= 1) {
                progreso = 1
                clearInterval(interval)
            }

            // Calcular nueva posición del barco
            const nuevaLat = interpolar(actual.lat, destino.lat, progreso)
            const nuevaLng = interpolar(actual.lng, destino.lng, progreso)

            // Mover el marcador del barco
            barco.setLngLat([nuevaLng, nuevaLat])

            // Crear nueva línea actualizada
            const nuevaRuta = {
                type: 'Feature',
                geometry: {
                    type: 'LineString',
                    coordinates: [
                        [actual.lng, actual.lat], // origen de la ruta
                        [nuevaLng, nuevaLat]      // hasta donde ha llegado
                    ]
                }
            }

            // Actualizar la línea en el mapa
            rutaSource.setData(nuevaRuta)

        }, 25) // Se ejecuta cada 25ms (animación fluida)

    })
})
</script>