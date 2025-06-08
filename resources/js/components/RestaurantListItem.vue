<template>
  <div
    @click="onPress"
    class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all hover:scale-[1.02] cursor-pointer"
  >
    <div class="relative">
      <img
        :src="imageSource"
        :alt="item.name"
        class="w-full h-48 object-cover"
      />
      <div
        class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"
      />

      <!-- Status and Promo Badges -->
      <div class="absolute top-3 left-3 flex gap-2">
        <span
          v-if="item.isOpen !== undefined"
          :class="[
            'px-2 py-1 rounded text-xs font-medium',
            item.isOpen ? 'bg-green-500 text-white' : 'bg-red-500 text-white',
          ]"
        >
          {{ item.isOpen ? "Open" : "Closed" }}
        </span>
        <span
          v-if="item.promoLabel"
          class="px-2 py-1 rounded text-xs font-medium bg-yellow-400 text-yellow-900"
        >
          {{ item.promoLabel }}
        </span>
      </div>

      <!-- Favorite Button -->
      <button
        v-if="onToggleFavorite"
        @click.stop="handleFavoritePress"
        :disabled="isLoadingFavorite"
        class="absolute top-3 right-3 h-8 w-8 rounded-full bg-black/20 hover:bg-black/40 transition-colors flex items-center justify-center"
      >
        <Heart
          v-if="!isLoadingFavorite"
          :size="16"
          :class="[
            'transition-colors',
            isFavoriteLocal ? 'text-red-500 fill-red-500' : 'text-white',
          ]"
        />
        <div
          v-else
          class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"
        ></div>
      </button>

      <!-- Restaurant Info Overlay -->
      <div class="absolute bottom-3 left-3 right-3">
        <h3 class="text-white font-bold text-lg mb-1 drop-shadow-md">
          {{ item.name }}
        </h3>
        <p class="text-gray-200 text-sm drop-shadow-sm">
          {{ item.cuisineType }}
        </p>
      </div>
    </div>

    <div class="p-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="flex items-center gap-1">
            <Star :size="16" class="text-yellow-400 fill-yellow-400" />
            <span class="text-sm font-medium">{{
              item.rating?.toFixed(1)
            }}</span>
          </div>
          <div class="flex items-center gap-1 text-gray-600">
            <Clock :size="16" />
            <span class="text-sm">{{ item.deliveryTime }}</span>
          </div>
        </div>
        <span
          v-if="item.priceRange"
          class="px-2 py-1 text-xs border border-gray-300 rounded"
        >
          {{ item.priceRange }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { useRouter } from "vue-router";
import { Heart, Star, Clock } from "lucide-vue-next";
import { useAuthStore } from "../stores/auth";

export interface RestaurantListItemProps {
  id: number;
  name: string;
  imageUrl?: string | null;
  cuisineType?: string | null;
  rating?: number | null;
  deliveryTime?: string | null;
  distance?: string | null;
  priceRange?: string | null;
  isOpen?: boolean;
  promoLabel?: string | null;
  isFavorite?: boolean;
  categories?: { Id: number; Name: string }[];
}

const props = defineProps<{
  item: RestaurantListItemProps;
  onToggleFavorite?: (itemId: number, newStatus: boolean) => Promise<void>;
}>();

const emit = defineEmits(["press"]);
const router = useRouter();
const auth = useAuthStore();

const isFavoriteLocal = ref(props.item.isFavorite || false);
const isLoadingFavorite = ref(false);

watch(
  () => props.item.isFavorite,
  (newVal) => {
    isFavoriteLocal.value = newVal || false;
  },
);

const defaultImage = "/images/placeholder-restaurant.png";
const imageSource = computed(() =>
  props.item.imageUrl && props.item.imageUrl.startsWith("http")
    ? props.item.imageUrl
    : defaultImage,
);

const onPress = () => {
  emit("press", props.item.id);
};

const handleFavoritePress = async () => {
  if (!auth.isAuthenticated) {
    if (
      confirm(
        "You must be logged in to add favorites. Would you like to go to the login page?",
      )
    ) {
      router.push("/login");
    }
    return;
  }

  if (isLoadingFavorite.value || !props.onToggleFavorite) return;

  isLoadingFavorite.value = true;
  const newStatus = !isFavoriteLocal.value;

  try {
    // Call the function passed from the parent component
    await props.onToggleFavorite(props.item.id, newStatus);
    isFavoriteLocal.value = newStatus;
  } catch (error: any) {
    console.error("Failed to toggle favorite:", error);
    alert(`Error: ${error.message || "Could not update favorites."}`);
  } finally {
    isLoadingFavorite.value = false;
  }
};
</script>