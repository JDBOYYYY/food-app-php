<template>
  <!-- Main container with dynamic classes -->
  <div
    :class="[
      'bg-white rounded-lg shadow-sm border border-gray-200 flex items-center gap-4 p-4',
      'hover:shadow-md hover:border-orange-300 transition-all',
    ]"
  >
    <img
      :src="imageSource"
      :alt="item.Name"
      class="w-20 h-20 rounded-md object-cover flex-shrink-0"
    />
    <div class="flex-1">
      <h4 class="font-bold text-gray-900">{{ item.Name }}</h4>
      <p class="text-gray-600 text-sm leading-relaxed mt-1">
        {{ item.Description }}
      </p>
      <p class="text-lg font-bold text-orange-500 mt-2">
        {{ item.Price.toFixed(2) }} zł
      </p>
    </div>
    <div class="flex flex-col items-center gap-4">
      <button
        @click.stop="emit('addToBasket', item)"
        class="bg-orange-100 text-orange-600 p-2 rounded-full hover:bg-orange-200 transition-colors"
      >
        <Plus class="h-5 w-5" />
      </button>
      <button
        v-if="onToggleFavorite"
        @click.stop="handleFavoritePress"
        :disabled="isLoadingFavorite"
        class="p-1.5"
      >
        <Heart
          v-if="!isLoadingFavorite"
          :size="20"
          :class="[
            'transition-all text-gray-400',
            isFavoriteLocal ? 'text-red-500 fill-red-500' : 'hover:text-red-400',
          ]"
        />
        <div
          v-else
          class="w-5 h-5 border-2 border-gray-300 border-t-transparent rounded-full animate-spin"
        ></div>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { useCartStore } from '../stores/cart';
import type { ProductDto } from '../services/types';
import { Heart, Plus } from 'lucide-vue-next';

const props = defineProps<{
  item: ProductDto & { isFavorite?: boolean };
  onToggleFavorite?: (productId: number, newStatus: boolean) => Promise<void>;
}>();

const emit = defineEmits(['addToBasket']);

const isFavoriteLocal = ref(props.item.isFavorite || false);
const isLoadingFavorite = ref(false);

watch(
  () => props.item.isFavorite,
  (newVal) => {
    isFavoriteLocal.value = newVal || false;
  },
);

const defaultImage = 'https://placehold.co/100x100/e2e8f0/4a5568?text=Item';
const imageSource = computed(() =>
  props.item.ImageUrl && props.item.ImageUrl.startsWith('http')
    ? props.item.ImageUrl
    : defaultImage,
);

const handleFavoritePress = async () => {
  if (isLoadingFavorite.value || !props.onToggleFavorite) return;
  isLoadingFavorite.value = true;
  const newStatus = !isFavoriteLocal.value;
  try {
    await props.onToggleFavorite(props.item.Id, newStatus);
    isFavoriteLocal.value = newStatus;
  } catch (error) {
    console.error('Failed to toggle favorite:', error);
  } finally {
    isLoadingFavorite.value = false;
  }
};
</script>