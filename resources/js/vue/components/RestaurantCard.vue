<template>
  <div class="bg-white rounded shadow-md overflow-hidden hover:shadow-lg transition">
    <img :src="restaurant.imageUrl || placeholder" class="w-full h-40 object-cover" />
    <div class="p-4">
      <h3 class="font-semibold text-lg mb-1">{{ restaurant.name }}</h3>
      <div class="text-sm text-gray-500 flex flex-wrap gap-2">
        <span v-if="restaurant.deliveryTime">{{ restaurant.deliveryTime }}</span>
        <span v-if="restaurant.distance">{{ restaurant.distance }}</span>
        <span v-if="restaurant.averageRating" class="flex items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-yellow-500"><path d="M12 17.3l-5.618 3.327 1.517-6.447L2.8 9.873l6.58-.558L12 3.2l2.62 6.115 6.58.558-5.098 4.307 1.517 6.447z"/></svg>
          {{ restaurant.averageRating.toFixed(1) }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  restaurant: { type: Object, required: true }
});
const placeholder = 'https://via.placeholder.com/400x300?text=No+Image';
</script>

