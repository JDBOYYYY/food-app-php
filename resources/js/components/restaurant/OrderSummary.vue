<template>
  <div class="lg:col-span-1">
    <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-24">
      <h3 class="text-lg font-bold text-gray-900 mb-4">Your Order</h3>
      <div v-if="basketItems.length === 0" class="text-center py-8">
        <ShoppingBag class="h-12 w-12 text-gray-300 mx-auto mb-3" />
        <p class="text-gray-500 mb-4">Your basket is empty</p>
        <p class="text-sm text-gray-400">
          Add items from the menu to get started
        </p>
      </div>
      <div v-else class="space-y-4">
        <div
          v-for="item in basketItems"
          :key="item.id"
          class="flex items-center justify-between py-2"
        >
          <div class="flex-1 pr-2">
            <p class="font-medium text-gray-900 truncate">{{ item.name }}</p>
            <p class="text-sm text-gray-600">{{ item.price.toFixed(2) }} zł</p>
          </div>
          <div class="flex items-center gap-2">
            <button
              @click="emit('updateQuantity', item.id, item.quantity - 1)"
              class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors"
            >
              <Minus class="h-3 w-3" />
            </button>
            <span class="w-8 text-center text-sm font-medium">{{
              item.quantity
            }}</span>
            <button
              @click="emit('updateQuantity', item.id, item.quantity + 1)"
              class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors"
            >
              <Plus class="h-3 w-3" />
            </button>
          </div>
        </div>
        <div class="border-t border-gray-200 pt-4">
          <div class="flex justify-between mb-2 text-sm">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-medium">{{ subtotal.toFixed(2) }} zł</span>
          </div>
          <div class="flex justify-between mb-2 text-sm">
            <span class="text-gray-600">Delivery Fee</span>
            <span class="font-medium">{{ deliveryFee.toFixed(2) }} zł</span>
          </div>
          <div
            class="flex justify-between font-bold text-lg border-t border-gray-200 pt-2 mt-2"
          >
            <span>Total</span>
            <span>{{ total.toFixed(2) }} zł</span>
          </div>
        </div>
        <button
          @click="goToCheckout"
          class="w-full bg-orange-500 text-white py-3 rounded-lg font-medium hover:bg-orange-600 transition-colors"
        >
          Proceed to Checkout
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
import { ShoppingBag, Minus, Plus } from 'lucide-vue-next';
import type { CartItem } from '../../stores/cart';

const props = defineProps<{
  basketItems: CartItem[];
}>();

const emit = defineEmits(['updateQuantity']);

const router = useRouter();

const deliveryFee = ref(3.99);

const subtotal = computed(() =>
  props.basketItems.reduce(
    (total, item) => total + item.price * item.quantity,
    0,
  ),
);

const total = computed(() => subtotal.value + deliveryFee.value);

const goToCheckout = () => {
  router.push('/cart');
};
</script>