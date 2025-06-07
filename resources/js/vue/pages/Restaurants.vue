<template>
  <div class="p-4 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Wszystkie Restauracje</h1>
    <div v-if="loading" class="text-center py-10">Ładowanie...</div>
    <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <router-link
        v-for="r in restaurants"
        :key="r.id"
        :to="`/restaurants/${r.id}`"
        class="block"
      >
        <RestaurantCard :restaurant="r" />
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import RestaurantCard from '../components/RestaurantCard.vue';

const restaurants = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const res = await fetch('/api/restaurants');
    restaurants.value = await res.json();
  } catch (e) {
    console.error('Fetch restaurants failed', e);
  } finally {
    loading.value = false;
  }
});
</script>
