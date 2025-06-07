<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Restaurant Hero Section -->
    <div class="relative h-80 bg-gray-900">
      <img
        :src="restaurant.ImageUrl"
        :alt="restaurant.Name"
        class="w-full h-full object-cover"
      />
      <div class="absolute inset-0 bg-black/40" />
      
      <!-- Back Button -->
      <button
        @click="goBack"
        class="absolute top-6 left-6 h-10 w-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white hover:bg-white/30 transition-colors"
      >
        <ArrowLeft class="h-5 w-5" />
      </button>

      <!-- Favorite Button -->
      <button
        @click="toggleFavorite"
        class="absolute top-6 right-6 h-10 w-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center hover:bg-white/30 transition-colors"
      >
        <Heart
          :class="[
            'h-5 w-5 transition-colors',
            restaurant.isFavorite ? 'text-red-500 fill-red-500' : 'text-white'
          ]"
        />
      </button>

      <!-- Restaurant Info Overlay -->
      <div class="absolute bottom-6 left-6 right-6">
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <h1 class="text-3xl font-bold text-white mb-2">{{ restaurant.Name }}</h1>
            <p class="text-gray-200 text-lg mb-3">
              {{ restaurant.Categories.map(cat => cat.Name).join(' • ') }}
            </p>
            <div class="flex items-center gap-6">
              <div class="flex items-center gap-1">
                <Star class="h-5 w-5 text-yellow-400 fill-yellow-400" />
                <span class="text-white font-medium">{{ restaurant.AverageRating }}</span>
                <span class="text-gray-300">({{ restaurant.reviewCount }}+ reviews)</span>
              </div>
              <div class="flex items-center gap-1 text-gray-200">
                <Clock class="h-4 w-4" />
                <span>{{ restaurant.DeliveryTime }}</span>
              </div>
              <div class="flex items-center gap-1 text-gray-200">
                <MapPin class="h-4 w-4" />
                <span>{{ restaurant.Distance }}</span>
              </div>
            </div>
          </div>
          <span
            :class="[
              'px-3 py-1 rounded-full text-sm font-medium ml-4',
              restaurant.isOpen ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            ]"
          >
            {{ restaurant.isOpen ? 'Open' : 'Closed' }}
          </span>
        </div>
      </div>
    </div>

    <!-- Restaurant Details -->
    <section class="py-8 bg-white">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Restaurant Info -->
          <div class="lg:col-span-2">
            <div class="mb-6">
              <h2 class="text-2xl font-bold text-gray-900 mb-4">About {{ restaurant.Name }}</h2>
              <p class="text-gray-600 leading-relaxed">{{ restaurant.description }}</p>
            </div>
            
            <!-- Features -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
              <div class="text-center p-4 bg-gray-50 rounded-lg">
                <Truck class="h-6 w-6 text-orange-500 mx-auto mb-2" />
                <p class="text-sm font-medium text-gray-900">Free Delivery</p>
                <p class="text-xs text-gray-600">On orders over $30</p>
              </div>
              <div class="text-center p-4 bg-gray-50 rounded-lg">
                <Clock class="h-6 w-6 text-orange-500 mx-auto mb-2" />
                <p class="text-sm font-medium text-gray-900">Fast Delivery</p>
                <p class="text-xs text-gray-600">{{ restaurant.DeliveryTime }}</p>
              </div>
              <div class="text-center p-4 bg-gray-50 rounded-lg">
                <Star class="h-6 w-6 text-orange-500 mx-auto mb-2" />
                <p class="text-sm font-medium text-gray-900">Top Rated</p>
                <p class="text-xs text-gray-600">{{ restaurant.AverageRating }}/5 stars</p>
              </div>
              <div class="text-center p-4 bg-gray-50 rounded-lg">
                <Shield class="h-6 w-6 text-orange-500 mx-auto mb-2" />
                <p class="text-sm font-medium text-gray-900">Safe & Hygienic</p>
                <p class="text-xs text-gray-600">Certified kitchen</p>
              </div>
            </div>
          </div>
          
          <!-- Order Summary Sidebar -->
          <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-24">
              <h3 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h3>
              
              <div v-if="basketItems.length === 0" class="text-center py-8">
                <ShoppingBag class="h-12 w-12 text-gray-300 mx-auto mb-3" />
                <p class="text-gray-500 mb-4">Your basket is empty</p>
                <p class="text-sm text-gray-400">Add items from the menu below</p>
              </div>
              
              <div v-else class="space-y-4">
                <div
                  v-for="item in basketItems"
                  :key="item.id"
                  class="flex items-center justify-between py-2"
                >
                  <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ item.name }}</p>
                    <p class="text-sm text-gray-600">${{ item.price.toFixed(2) }} each</p>
                  </div>
                  <div class="flex items-center gap-2">
                    <button
                      @click="updateQuantity(item.id, item.quantity - 1)"
                      class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors"
                    >
                      <Minus class="h-3 w-3" />
                    </button>
                    <span class="w-8 text-center text-sm font-medium">{{ item.quantity }}</span>
                    <button
                      @click="updateQuantity(item.id, item.quantity + 1)"
                      class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors"
                    >
                      <Plus class="h-3 w-3" />
                    </button>
                  </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                  <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium">${{ subtotal.toFixed(2) }}</span>
                  </div>
                  <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Delivery Fee</span>
                    <span class="font-medium">${{ deliveryFee.toFixed(2) }}</span>
                  </div>
                  <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-2">
                    <span>Total</span>
                    <span>${{ total.toFixed(2) }}</span>
                  </div>
                </div>
                
                <button
                  class="w-full bg-orange-500 text-white py-3 rounded-lg font-medium hover:bg-orange-600 transition-colors"
                >
                  Proceed to Checkout
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Menu Categories -->
    <section class="py-8 bg-gray-50">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-gray-900">Menu</h2>
        </div>
        
        <!-- Category Filter -->
        <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide mb-8">
          <button
            v-for="category in menuCategories"
            :key="category.id"
            @click="selectedCategory = category.id"
            :class="[
              'flex-shrink-0 px-4 py-2 rounded-full font-medium transition-colors',
              selectedCategory === category.id
                ? 'bg-orange-500 text-white'
                : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'
            ]"
          >
            {{ category.name }}
          </button>
        </div>

        <!-- Menu Items Grid -->
        <div class="space-y-8">
          <div v-for="category in filteredMenuItems" :key="category.id">
            <h3 class="text-xl font-bold text-gray-900 mb-4">{{ category.name }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div
                v-for="item in category.items"
                :key="item.id"
                class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1 pr-4">
                    <h4 class="font-bold text-gray-900 mb-2">{{ item.name }}</h4>
                    <p class="text-gray-600 text-sm mb-3 leading-relaxed">{{ item.description }}</p>
                    <div class="flex items-center gap-4 mb-4">
                      <span class="text-lg font-bold text-orange-500">${{ item.price.toFixed(2) }}</span>
                      <div v-if="item.rating" class="flex items-center gap-1">
                        <Star class="h-4 w-4 text-yellow-400 fill-yellow-400" />
                        <span class="text-sm text-gray-600">{{ item.rating }}</span>
                      </div>
                      <span v-if="item.isPopular" class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full font-medium">
                        Popular
                      </span>
                    </div>
                    <button
                      @click="addToBasket(item)"
                      class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium"
                    >
                      Add to Basket
                    </button>
                  </div>
                  <div class="flex-shrink-0">
                    <img
                      :src="item.image"
                      :alt="item.name"
                      class="w-24 h-24 rounded-lg object-cover"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { 
  ArrowLeft, 
  Heart, 
  Star, 
  Clock, 
  MapPin, 
  Truck, 
  Shield, 
  ShoppingBag, 
  Minus, 
  Plus 
} from 'lucide-vue-next'

const restaurant = ref({})
const selectedCategory = ref('all')
const basketItems = ref([])
const deliveryFee = ref(3.99)

const menuCategories = ref([
  { id: 'all', name: 'All Items' },
  { id: 'appetizers', name: 'Appetizers' },
  { id: 'mains', name: 'Main Courses' },
  { id: 'desserts', name: 'Desserts' },
  { id: 'drinks', name: 'Drinks' },
])

const menuItems = ref([
  {
    id: 'appetizers',
    name: 'Appetizers',
    items: [
      {
        id: 1,
        name: 'Garlic Bread',
        description: 'Fresh baked bread with garlic butter, herbs, and melted mozzarella cheese',
        price: 8.99,
        image: 'https://images.unsplash.com/photo-1573140247632-f8fd74997d5c?w=200&h=200&fit=crop',
        rating: 4.5,
        isPopular: true
      },
      {
        id: 2,
        name: 'Caesar Salad',
        description: 'Crisp romaine lettuce with parmesan cheese, croutons, and our signature Caesar dressing',
        price: 12.99,
        image: 'https://images.unsplash.com/photo-1546793665-c74683f339c1?w=200&h=200&fit=crop',
        rating: 4.3
      }
    ]
  },
  {
    id: 'mains',
    name: 'Main Courses',
    items: [
      {
        id: 3,
        name: 'Margherita Pizza',
        description: 'Classic pizza with fresh tomato sauce, mozzarella cheese, and fresh basil leaves',
        price: 18.99,
        image: 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?w=200&h=200&fit=crop',
        rating: 4.7,
        isPopular: true
      },
      {
        id: 4,
        name: 'Pepperoni Pizza',
        description: 'Traditional pepperoni pizza with mozzarella cheese and our signature tomato sauce',
        price: 21.99,
        image: 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=200&h=200&fit=crop',
        rating: 4.6,
        isPopular: true
      },
      {
        id: 5,
        name: 'Chicken Alfredo Pasta',
        description: 'Grilled chicken breast with creamy alfredo sauce over fresh fettuccine pasta',
        price: 19.99,
        image: 'https://images.unsplash.com/photo-1621996346565-e3dbc353d2e5?w=200&h=200&fit=crop',
        rating: 4.4
      }
    ]
  },
  {
    id: 'desserts',
    name: 'Desserts',
    items: [
      {
        id: 6,
        name: 'Tiramisu',
        description: 'Classic Italian dessert with coffee-soaked ladyfingers and mascarpone cream',
        price: 7.99,
        image: 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=200&h=200&fit=crop',
        rating: 4.8
      },
      {
        id: 7,
        name: 'Chocolate Lava Cake',
        description: 'Warm chocolate cake with a molten chocolate center, served with vanilla ice cream',
        price: 8.99,
        image: 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=200&h=200&fit=crop',
        rating: 4.6
      }
    ]
  },
  {
    id: 'drinks',
    name: 'Drinks',
    items: [
      {
        id: 8,
        name: 'Coca Cola',
        description: 'Classic refreshing cola drink served chilled',
        price: 2.99,
        image: 'https://images.unsplash.com/photo-1629203851122-3726ecdf080e?w=200&h=200&fit=crop'
      },
      {
        id: 9,
        name: 'Fresh Orange Juice',
        description: 'Freshly squeezed orange juice, no added sugar',
        price: 4.99,
        image: 'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?w=200&h=200&fit=crop'
      }
    ]
  }
])

const filteredMenuItems = computed(() => {
  if (selectedCategory.value === 'all') {
    return menuItems.value
  }
  return menuItems.value.filter(category => category.id === selectedCategory.value)
})

const subtotal = computed(() => {
  return basketItems.value.reduce((total, item) => total + (item.price * item.quantity), 0)
})

const total = computed(() => {
  return subtotal.value + deliveryFee.value
})

const toggleFavorite = () => {
  restaurant.value.isFavorite = !restaurant.value.isFavorite
}

const addToBasket = (item) => {
  const existingItem = basketItems.value.find(i => i.id === item.id)
  
  if (existingItem) {
    existingItem.quantity += 1
  } else {
    basketItems.value.push({
      ...item,
      quantity: 1
    })
  }
}

const updateQuantity = (itemId, newQuantity) => {
  if (newQuantity <= 0) {
    basketItems.value = basketItems.value.filter(item => item.id !== itemId)
  } else {
    const item = basketItems.value.find(i => i.id === itemId)
    if (item) {
      item.quantity = newQuantity
    }
  }
}

const goBack = () => {
  window.history.back()
}

onMounted(() => {
  // Mock restaurant data
  restaurant.value = {
    Id: 3,
    Name: "Luigi's Pizza Place",
    ImageUrl: "https://images.unsplash.com/photo-1513104890138-7c749659a591?w=1200&h=600&fit=crop",
    AverageRating: 4.5,
    reviewCount: 324,
    DeliveryTime: "30-45 min",
    Distance: "2.5 km",
    Categories: [{ Name: "Italian" }, { Name: "Pizza" }, { Name: "Pasta" }],
    description: "Authentic Italian cuisine in the heart of the city. Luigi's has been serving traditional recipes passed down through generations since 1985. We use only the finest ingredients imported directly from Italy to ensure an authentic dining experience.",
    isFavorite: false,
    isOpen: true
  }
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