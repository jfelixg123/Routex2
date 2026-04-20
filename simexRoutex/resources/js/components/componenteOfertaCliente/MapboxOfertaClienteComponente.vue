<template>
  <div class="p-6">
    <h2 class="text-xl font-bold mb-4">Mapa de seguimiento</h2>

    <div id="map" class="w-full h-[500px] rounded-xl"></div>
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
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [origen.lng, origen.lat],
    zoom: 3
  })

  // ORIGEN
  new mapboxgl.Marker({ color: 'green' })
    .setLngLat([origen.lng, origen.lat])
    .addTo(map)

  // DESTINO
  new mapboxgl.Marker({ color: 'red' })
    .setLngLat([destino.lng, destino.lat])
    .addTo(map)

  // POSICIÓN ACTUAL
  const barco = new mapboxgl.Marker({ color: 'orange' })
    .setLngLat([actual.lng, actual.lat])
    .addTo(map)

  // RUTA
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