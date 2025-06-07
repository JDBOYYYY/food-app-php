<template>
  <div class="bg-white rounded shadow-md overflow-hidden flex flex-col hover:shadow-lg transition">
    <img :src="product.image || placeholder" class="w-full h-32 object-cover" />
    <div class="p-3 flex flex-col flex-1">
      <h4 class="font-semibold mb-1 text-sm">{{ product.name }}</h4>
      <p class="text-xs text-gray-500 flex-1" v-if="product.description">{{ product.description }}</p>
      <div class="mt-2 flex justify-between items-center">
        <span class="font-bold text-green-700">{{ product.price }} zł</span>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  product: { type: Object, required: true }
});
const placeholder = 'https://via.placeholder.com/300x200?text=No+Image';
</script>

