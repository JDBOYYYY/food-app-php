<template>
  <div class="p-4">
    <button @click="$router.back()" class="text-sm mb-4 text-blue-500">Powrót</button>
    <div v-if="loading" class="text-center">Ładowanie...</div>
    <div v-else-if="restaurant">
      <h2 class="text-2xl font-bold mb-2">{{ restaurant.name }}</h2>
      <button v-if="token" @click="toggleFavorite" class="mb-4 px-2 py-1 text-sm text-white" :class="isFav ? 'bg-red-500' : 'bg-green-500'">
        {{ isFav ? 'Usuń z ulubionych' : 'Dodaj do ulubionych' }}
      </button>
      <ul>
        <li v-for="p in products" :key="p.id" class="mb-1">
          {{ p.name }} - {{ p.price }} zł
        </li>
      </ul>
    </div>
    <div v-else>Błąd wczytywania.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useAuth } from '../stores/auth.js';

const route = useRoute();
const restaurant = ref(null);
const products = ref([]);
const loading = ref(true);
const auth = useAuth();
const token = auth.token;
const isFav = ref(false);

onMounted(async () => {
  const id = route.params.id;
  try {
    const [rRes, pRes] = await Promise.all([
      fetch(`/api/restaurants/${id}`),
      fetch(`/api/products?restaurantId=${id}`)
    ]);
    restaurant.value = await rRes.json();
    products.value = await pRes.json();
    if (token.value) {
      // check if restaurant is favorite
      const fRes = await fetch('/api/favorites', { headers: { Authorization: `Bearer ${token.value}` }});
      const favs = await fRes.json();
      isFav.value = favs.some(f => f.restaurantId == id);
    }
  } catch (e) {
    console.error('Fetch details failed', e);
  } finally {
    loading.value = false;
  }
});

async function toggleFavorite() {
  if (!token.value) return;
  try {
    if (isFav.value) {
      await fetch(`/api/favorites/restaurant/${route.params.id}`, { method: 'DELETE', headers: { Authorization: `Bearer ${token.value}` }});
      isFav.value = false;
    } else {
      await fetch(`/api/favorites/restaurant/${route.params.id}`, { method: 'POST', headers: { Authorization: `Bearer ${token.value}` }});
      isFav.value = true;
    }
  } catch (e) { console.error('Toggle favorite failed', e); }
}
</script>
