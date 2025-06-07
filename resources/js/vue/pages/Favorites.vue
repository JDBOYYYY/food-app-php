<template>
  <div class="p-4 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Ulubione</h1>
    <div v-if="loading" class="text-center py-10">Ładowanie...</div>
    <div v-else>
      <div v-if="favorites.length === 0" class="text-gray-500">Brak ulubionych.</div>
      <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <component
          v-for="f in favorites"
          :key="f.id"
          :is="f.restaurantId ? 'RestaurantCard' : 'ProductCard'"
          :restaurant="f.restaurantId ? f : undefined"
          :product="!f.restaurantId ? f : undefined"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuth } from '../stores/auth.js';
import RestaurantCard from '../components/RestaurantCard.vue';
import ProductCard from '../components/ProductCard.vue';

const favorites = ref([]);
const loading = ref(true);
const auth = useAuth();

onMounted(async () => {
  try {
    const res = await fetch('/api/favorites', {
      headers: { Authorization: `Bearer ${auth.token.value}` }
    });
    const data = await res.json();
    favorites.value = data.map(f => {
      if (f.restaurantId) {
        return {
          id: f.id,
          restaurantId: f.restaurantId,
          name: f.restaurantName,
          imageUrl: f.productImageUrl || null,
          deliveryTime: null,
          distance: null,
          averageRating: null,
        };
      } else {
        return {
          id: f.id,
          productId: f.productId,
          name: f.productName,
          price: f.productPrice ? f.productPrice.toFixed(2) : '',
          description: null,
          image: f.productImageUrl,
        };
      }
    });
  } catch (e) {
    console.error('Fetch favorites failed', e);
  } finally {
    loading.value = false;
  }
});
</script>
