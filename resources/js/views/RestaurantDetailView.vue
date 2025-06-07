<template>
  <div class="min-h-screen bg-gray-100">
    <div v-if="isLoading" class="flex justify-center items-center h-screen">
      <div
        class="animate-spin rounded-full h-16 w-16 border-b-2 border-orange-500"
      ></div>
    </div>
    <div
      v-else-if="error"
      class="flex flex-col justify-center items-center h-screen text-center px-4"
    >
      <h2 class="text-2xl font-bold text-red-600 mb-4">
        Oops! Something went wrong.
      </h2>
      <p class="text-gray-600 mb-6">{{ error }}</p>
      <button
        @click="goBack"
        class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors"
      >
        Go Back
      </button>
    </div>
    <div v-else-if="restaurant">
      <RestaurantHero
        :restaurant="restaurant"
        @goBack="goBack"
        @toggleFavorite="toggleFavorite"
      />
      <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
          <MenuList
            :products="restaurant.Products || []"
            v-model:selectedCategory="selectedCategory"
            @addToBasket="addToBasket"
          />
          <OrderSummary
            :basketItems="basketItems"
            @updateQuantity="updateQuantity"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { restaurantService } from '../services/index';
import { useCartStore } from '../stores/cart';
import { useAuthStore } from '../stores/auth';
import type { ProductDto, RestaurantDetailDto } from '../services/types';

// Import the new components
import RestaurantHero from '../components/restaurant/RestaurantHero.vue';
import MenuList from '../components/restaurant/MenuList.vue';
import OrderSummary from '../components/restaurant/OrderSummary.vue';

const route = useRoute();
const router = useRouter();
const cart = useCartStore();
const auth = useAuthStore();

const restaurant = ref<RestaurantDetailDto | null>(null);
const isLoading = ref(true);
const error = ref<string | null>(null);
const selectedCategory = ref<number | null>(null);

const basketItems = computed(() =>
  cart.items.filter(
    (item) => item.restaurantId === Number(route.params.id),
  ),
);

const goBack = () => router.back();

const addToBasket = (product: ProductDto) => {
  if (!restaurant.value) return;
  cart.addToCart({
    id: product.Id.toString(),
    name: product.Name,
    price: product.Price,
    restaurantId: restaurant.value.Id,
    image: product.ImageUrl,
  });
};

const updateQuantity = (productId: string, newQuantity: number) => {
  cart.updateQuantity(productId, newQuantity);
};

const toggleFavorite = async () => {
  if (!auth.isAuthenticated) {
    if (confirm('You must be logged in to add favorites. Go to login?')) {
      router.push('/login');
    }
    return;
  }
  if (!restaurant.value) return;

  const newStatus = !restaurant.value.isFavorite;
  try {
    // This would call a real service in the future
    console.log(`Toggling favorite for ${restaurant.value.Id} to ${newStatus}`);
    restaurant.value.isFavorite = newStatus;
  } catch (e: any) {
    alert(`Error: ${e.message}`);
  }
};

onMounted(async () => {
  const restaurantId = Number(route.params.id);
  if (isNaN(restaurantId)) {
    error.value = 'Invalid Restaurant ID';
    isLoading.value = false;
    return;
  }

  try {
    restaurant.value = await restaurantService.getRestaurantPageData(
      restaurantId,
    );
  } catch (e: any) {
    error.value = e.message || 'Failed to load restaurant details.';
  } finally {
    isLoading.value = false;
  }
});
</script>