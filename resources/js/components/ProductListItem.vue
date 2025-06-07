<template>
  <div
    class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105 cursor-pointer"
    @click="onPress"
  >
    <div class="relative">
      <img :src="imageSource" class="w-full h-32 object-cover" alt="Product Image" />
      <button
        v-if="onToggleFavorite"
        @click.stop="handleFavoritePress"
        :disabled="isLoadingFavorite"
        class="absolute top-2 right-2 p-1.5 rounded-full bg-black/30 hover:bg-black/50 transition-colors"
      >
        <svg
          v-if="!isLoadingFavorite"
          xmlns="http://www.w3.org/2000/svg"
          width="20"
          height="20"
          viewBox="0 0 24 24"
          fill="none"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          :class="[
            'transition-all',
            isFavoriteLocal ? 'stroke-red-500 fill-red-500' : 'stroke-white',
          ]"
        >
          <path
            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
          ></path>
        </svg>
        <div
          v-else
          class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"
        ></div>
      </button>
    </div>
    <div class="p-4">
      <h3 class="font-semibold text-gray-800 truncate">{{ item.Name }}</h3>
      <p class="text-sm text-gray-500 mb-2">{{ item.Category?.Name }}</p>
      <div class="flex justify-between items-center">
        <p class="font-bold text-green-600">{{ item.Price.toFixed(2) }} zł</p>
        <button
          @click.stop="handleAddToCart"
          class="bg-green-100 text-green-700 p-2 rounded-full hover:bg-green-200 transition"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <path d="M16 10a4 4 0 0 1-8 0"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { useCartStore } from '../stores/cart';
import type { ProductDto } from '../services/types';

const props = defineProps<{
  item: ProductDto;
  onToggleFavorite?: (productId: string, newStatus: boolean) => Promise<void>;
}>();

const emit = defineEmits(['press']);

const cart = useCartStore();
const isFavoriteLocal = ref(props.item.isFavorite || false); // Assuming isFavorite is added to DTO
const isLoadingFavorite = ref(false);

watch(
  () => props.item.isFavorite,
  (newVal) => {
    isFavoriteLocal.value = newVal || false;
  },
);

const defaultImage = '/images/placeholder-restaurant.png';
const imageSource = computed(() =>
  props.item.ImageUrl && props.item.ImageUrl.startsWith('http')
    ? props.item.ImageUrl
    : defaultImage,
);

const onPress = () => {
  emit('press', props.item.Id);
};

const handleAddToCart = () => {
  cart.addToCart({
    id: props.item.Id.toString(),
    name: props.item.Name,
    price: props.item.Price,
    restaurantId: props.item.RestaurantId,
    image: imageSource.value,
  });
  alert(`${props.item.Name} added to cart!`);
};

const handleFavoritePress = async () => {
  if (isLoadingFavorite.value || !props.onToggleFavorite) return;
  isLoadingFavorite.value = true;
  const newStatus = !isFavoriteLocal.value;
  try {
    await props.onToggleFavorite(props.item.Id.toString(), newStatus);
    isFavoriteLocal.value = newStatus;
  } catch (error) {
    console.error('Failed to toggle favorite:', error);
  } finally {
    isLoadingFavorite.value = false;
  }
};
</script>