<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section
      class="bg-gradient-to-br from-orange-500 via-red-500 to-pink-500 text-white"
    >
      <div class="container mx-auto px-4 py-16">
        <div class="max-w-2xl mx-auto text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-4">
            Delicious food, delivered fast
          </h1>
          <p class="text-xl mb-8 opacity-90">
            Order from your favorite restaurants and get it delivered in minutes
          </p>
          <div class="relative max-w-md mx-auto">
            <Search
              class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5"
            />
            <input
              type="text"
              placeholder="Search restaurants or dishes..."
              v-model="searchQuery"
              class="w-full pl-12 pr-4 py-3 text-gray-900 bg-white rounded-full border-0 shadow-lg focus:outline-none focus:ring-2 focus:ring-white/50"
            />
          </div>
        </div>
      </div>
    </section>

    <section class="py-8 bg-white">
      <!-- The container class is removed from the parent and applied to the header -->
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Browse by Category</h2>
        </div>
      </div>

      <!-- Changed from horizontal scroll to flex wrap -->
      <div class="container mx-auto px-4">
        <div
          class="flex flex-wrap gap-4"
          role="tablist"
          aria-orientation="horizontal"
        >
          <!-- "All" button is now a real button that clears the filter -->
          <button
            @click="selectedCategory = null"
            role="tab"
            :aria-selected="selectedCategory === null"
            :class="[
              'flex-shrink-0 px-4 py-2 rounded-full font-medium transition-colors',
              selectedCategory === null
                ? 'bg-orange-500 text-white shadow'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
            ]"
          >
            All
          </button>

          <!-- The rest of the category buttons - now toggleable -->
          <button
            v-for="category in categories"
            :key="category.Id"
            @click="
              selectedCategory =
                selectedCategory === category.Id ? null : category.Id
            "
            role="tab"
            :aria-selected="selectedCategory === category.Id"
            :class="[
              'flex-shrink-0 px-4 py-2 rounded-full font-medium transition-colors flex items-center',
              selectedCategory === category.Id
                ? 'bg-orange-500 text-white shadow'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
            ]"
          >
            <span class="mr-2">{{ category.icon }}</span>
            {{ category.Name }}
          </button>
        </div>
      </div>
    </section>

    <!-- All Restaurants Section -->
    <section class="py-12 bg-gray-50">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
          <h2 class="text-2xl font-bold text-gray-900">
            All Restaurants
            <span
              v-if="selectedCategory"
              class="text-lg font-normal text-gray-600 ml-2"
            >
              • {{ categories.find((c) => c.Id === selectedCategory)?.Name }}
            </span>
          </h2>
          <p class="text-gray-600">
            {{ filteredRestaurants.length }} restaurants found
          </p>
        </div>

        <div v-if="isLoading" class="text-center py-12">
          <div
            class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500 mx-auto"
          ></div>
          <p class="mt-4 text-gray-600">Loading restaurants...</p>
        </div>

        <div
          v-else-if="filteredRestaurants.length > 0"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
        >
          <RestaurantListItem
            v-for="restaurant in filteredRestaurants"
            :key="restaurant.id"
            :item="restaurant"
            @press="navigateToRestaurant"
            :onToggleFavorite="handleToggleFavorite"
          />
        </div>

        <div
          v-if="filteredRestaurants.length === 0 && !isLoading"
          class="text-center py-12"
        >
          <div class="text-6xl mb-4">🍽️</div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">
            No restaurants found
          </h3>
          <p class="text-gray-600 mb-4">
            Try adjusting your search or category filter
          </p>
          <button
            @click="clearFilters"
            class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors"
          >
            Clear Filters
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import { Search } from "lucide-vue-next";
import { restaurantService, favoriteService } from "../services/index";
import { useAuthStore } from "../stores/auth";
import RestaurantListItem from "../components/RestaurantListItem.vue";
import type { RestaurantListItemProps } from "../components/RestaurantListItem.vue";

const router = useRouter();
const auth = useAuthStore();
const allRestaurants = ref<RestaurantListItemProps[]>([]);
const categories = ref<any[]>([]);
const isLoading = ref(true);
const error = ref<string | null>(null);

const searchQuery = ref("");
const selectedCategory = ref<number | null>(null);

const filteredRestaurants = computed(() => {
  return allRestaurants.value.filter((restaurant) => {
    const matchesSearch = restaurant.name
      .toLowerCase()
      .includes(searchQuery.value.toLowerCase());
    const restaurantCategoryNames =
      restaurant.cuisineType?.split(", ").map((c) => c.toLowerCase()) || [];
    const selectedCategoryName = categories.value
      .find((c) => c.Id === selectedCategory.value)
      ?.Name.toLowerCase();
    const matchesCategory =
      selectedCategory.value === null ||
      (selectedCategoryName &&
        restaurantCategoryNames.includes(selectedCategoryName));
    return matchesSearch && matchesCategory;
  });
});

const navigateToRestaurant = (restaurantId: number) => {
  router.push(`/restaurant/${restaurantId}`);
};

const clearFilters = () => {
  searchQuery.value = "";
  selectedCategory.value = null;
};

const handleToggleFavorite = async (
  restaurantId: number,
  newStatus: boolean,
) => {
  if (!auth.isAuthenticated) {
    if (confirm("You must be logged in to add favorites. Go to login?")) {
      router.push("/login");
    }
    return;
  }

  const restaurant = allRestaurants.value.find((r) => r.id === restaurantId);
  if (!restaurant) return;

  try {
    if (newStatus) {
      await favoriteService.addRestaurantFavorite(restaurantId);
    } else {
      await favoriteService.removeRestaurantFavorite(restaurantId);
    }
    restaurant.isFavorite = newStatus;
  } catch (e: any) {
    console.error("Failed to toggle favorite:", e);
    alert(`Error: ${e.message || "Could not update favorites."}`);
  }
};

onMounted(async () => {
  isLoading.value = true;
  error.value = null;
  try {
    const data = await restaurantService.getHomePageData();
    allRestaurants.value = data.restaurants;
    categories.value = data.categories;
  } catch (e: any) {
    error.value = "Could not load data. Please try again later.";
    console.error("Failed to fetch home page data:", e);
  } finally {
    isLoading.value = false;
  }
});
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