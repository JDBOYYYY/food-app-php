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
      <div
        v-for="item in filteredMenuItems"
        :key="item.Id"
        class="flex items-center gap-4"
      >
        <img
          :src="item.ImageUrl || '/images/placeholder-restaurant.png'"
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
        <button
          @click="emit('addToBasket', item)"
          class="self-start bg-orange-100 text-orange-600 p-2 rounded-full hover:bg-orange-200 transition-colors"
        >
          <Plus class="h-5 w-5" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Plus } from 'lucide-vue-next';
import type { ProductDto } from '../../services/types';

const props = defineProps<{
  products: ProductDto[];
  selectedCategory: number | null;
}>();

const emit = defineEmits(['update:selectedCategory', 'addToBasket']);

const menuCategories = computed(() => {
  if (!props.products) return [];
  const categories = new Map<number, string>();
  props.products.forEach((p) => {
    if (p.CategoryId && p.Category?.Name) {
      categories.set(p.CategoryId, p.Category.Name);
    }
  });
  return Array.from(categories, ([id, name]) => ({ id, name }));
});

const filteredMenuItems = computed(() => {
  if (!props.products) return [];
  if (props.selectedCategory === null) {
    return props.products;
  }
  return props.products.filter(
    (p: ProductDto) => p.CategoryId === props.selectedCategory,
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