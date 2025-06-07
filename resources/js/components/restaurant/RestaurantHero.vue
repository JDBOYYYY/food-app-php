<template>
  <div class="relative h-64 md:h-80 bg-gray-700">
    <img
      :src="restaurant.ImageUrl || '/images/placeholder-restaurant.png'"
      :alt="restaurant.Name"
      class="w-full h-full object-cover"
    />
    <div
      class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
    />
    <div
      class="absolute top-0 left-0 right-0 flex justify-between items-center p-4"
    >
      <button
        @click="$emit('goBack')"
        class="h-10 w-10 rounded-full bg-black/30 backdrop-blur-sm flex items-center justify-center text-white hover:bg-black/50 transition-colors"
      >
        <ArrowLeft class="h-5 w-5" />
      </button>
      <button
        @click="$emit('toggleFavorite')"
        class="h-10 w-10 rounded-full bg-black/30 backdrop-blur-sm flex items-center justify-center hover:bg-black/50 transition-colors"
      >
        <Heart
          :class="[
            'h-5 w-5 transition-colors',
            restaurant.isFavorite ? 'text-red-500 fill-red-500' : 'text-white',
          ]"
        />
      </button>
    </div>
    <div class="absolute bottom-0 left-0 right-0 p-6">
      <h1 class="text-4xl font-bold text-white mb-1 drop-shadow-lg">
        {{ restaurant.Name }}
      </h1>
      <p class="text-gray-200 text-lg mb-3 drop-shadow">
        {{ restaurant.Categories?.map((cat) => cat.Name).join(' • ') }}
      </p>
      <div class="flex items-center gap-4 text-white text-sm">
        <div class="flex items-center gap-1.5">
          <Star class="h-4 w-4 text-yellow-400 fill-yellow-400" />
          <span class="font-medium">{{
            restaurant.AverageRating?.toFixed(1)
          }}</span>
        </div>
        <div class="flex items-center gap-1.5">
          <Clock class="h-4 w-4" />
          <span>{{ restaurant.DeliveryTime }}</span>
        </div>
        <span
          :class="[
            'px-2 py-0.5 rounded-full text-xs font-medium',
            restaurant.isOpen
              ? 'bg-green-500/90 text-white'
              : 'bg-red-500/90 text-white',
          ]"
        >
          {{ restaurant.isOpen ? 'Open' : 'Closed' }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ArrowLeft, Heart, Star, Clock } from 'lucide-vue-next';
import type { RestaurantDetailDto } from '../../services/types';

defineProps<{
  restaurant: RestaurantDetailDto;
}>();

defineEmits(['goBack', 'toggleFavorite']);
</script>