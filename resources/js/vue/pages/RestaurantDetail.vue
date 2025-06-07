<template>
  <div class="p-4">
    <button @click="$router.back()" class="text-sm mb-4 text-blue-500">Powrót</button>
    <div v-if="loading" class="text-center">Ładowanie...</div>
    <div v-else-if="restaurant">
      <h2 class="text-2xl font-bold mb-2">{{ restaurant.name }}</h2>
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

const route = useRoute();
const restaurant = ref(null);
const products = ref([]);
const loading = ref(true);

onMounted(async () => {
  const id = route.params.id;
  try {
    const [rRes, pRes] = await Promise.all([
      fetch(`/api/restaurants/${id}`),
      fetch(`/api/products?restaurantId=${id}`)
    ]);
    restaurant.value = await rRes.json();
    products.value = await pRes.json();
  } catch (e) {
    console.error('Fetch details failed', e);
  } finally {
    loading.value = false;
  }
});
</script>
