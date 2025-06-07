<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Ulubione</h1>
    <div v-if="loading" class="text-center">Ładowanie...</div>
    <ul v-else>
      <li v-for="f in favorites" :key="f.id" class="mb-1">
        {{ f.restaurantName || f.productName }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuth } from '../stores/auth.js';

const favorites = ref([]);
const loading = ref(true);
const auth = useAuth();

onMounted(async () => {
  try {
    const res = await fetch('/api/favorites', {
      headers: { Authorization: `Bearer ${auth.token.value}` }
    });
    favorites.value = await res.json();
  } catch (e) {
    console.error('Fetch favorites failed', e);
  } finally {
    loading.value = false;
  }
});
</script>
