<template>
  <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Menu</h2>
    <div class="flex gap-2 overflow-x-auto pb-4 scrollbar-hide mb-6">
      <button
        @click="emit('update:selectedCategory', null)"
        :class="[
          'flex-shrink-0 px-4 py-2 rounded-full font-medium transition-colors text-sm',
          selectedCategory === null
            ? 'bg-orange-500 text-white'
            : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
        ]"
      >
        All
      </button>
      <button
        v-for="category in menuCategories"
        :key="category.id"
        @click="emit('update:selectedCategory', category.id)"
        :class="[
          'flex-shrink-0 px-4 py-2 rounded-full font-medium transition-colors text-sm',
          selectedCategory === category.id
            ? 'bg-orange-500 text-white'
            : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
        ]"
      >
        {{ category.name }}
      </button>
    </div>

    <div class="space-y-4">
      <template v-for="item in filteredMenuItems" :key="item.Id">
        <ProductListItem
          v-if="item"
          :item="item"
          @addToBasket="emit('addToBasket', item)"
          :onToggleFavorite="
            (productId, newStatus) =>
              emit('toggleProductFavorite', productId, newStatus)
          "
        />
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { ProductDto } from '../../services/types';
import ProductListItem from '../ProductListItem.vue';

const props = defineProps<{
  products: ProductDto[];
  selectedCategory: number | null;
}>();

const emit = defineEmits(['update:selectedCategory', 'addToBasket', 'toggleProductFavorite']);

const menuCategories = computed(() => {
  if (!props.products) return [];
  const categories = new Map<number, string>();
  props.products.forEach((p) => {
    if (p && p.CategoryId && p.Category?.Name) {
      categories.set(p.CategoryId, p.Category.Name);
    }
  });
  return Array.from(categories, ([id, name]) => ({ id, name }));
});

const filteredMenuItems = computed(() => {
  if (!Array.isArray(props.products)) return [];
  
  if (props.selectedCategory === null) {
    return props.products;
  }
  return props.products.filter(
    (p?: ProductDto) => p && p.CategoryId === props.selectedCategory,
  );
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