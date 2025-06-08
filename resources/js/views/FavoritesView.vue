<template>
  <div class="min-h-screen bg-gray-100">
    <div class="bg-white shadow-sm">
      <div class="container mx-auto px-4 py-6">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Your Favorites</h1>
          <p class="text-gray-600">
            A collection of your most-loved restaurants and dishes.
          </p>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4 py-8">
      <div v-if="isLoading" class="text-center py-16">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500 mx-auto"
        ></div>
        <p class="mt-4 text-gray-600">Loading your favorites...</p>
      </div>

      <div v-else-if="error" class="text-center py-16">
        <h3 class="text-xl font-semibold text-red-600 mb-2">
          Failed to load favorites
        </h3>
        <p class="text-gray-600">{{ error }}</p>
      </div>

      <div v-else>
        <section class="mb-12">
          <h2 class="text-2xl font-bold text-gray-900 mb-6">
            Favorite Restaurants
          </h2>
          <div
            v-if="favoriteRestaurants.length === 0"
            class="text-center py-10 bg-white rounded-lg border border-dashed"
          >
            <Heart class="h-12 w-12 text-gray-300 mx-auto mb-4" />
            <h3 class="text-lg font-semibold text-gray-700">
              No favorite restaurants yet
            </h3>
            <p class="text-gray-500 mt-1 mb-4">
              Click the heart icon on any restaurant to save it here.
            </p>
            <button
              @click="router.push('/')"
              class="bg-orange-500 text-white px-5 py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium text-sm"
            >
              Explore Restaurants
            </button>
          </div>
          <TransitionGroup
            v-else
            name="list-item"
            tag="div"
            class="relative grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
          >
            <RestaurantListItem
              v-for="restaurant in favoriteRestaurants"
              :key="restaurant.id"
              :item="restaurant"
              @press="viewRestaurant(restaurant.id)"
              :onToggleFavorite="handleRestaurantToggle"
            />
          </TransitionGroup>
        </section>

        <section>
          <h2 class="text-2xl font-bold text-gray-900 mb-6">
            Favorite Dishes
          </h2>
          <div
            v-if="favoriteProducts.length === 0"
            class="text-center py-10 bg-white rounded-lg border border-dashed"
          >
            <Heart class="h-12 w-12 text-gray-300 mx-auto mb-4" />
            <h3 class="text-lg font-semibold text-gray-700">
              No favorite dishes yet
            </h3>
            <p class="text-gray-500 mt-1">
              Save your favorite dishes for quick reordering.
            </p>
          </div>
          <TransitionGroup
            v-else
            name="list-item"
            tag="div"
            class="relative grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
          >
            <ProductListItem
              v-for="product in favoriteProducts"
              :key="product.Id"
              :item="product"
              :onToggleFavorite="handleProductToggle"
            />
          </TransitionGroup>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { Heart } from "lucide-vue-next";
import { useAuthStore } from "../stores/auth";
import { favoriteService } from "../services/index";
import type { ProductDto } from "../services/types";

import RestaurantListItem from "../components/RestaurantListItem.vue";
import ProductListItem from "../components/ProductListItem.vue";
import type { RestaurantListItemProps } from "../components/RestaurantListItem.vue";

const router = useRouter();
const auth = useAuthStore();

const isLoading = ref(true);
const error = ref<string | null>(null);

const favoriteProducts = ref<ProductDto[]>([]);
const favoriteRestaurants = ref<RestaurantListItemProps[]>([]);

const mapRestaurantDtoToProps = (dto: any): RestaurantListItemProps => ({
  id: dto.Id,
  name: dto.Name,
  imageUrl: dto.ImageUrl,
  rating: dto.AverageRating,
  deliveryTime: dto.DeliveryTime,
  isFavorite: true,
  cuisineType:
    dto.Categories?.map((c: any) => c.Name).join(" • ") || "Various",
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
    favoriteProducts.value = response.products.map((p) => ({
      ...p,
      isFavorite: true,
    }));
    favoriteRestaurants.value = response.restaurants.map(mapRestaurantDtoToProps);
  } catch (e: any) {
    error.value = e.message || "Could not load your favorites.";
  } finally {
    isLoading.value = false;
  }
};

const handleRestaurantToggle = async (
  restaurantId: number,
  newStatus: boolean,
) => {
  if (newStatus === true) return;

  try {
    await favoriteService.removeRestaurantFavorite(restaurantId);
    const index = favoriteRestaurants.value.findIndex(
      (r) => r.id === restaurantId,
    );
    if (index > -1) {
      favoriteRestaurants.value.splice(index, 1);
    }
  } catch (e) {
    console.error("Failed to remove restaurant favorite:", e);
    fetchFavorites();
  }
};

const handleProductToggle = async (productId: number, newStatus: boolean) => {
  if (newStatus === true) return;

  try {
    await favoriteService.removeProductFavorite(productId);
    const index = favoriteProducts.value.findIndex((p) => p.Id === productId);
    if (index > -1) {
      favoriteProducts.value.splice(index, 1);
    }
  } catch (e) {
    console.error("Failed to remove product favorite:", e);
    fetchFavorites();
  }
};

const viewRestaurant = (restaurantId: number) => {
  router.push(`/restaurant/${restaurantId}`);
};

onMounted(fetchFavorites);
</script>

<style scoped>
/* Transition for removing items from the list */
.list-item-leave-active {
  transition: all 0.4s ease;
  /* Position absolute is needed to allow other items to smoothly re-flow */
  position: absolute;
}
.list-item-leave-to {
  opacity: 0;
  transform: scale(0.8);
}

/* Transition for re-ordering items when one is removed */
.list-item-move {
  transition: transform 0.4s ease;
}
</style>