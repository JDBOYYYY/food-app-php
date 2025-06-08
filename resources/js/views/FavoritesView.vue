<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow-sm">
      <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Your Favorites</h1>
            <p class="text-gray-600">
              {{ favoriteRestaurants.length }} restaurants •
              {{ favoriteProducts.length }} dishes
            </p>
          </div>
          <div class="flex items-center gap-4">
            <!-- View Toggle -->
            <div class="flex items-center bg-gray-100 rounded-lg p-1">
              <button
                @click="viewMode = 'restaurants'"
                :class="[
                  'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                  viewMode === 'restaurants'
                    ? 'bg-white text-gray-900 shadow-sm'
                    : 'text-gray-600 hover:text-gray-900',
                ]"
              >
                Restaurants
              </button>
              <button
                @click="viewMode = 'products'"
                :class="[
                  'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                  viewMode === 'products'
                    ? 'bg-white text-gray-900 shadow-sm'
                    : 'text-gray-600 hover:text-gray-900',
                ]"
              >
                Dishes
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 py-8">
      <!-- Loading State -->
      <div v-if="isLoading" class="text-center py-16">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500 mx-auto"
        ></div>
        <p class="mt-4 text-gray-600">Loading your favorites...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-16">
        <h3 class="text-xl font-semibold text-red-600 mb-2">
          Failed to load favorites
        </h3>
        <p class="text-gray-600">{{ error }}</p>
      </div>

      <!-- Empty State -->
      <div
        v-else-if="currentItems.length === 0"
        class="text-center py-16"
      >
        <Heart class="h-16 w-16 text-gray-300 mx-auto mb-4" />
        <h2 class="text-2xl font-bold text-gray-900 mb-2">No favorites yet</h2>
        <p class="text-gray-600 mb-6">
          {{
            viewMode === 'restaurants'
              ? 'Start exploring restaurants and add your favorites!'
              : 'Save your favorite dishes for quick ordering!'
          }}
        </p>
        <button
          @click="router.push('/')"
          class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium"
        >
          Explore Restaurants
        </button>
      </div>

      <!-- Restaurants View -->
      <div
        v-else-if="viewMode === 'restaurants'"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
      >
        <RestaurantListItem
          v-for="restaurant in favoriteRestaurants"
          :key="restaurant.id"
          :item="restaurant"
          @press="viewRestaurant(restaurant.id)"
          :onToggleFavorite="toggleFavorite"
        />
      </div>

      <!-- Dishes View -->
      <div
        v-else-if="viewMode === 'products'"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
      >
        <ProductListItem
          v-for="product in favoriteProducts"
          :key="product.Id"
          :item="product"
          :onToggleFavorite="toggleFavorite"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { Heart, Star, Clock, Package, Filter, ChevronDown } from 'lucide-vue-next';
import { useAuthStore } from '../stores/auth';
import { favoriteService } from '../services/index';
import type { ProductDto } from '../services/types';

import RestaurantListItem from '../components/RestaurantListItem.vue';
import ProductListItem from '../components/ProductListItem.vue';
import type { RestaurantListItemProps } from '../components/RestaurantListItem.vue';

const router = useRouter();
const auth = useAuthStore();

const viewMode = ref<'restaurants' | 'products'>('restaurants');
const isLoading = ref(true);
const error = ref<string | null>(null);

const favoriteProducts = ref<ProductDto[]>([]);
const favoriteRestaurants = ref<RestaurantListItemProps[]>([]);

const currentItems = computed(() => {
  return viewMode.value === 'restaurants'
    ? favoriteRestaurants.value
    : favoriteProducts.value;
});

const mapRestaurantDtoToProps = (dto: any): RestaurantListItemProps => ({
    id: dto.Id,
    name: dto.Name,
    imageUrl: dto.ImageUrl,
    rating: dto.AverageRating,
    deliveryTime: dto.DeliveryTime,
    isFavorite: true,
    cuisineType: dto.Categories?.map((c: any) => c.Name).join(' • ') || 'Various',
});

const fetchFavorites = async () => {
  if (!auth.isAuthenticated) {
    isLoading.value = false;
    return;
  }
  isLoading.value = true;
  error.value = null;
  try {
    const response = await favoriteService.getUserFavorites();
    favoriteProducts.value = response.products.map(p => ({...p, isFavorite: true}));
    favoriteRestaurants.value = response.restaurants.map(mapRestaurantDtoToProps);
  } catch (e: any) {
    error.value = e.message || 'Could not load your favorites.';
  } finally {
    isLoading.value = false;
  }
};

const toggleFavorite = async (itemId: number, itemType: 'product' | 'restaurant') => {
  try {
    if (itemType === 'product') {
      await favoriteService.removeProductFavorite(itemId);
      favoriteProducts.value = favoriteProducts.value.filter(p => p.Id !== itemId);
    } else {
      await favoriteService.removeRestaurantFavorite(itemId);
      favoriteRestaurants.value = favoriteRestaurants.value.filter(r => r.id !== itemId);
    }
  } catch (e) {
    console.error('Failed to update favorite status:', e);
    fetchFavorites(); // Re-fetch to get the correct state back on error
  }
};

const viewRestaurant = (restaurantId: number) => {
  router.push(`/restaurant/${restaurantId}`);
};

onMounted(fetchFavorites);
</script>

<style scoped>
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
</style>