<template>
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div v-if="isLoading" class="text-center py-20">Loading...</div>
    <div v-else-if="error" class="text-center py-20 text-red-500">
      Error: {{ error }}
    </div>
    <div v-else-if="restaurant">
      <h1 class="text-4xl font-bold mb-2">{{ restaurant.name }}</h1>
      <p class="text-gray-600 mb-6">{{ restaurant.description }}</p>
      <h2 class="text-2xl font-semibold mb-4">Menu</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- ProductItem component would go here -->
        <div
          v-for="product in restaurant.products?.$values"
          :key="product.id"
          class="bg-white p-4 rounded-lg shadow"
        >
          <h3 class="font-bold">{{ product.name }}</h3>
          <p>{{ product.price }} zł</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { restaurantService, type RestaurantDetailDto } from "../services";

const props = defineProps<{ id: string }>();
const restaurant = ref<RestaurantDetailDto | null>(null);
const isLoading = ref(true);
const error = ref<string | null>(null);

onMounted(async () => {
  try {
    restaurant.value = await restaurantService.getById(Number(props.id));
  } catch (e: any) {
    error.value = e.message;
  } finally {
    isLoading.value = false;
  }
});
</script>