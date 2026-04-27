<template>
    <div class="bg-white rounded-xl shadow p-4">
        <div id="map" class="w-full h-[400px] rounded-xl"></div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import mapboxgl from 'mapbox-gl'

mapboxgl.accessToken = 'pk.eyJ1IjoiamVhbjEyMzIxIiwiYSI6ImNtbTUzZzhqYTAyMjYycHA5YzQ3amtqdWcifQ.VfYxLlWimvLzoBwsQu7SuA'

onMounted(() => {
    // const origen = { lat: 31.2304, lng: 121.4737 } // Shanghai
    const destino = { lat: 34.0522, lng: -118.2437 } // LA
    const actual = { lat: 25.0, lng: -140.0 } // barco

    const map = new mapboxgl.Map({
        container: 'map', // ID del div
        style: 'mapbox://styles/mapbox/streets-v11', // El "look" del mapa
        center: [actual.lng, actual.lat], // Posición inicial
        zoom: 3 // Nivel de acercamiento
    })

    // Función para interpolar entre dos puntos
function interpolar(start, end, progress) {
    return start + (end - start) * progress
}

let progreso = 0
const velocidad = 0.001 // Ajusta esto (más alto = más rápido)
    // // ORIGEN
    // new mapboxgl.Marker({ color: 'green' }) // Crea un pin visual
    //     .setLngLat([origen.lng, origen.lat]) // Le dice en qué coordenada ponerlo
    //     .addTo(map)

    // DESTINO:
    new mapboxgl.Marker({ color: 'red' }) // Crea un pin visual
        .setLngLat([destino.lng, destino.lat]) // Le dice en qué coordenada ponerlo
        .addTo(map) // Lo imprime en el mapa

    // POSICIÓN ACTUAL
    const barco = new mapboxgl.Marker({ color: 'orange' }) // Crea un pin visual
        // .setLngLat([actual.lng, actual.lat])
        .setLngLat([destino.lng, destino.lat]) // Le dice en qué coordenada ponerlo
        .addTo(map) // Lo imprime en el mapa

    // RUTA
    // Cuando el mapa termine de cargar, dibuja la ruta entre origen, actual y destino
    map.on('load', () => {

    // Fuente inicial (solo punto actual)
    map.addSource('ruta', {
        type: 'geojson',
        data: {
            type: 'Feature',
            geometry: {
                type: 'LineString',
                coordinates: [
                    [actual.lng, actual.lat]
                ]
            }
        }
    })

    map.addLayer({
        id: 'ruta',
        type: 'line',
        source: 'ruta',
        paint: {
            'line-color': '#ff6600',
            'line-width': 4
        }
    })

    const rutaSource = map.getSource('ruta')

    // const interval = setInterval(() => {
    //     progreso += velocidad

    //     if (progreso >= 1) {
    //         progreso = 1
    //         clearInterval(interval)
    //     }

    //     const nuevaLat = interpolar(actual.lat, destino.lat, progreso)
    //     const nuevaLng = interpolar(actual.lng, destino.lng, progreso)

    //     // 🟠 Mover barco
    //     barco.setLngLat([nuevaLng, nuevaLat])

    //     // 🔥 ACTUALIZAR LA LÍNEA
    //     const nuevaRuta = {
    //         type: 'Feature',
    //         geometry: {
    //             type: 'LineString',
    //             coordinates: [
    //                 [destino.lng, destino.lat],      // inicio
    //                 [nuevaLng, nuevaLat]           // hasta donde va el barco
    //             ]
    //         }
    //     }

    //     rutaSource.setData(nuevaRuta)

    // }, 25)
})
})
</script>