<template>
  <div
    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
  >
    <div
      class="bg-white rounded-2xl p-8 max-w-md w-full text-center shadow-2xl transform transition-all"
      :class="showContent ? 'scale-100 opacity-100' : 'scale-95 opacity-0'"
    >
      <!-- Checkmark Icon -->
      <div
        class="h-20 w-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-green-200"
      >
        <Check class="h-12 w-12 text-green-600" />
      </div>

      <!-- Main Message -->
      <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
        Zamówienie Przyjęte!
      </h2>
      <p class="text-gray-600 mb-6">
        Twoje zamówienie <span class="font-bold text-orange-500">#{{ orderId }}</span> jest już w drodze!
      </p>

      <!-- Delivery Animation -->
      <div class="relative h-20 overflow-hidden mb-6">
        <div class="absolute inset-y-0 left-0 right-0 flex items-center">
          <div class="w-full h-1 bg-gray-200 rounded-full"></div>
        </div>
        <div
          class="absolute top-1/2 -translate-y-1/2 transition-all duration-[3000ms] ease-in-out"
          :style="{ left: scooterPosition }"
        >
          <img src="/public/images/delivery-scooter.svg" class="h-16 w-16" alt="Delivery Scooter" />
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="space-y-3">
        <button
          @click="trackOrder"
          class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium text-lg"
        >
          Śledź Zamówienie
        </button>
        <button
          @click="goToHome"
          class="w-full border border-gray-300 py-3 rounded-lg hover:bg-gray-100 transition-colors font-medium"
        >
          Kontynuuj Zakupy
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Check } from 'lucide-vue-next';

const props = defineProps<{
  orderId: number;
}>();

const emit = defineEmits(['trackOrder', 'goHome']);

const showContent = ref(false);
const scooterPosition = ref('-10%');

onMounted(() => {
  // Trigger animations after component mounts
  setTimeout(() => {
    showContent.value = true;
    scooterPosition.value = '110%';
  }, 100);
});

const trackOrder = () => {
  alert('Order tracking is not yet implemented.');
  emit('trackOrder');
};

const goToHome = () => {
  emit('goHome');
};
</script>