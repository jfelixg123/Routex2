<template>
  <div>
    <h2 class="text-xl font-semibold mb-4">New Offers</h2>

    <div class="grid grid-cols-3 gap-6">
      <div v-for="offer in offers" :key="offer.id" class="bg-white rounded-xl shadow p-4">

        <img :src="offer.image" class="rounded-lg mb-3" />

        <h3 class="font-semibold">{{ offer.title }}</h3>
        <p class="text-sm text-gray-500">Route ID: {{ offer.route }}</p>
        <p class="text-sm text-gray-500">Departs: {{ offer.time }}</p>

        <button class="mt-3 w-full border border-orange-500 text-orange-500 rounded-lg py-2 hover:bg-orange-50">
          Accept Offer
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const offers = [
  {
    id: 1,
    title: 'Miami to Orlando',
    route: 'RX-9021',
    time: '10:00 AM',
    image: 'https://picsum.photos/400/200?1'
  },
  {
    id: 2,
    title: 'Houston to Dallas',
    route: 'RX-4432',
    time: '02:00 PM',
    image: 'https://picsum.photos/400/200?2'
  },
  {
    id: 3,
    title: 'Seattle to Portland',
    route: 'RX-1128',
    time: '08:30 AM',
    image: 'https://picsum.photos/400/200?3'
  }
];
</script>
