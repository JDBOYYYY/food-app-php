<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
      <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Your Favorites</h1>
            <p class="text-gray-600">{{ favoriteRestaurants.length }} restaurants • {{ favoriteDishes.length }} dishes</p>
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
                    : 'text-gray-600 hover:text-gray-900'
                ]"
              >
                Restaurants
              </button>
              <button
                @click="viewMode = 'dishes'"
                :class="[
                  'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                  viewMode === 'dishes'
                    ? 'bg-white text-gray-900 shadow-sm'
                    : 'text-gray-600 hover:text-gray-900'
                ]"
              >
                Dishes
              </button>
            </div>
            
            <!-- Sort Dropdown -->
            <div class="relative">
              <button
                @click="showSortMenu = !showSortMenu"
                class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
              >
                <Filter class="h-4 w-4" />
                <span class="text-sm font-medium">{{ sortOptions.find(opt => opt.value === sortBy)?.label }}</span>
                <ChevronDown class="h-4 w-4" />
              </button>
              
              <div
                v-if="showSortMenu"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10"
              >
                <button
                  v-for="option in sortOptions"
                  :key="option.value"
                  @click="sortBy = option.value; showSortMenu = false"
                  :class="[
                    'w-full text-left px-4 py-2 text-sm hover:bg-gray-50 transition-colors',
                    sortBy === option.value ? 'text-orange-600 bg-orange-50' : 'text-gray-700'
                  ]"
                >
                  {{ option.label }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 py-8">
      <!-- Empty State -->
      <div v-if="currentItems.length === 0" class="text-center py-16">
        <Heart class="h-16 w-16 text-gray-300 mx-auto mb-4" />
        <h2 class="text-2xl font-bold text-gray-900 mb-2">No favorites yet</h2>
        <p class="text-gray-600 mb-6">
          {{ viewMode === 'restaurants' 
            ? 'Start exploring restaurants and add your favorites!' 
            : 'Save your favorite dishes for quick ordering!' 
          }}
        </p>
        <button
          @click="goToHome"
          class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium"
        >
          Explore Restaurants
        </button>
      </div>

      <!-- Restaurants View -->
      <div v-else-if="viewMode === 'restaurants'" class="space-y-6">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <Heart class="h-8 w-8 text-orange-500 mx-auto mb-2" />
            <p class="text-2xl font-bold text-gray-900">{{ favoriteRestaurants.length }}</p>
            <p class="text-sm text-gray-600">Favorite Restaurants</p>
          </div>
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <Star class="h-8 w-8 text-yellow-500 mx-auto mb-2" />
            <p class="text-2xl font-bold text-gray-900">{{ averageRating.toFixed(1) }}</p>
            <p class="text-sm text-gray-600">Average Rating</p>
          </div>
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <Package class="h-8 w-8 text-blue-500 mx-auto mb-2" />
            <p class="text-2xl font-bold text-gray-900">{{ totalOrders }}</p>
            <p class="text-sm text-gray-600">Total Orders</p>
          </div>
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <Clock class="h-8 w-8 text-green-500 mx-auto mb-2" />
            <p class="text-2xl font-bold text-gray-900">{{ averageDeliveryTime }}</p>
            <p class="text-sm text-gray-600">Avg. Delivery</p>
          </div>
        </div>

        <!-- Restaurants Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="restaurant in sortedRestaurants"
            :key="restaurant.Id"
            class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group"
          >
            <!-- Restaurant Image -->
            <div class="relative h-48">
              <img
                :src="restaurant.ImageUrl"
                :alt="restaurant.Name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-colors" />
              
              <!-- Favorite Button -->
              <button
                @click="toggleRestaurantFavorite(restaurant.Id)"
                class="absolute top-3 right-3 h-10 w-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center hover:bg-white/30 transition-colors"
              >
                <Heart class="h-5 w-5 text-red-500 fill-red-500" />
              </button>

              <!-- Status Badge -->
              <div class="absolute top-3 left-3">
                <span
                  :class="[
                    'px-3 py-1 rounded-full text-sm font-medium',
                    restaurant.isOpen ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
                  ]"
                >
                  {{ restaurant.isOpen ? 'Open' : 'Closed' }}
                </span>
              </div>

              <!-- Quick Order Button -->
              <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <button
                  @click="quickOrder(restaurant)"
                  class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium text-sm"
                >
                  Quick Order
                </button>
              </div>
            </div>

            <!-- Restaurant Info -->
            <div class="p-6">
              <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                  <h3 class="font-bold text-gray-900 text-lg mb-1">{{ restaurant.Name }}</h3>
                  <p class="text-gray-600 text-sm">
                    {{ restaurant.Categories.map(cat => cat.Name).join(' • ') }}
                  </p>
                </div>
              </div>

              <div class="flex items-center gap-4 mb-4">
                <div class="flex items-center gap-1">
                  <Star class="h-4 w-4 text-yellow-400 fill-yellow-400" />
                  <span class="text-sm font-medium">{{ restaurant.AverageRating }}</span>
                  <span class="text-gray-500 text-sm">({{ restaurant.reviewCount }}+)</span>
                </div>
                <div class="flex items-center gap-1 text-gray-500 text-sm">
                  <Clock class="h-4 w-4" />
                  <span>{{ restaurant.DeliveryTime }}</span>
                </div>
                <div class="flex items-center gap-1 text-gray-500 text-sm">
                  <MapPin class="h-4 w-4" />
                  <span>{{ restaurant.Distance }}</span>
                </div>
              </div>

              <!-- Last Order Info -->
              <div v-if="restaurant.lastOrder" class="bg-gray-50 rounded-lg p-3 mb-4">
                <p class="text-sm text-gray-600 mb-1">Last order: {{ restaurant.lastOrder.date }}</p>
                <p class="text-sm font-medium text-gray-900">{{ restaurant.lastOrder.items.join(', ') }}</p>
              </div>

              <!-- Action Buttons -->
              <div class="flex gap-3">
                <button
                  @click="viewRestaurant(restaurant)"
                  class="flex-1 bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium text-sm"
                >
                  View Menu
                </button>
                <button
                  v-if="restaurant.lastOrder"
                  @click="reorder(restaurant)"
                  class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium"
                >
                  Reorder
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Dishes View -->
      <div v-else class="space-y-6">
        <!-- Categories Filter -->
        <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
          <button
            v-for="category in dishCategories"
            :key="category"
            @click="selectedDishCategory = category"
            :class="[
              'flex-shrink-0 px-4 py-2 rounded-full font-medium transition-colors',
              selectedDishCategory === category
                ? 'bg-orange-500 text-white'
                : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'
            ]"
          >
            {{ category }}
          </button>
        </div>

        <!-- Dishes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="dish in filteredDishes"
            :key="dish.id"
            class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow"
          >
            <!-- Dish Image -->
            <div class="relative h-48">
              <img
                :src="dish.image"
                :alt="dish.name"
                class="w-full h-full object-cover"
              />
              
              <!-- Favorite Button -->
              <button
                @click="toggleDishFavorite(dish.id)"
                class="absolute top-3 right-3 h-10 w-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center hover:bg-white/30 transition-colors"
              >
                <Heart class="h-5 w-5 text-red-500 fill-red-500" />
              </button>

              <!-- Restaurant Badge -->
              <div class="absolute bottom-3 left-3">
                <span class="bg-black/60 text-white px-3 py-1 rounded-full text-sm font-medium">
                  {{ dish.restaurantName }}
                </span>
              </div>
            </div>

            <!-- Dish Info -->
            <div class="p-6">
              <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                  <h3 class="font-bold text-gray-900 text-lg mb-1">{{ dish.name }}</h3>
                  <p class="text-gray-600 text-sm leading-relaxed">{{ dish.description }}</p>
                </div>
              </div>

              <div class="flex items-center gap-4 mb-4">
                <span class="text-xl font-bold text-orange-500">${{ dish.price.toFixed(2) }}</span>
                <div v-if="dish.rating" class="flex items-center gap-1">
                  <Star class="h-4 w-4 text-yellow-400 fill-yellow-400" />
                  <span class="text-sm font-medium">{{ dish.rating }}</span>
                </div>
                <span v-if="dish.isPopular" class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full font-medium">
                  Popular
                </span>
              </div>

              <!-- Last Ordered -->
              <div v-if="dish.lastOrdered" class="bg-gray-50 rounded-lg p-3 mb-4">
                <p class="text-sm text-gray-600">Last ordered: {{ dish.lastOrdered }}</p>
              </div>

              <!-- Action Buttons -->
              <div class="flex gap-3">
                <button
                  @click="addToBasket(dish)"
                  class="flex-1 bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium text-sm"
                >
                  Add to Basket
                </button>
                <button
                  @click="viewRestaurant({ Id: dish.restaurantId })"
                  class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium"
                >
                  View Restaurant
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { 
  Heart, 
  Filter, 
  ChevronDown, 
  Star, 
  Clock, 
  MapPin, 
  Package,
  Plus
} from 'lucide-vue-next'

const viewMode = ref('restaurants')
const showSortMenu = ref(false)
const sortBy = ref('recent')
const selectedDishCategory = ref('All')

const sortOptions = ref([
  { value: 'recent', label: 'Recently Added' },
  { value: 'rating', label: 'Highest Rated' },
  { value: 'orders', label: 'Most Ordered' },
  { value: 'name', label: 'Name A-Z' },
  { value: 'distance', label: 'Nearest First' }
])

const favoriteRestaurants = ref([
  {
    Id: 1,
    Name: "Luigi's Pizza Place",
    ImageUrl: "https://images.unsplash.com/photo-1513104890138-7c749659a591?w=400&h=300&fit=crop",
    AverageRating: 4.5,
    reviewCount: 324,
    DeliveryTime: "30-45 min",
    Distance: "2.5 km",
    Categories: [{ Name: "Italian" }, { Name: "Pizza" }],
    isOpen: true,
    totalOrders: 12,
    lastOrder: {
      date: "3 days ago",
      items: ["Margherita Pizza", "Caesar Salad"]
    }
  },
  {
    Id: 2,
    Name: "Burger Palace",
    ImageUrl: "https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&h=300&fit=crop",
    AverageRating: 4.3,
    reviewCount: 189,
    DeliveryTime: "25-35 min",
    Distance: "1.8 km",
    Categories: [{ Name: "American" }, { Name: "Burgers" }],
    isOpen: true,
    totalOrders: 8,
    lastOrder: {
      date: "1 week ago",
      items: ["Classic Burger", "Fries"]
    }
  },
  {
    Id: 3,
    Name: "Sushi Express",
    ImageUrl: "https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=400&h=300&fit=crop",
    AverageRating: 4.7,
    reviewCount: 256,
    DeliveryTime: "40-55 min",
    Distance: "3.2 km",
    Categories: [{ Name: "Japanese" }, { Name: "Sushi" }],
    isOpen: false,
    totalOrders: 15,
    lastOrder: {
      date: "5 days ago",
      items: ["California Roll", "Salmon Sashimi"]
    }
  }
])

const favoriteDishes = ref([
  {
    id: 1,
    name: "Margherita Pizza",
    description: "Classic pizza with fresh tomato sauce, mozzarella cheese, and fresh basil leaves",
    price: 18.99,
    image: "https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?w=400&h=300&fit=crop",
    rating: 4.7,
    isPopular: true,
    restaurantName: "Luigi's Pizza Place",
    restaurantId: 1,
    category: "Pizza",
    lastOrdered: "3 days ago"
  },
  {
    id: 2,
    name: "Classic Burger",
    description: "Juicy beef patty with lettuce, tomato, onion, and our special sauce",
    price: 12.99,
    image: "https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop",
    rating: 4.4,
    restaurantName: "Burger Palace",
    restaurantId: 2,
    category: "Burgers",
    lastOrdered: "1 week ago"
  },
  {
    id: 3,
    name: "California Roll",
    description: "Fresh avocado, cucumber, and crab meat wrapped in seasoned rice and nori",
    price: 8.99,
    image: "https://images.unsplash.com/photo-1617196034796-73dfa7b1fd56?w=400&h=300&fit=crop",
    rating: 4.6,
    restaurantName: "Sushi Express",
    restaurantId: 3,
    category: "Sushi",
    lastOrdered: "5 days ago"
  },
  {
    id: 4,
    name: "Caesar Salad",
    description: "Crisp romaine lettuce with parmesan cheese, croutons, and Caesar dressing",
    price: 12.99,
    image: "https://images.unsplash.com/photo-1546793665-c74683f339c1?w=400&h=300&fit=crop",
    rating: 4.3,
    restaurantName: "Luigi's Pizza Place",
    restaurantId: 1,
    category: "Salads",
    lastOrdered: "3 days ago"
  }
])

const dishCategories = computed(() => {
  const categories = ['All', ...new Set(favoriteDishes.value.map(dish => dish.category))]
  return categories
})

const filteredDishes = computed(() => {
  if (selectedDishCategory.value === 'All') {
    return favoriteDishes.value
  }
  return favoriteDishes.value.filter(dish => dish.category === selectedDishCategory.value)
})

const sortedRestaurants = computed(() => {
  const restaurants = [...favoriteRestaurants.value]
  
  switch (sortBy.value) {
    case 'rating':
      return restaurants.sort((a, b) => b.AverageRating - a.AverageRating)
    case 'orders':
      return restaurants.sort((a, b) => b.totalOrders - a.totalOrders)
    case 'name':
      return restaurants.sort((a, b) => a.Name.localeCompare(b.Name))
    case 'distance':
      return restaurants.sort((a, b) => parseFloat(a.Distance) - parseFloat(b.Distance))
    default:
      return restaurants
  }
})

const currentItems = computed(() => {
  return viewMode.value === 'restaurants' ? favoriteRestaurants.value : favoriteDishes.value
})

const averageRating = computed(() => {
  if (favoriteRestaurants.value.length === 0) return 0
  const sum = favoriteRestaurants.value.reduce((acc, restaurant) => acc + restaurant.AverageRating, 0)
  return sum / favoriteRestaurants.value.length
})

const totalOrders = computed(() => {
  return favoriteRestaurants.value.reduce((acc, restaurant) => acc + restaurant.totalOrders, 0)
})

const averageDeliveryTime = computed(() => {
  if (favoriteRestaurants.value.length === 0) return '0 min'
  const times = favoriteRestaurants.value.map(r => {
    const match = r.DeliveryTime.match(/(\d+)-(\d+)/)
    return match ? (parseInt(match[1]) + parseInt(match[2])) / 2 : 30
  })
  const avg = times.reduce((acc, time) => acc + time, 0) / times.length
  return `${Math.round(avg)} min`
})

const toggleRestaurantFavorite = (restaurantId) => {
  favoriteRestaurants.value = favoriteRestaurants.value.filter(r => r.Id !== restaurantId)
}

const toggleDishFavorite = (dishId) => {
  favoriteDishes.value = favoriteDishes.value.filter(d => d.id !== dishId)
}

const viewRestaurant = (restaurant) => {
  console.log('Viewing restaurant:', restaurant)
  // Navigate to restaurant detail
}

const quickOrder = (restaurant) => {
  console.log('Quick order from:', restaurant.Name)
  // Handle quick order
}

const reorder = (restaurant) => {
  console.log('Reordering from:', restaurant.Name)
  // Handle reorder
}

const addToBasket = (dish) => {
  console.log('Adding to basket:', dish.name)
  // Handle add to basket
}

const goToHome = () => {
  console.log('Navigating to home')
  // Navigate to home page
}

onMounted(() => {
  // Initialize component
})
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