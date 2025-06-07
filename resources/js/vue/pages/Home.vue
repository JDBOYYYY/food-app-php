<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Restauracje</h1>
    <div v-if="loading" class="text-center">Ładowanie...</div>
    <div v-else>
      <ul>
        <li v-for="r in restaurants" :key="r.id" class="mb-2">
          <router-link :to="`/restaurants/${r.id}`" class="text-blue-600 underline">
            {{ r.name }}
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

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
