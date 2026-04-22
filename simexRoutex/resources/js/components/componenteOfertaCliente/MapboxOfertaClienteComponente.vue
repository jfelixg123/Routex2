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
    const origen = { lat: 31.2304, lng: 121.4737 } // Shanghai
    const destino = { lat: 34.0522, lng: -118.2437 } // LA
    const actual = { lat: 25.0, lng: -140.0 } // barco

    const map = new mapboxgl.Map({
        container: 'map', // ID del div
        style: 'mapbox://styles/mapbox/streets-v11', // El "look" del mapa
        center: [origen.lng, origen.lat], // Donde empieza el mapa
        zoom: 3 // Nivel de acercamiento
    })

    // ORIGEN
    new mapboxgl.Marker({ color: 'green' }) // Crea un pin visual
        .setLngLat([origen.lng, origen.lat]) // Le dice en qué coordenada ponerlo
        .addTo(map)

    // DESTINO: 
    new mapboxgl.Marker({ color: 'red' }) // Crea un pin visual
        .setLngLat([destino.lng, destino.lat]) // Le dice en qué coordenada ponerlo
        .addTo(map) // Lo imprime en el mapa

    // POSICIÓN ACTUAL
    const barco = new mapboxgl.Marker({ color: 'orange' }) // Crea un pin visual
        .setLngLat([actual.lng, actual.lat]) // Le dice en qué coordenada ponerlo
        .addTo(map) // Lo imprime en el mapa

    // RUTA
    // Cuando el mapa termine de cargar, dibuja la ruta entre origen, actual y destino
    map.on('load', () => {
        map.addSource('ruta', {
            type: 'geojson',
            data: {
                type: 'Feature',
                geometry: {
                    type: 'LineString',
                    coordinates: [
                        [origen.lng, origen.lat],
                        [actual.lng, actual.lat],
                        [destino.lng, destino.lat]
                    ]
                }
            }
        })
    // Agrega una capa visual para mostrar la ruta
        map.addLayer({
            id: 'ruta',
            type: 'line',
            source: 'ruta',
            paint: {
                'line-color': '#ff6600',
                'line-width': 4
            }
        })
    })
})
</script>