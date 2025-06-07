<template>
  <div class="p-4 max-w-4xl mx-auto">
    <button @click="$router.back()" class="text-sm mb-4 text-blue-500">Powrót</button>
    <div v-if="loading" class="text-center py-10">Ładowanie...</div>
    <div v-else-if="restaurant">
      <img :src="restaurant.imageUrl || placeholder" class="w-full h-60 object-cover mb-4 rounded" />
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold">{{ restaurant.name }}</h2>
        <button v-if="token" @click="toggleFavorite" class="px-3 py-1 text-sm text-white rounded" :class="isFav ? 'bg-red-500' : 'bg-green-500'">
          {{ isFav ? 'Usuń z ulubionych' : 'Dodaj do ulubionych' }}
        </button>
      </div>
      <div class="grid gap-6 sm:grid-cols-2">
        <ProductCard v-for="p in products" :key="p.id" :product="p" />
      </div>
    </div>
    <div v-else>Błąd wczytywania.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useAuth } from '../stores/auth.js';
import ProductCard from '../components/ProductCard.vue';

const placeholder = '/placeholder-restaurant.png';

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
    const prods = await pRes.json();
    products.value = prods.map(p => ({
      id: p.id,
      name: p.name,
      description: p.description,
      price: p.price?.toFixed ? p.price.toFixed(2) : p.price,
      image: p.imageUrl,
    }));
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
