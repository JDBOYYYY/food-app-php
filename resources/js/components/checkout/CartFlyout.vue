<template>
  <Transition name="fade">
    <div
      v-if="cart.isFlyoutOpen"
      @click="cart.closeFlyout"
      class="fixed inset-0 bg-black/60 z-40"
    ></div>
  </Transition>
  <Transition name="slide-in">
    <div
      v-if="cart.isFlyoutOpen"
      class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-xl z-50 flex flex-col"
    >
      <!-- Header -->
      <div class="flex items-center justify-between p-4 border-b">
        <div>
          <h2 class="text-lg font-bold text-gray-900">Your Cart</h2>
          <!-- RESTAURANT NAME SUBHEADER -->
          <p
            v-if="cart.getCartRestaurantName"
            class="text-sm text-gray-500 -mt-1"
          >
            From: {{ cart.getCartRestaurantName }}
          </p>
        </div>
        <button
          @click="cart.closeFlyout"
          class="p-2 text-gray-400 hover:text-gray-600"
        >
          <X class="h-6 w-6" />
        </button>
      </div>

      <!-- Empty State -->
      <div
        v-if="cart.itemCount === 0"
        class="flex-1 flex flex-col items-center justify-center text-center p-8"
      >
        <ShoppingBag class="h-16 w-16 text-gray-300 mb-4" />
        <h3 class="text-xl font-semibold text-gray-700">
          Your cart is empty
        </h3>
        <p class="text-gray-500 mt-2">
          Add some delicious food to get started!
        </p>
      </div>

      <!-- Cart Items -->
      <div v-else class="flex-1 overflow-y-auto p-4 space-y-4">
        <div v-for="item in cart.items" :key="item.id" class="flex gap-4">
          <img
            :src="
              item.image ||
              'https://placehold.co/100x100/e2e8f0/4a5568?text=Item'
            "
            :alt="item.name"
            class="h-20 w-20 rounded-lg object-cover"
          />
          <div class="flex-1">
            <h4 class="font-bold text-gray-800">{{ item.name }}</h4>
            <p class="text-sm text-orange-500 font-bold">
              {{ item.price.toFixed(2) }} zł
            </p>
            <div class="flex items-center gap-2 mt-2">
              <button
                @click="cart.updateQuantity(item.id, item.quantity - 1)"
                class="h-7 w-7 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors text-sm"
              >
                <Minus class="h-4 w-4" />
              </button>
              <span class="w-8 text-center font-medium">{{
                item.quantity
              }}</span>
              <button
                @click="cart.updateQuantity(item.id, item.quantity + 1)"
                class="h-7 w-7 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors text-sm"
              >
                <Plus class="h-4 w-4" />
              </button>
            </div>
          </div>
          <button
            @click="cart.removeFromCart(item.id)"
            class="text-gray-400 hover:text-red-500 p-1"
          >
            <Trash2 class="h-5 w-5" />
          </button>
        </div>
      </div>

      <!-- Footer -->
      <div v-if="cart.itemCount > 0" class="p-4 border-t bg-gray-50">
        <div class="flex justify-between font-bold text-lg mb-4">
          <span>Subtotal</span>
          <span>{{ cart.totalPrice.toFixed(2) }} zł</span>
        </div>
        <div class="space-y-3">
          <button
            @click="goTo('/cart')"
            class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium"
          >
            View Cart
          </button>
          <button
            @click="goTo('/checkout')"
            class="w-full border border-gray-300 py-3 rounded-lg hover:bg-gray-100 transition-colors font-medium"
          >
            Checkout
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { useRouter } from "vue-router";
import { useCartStore } from "@/stores/cart";
import { X, ShoppingBag, Plus, Minus, Trash2 } from "lucide-vue-next";

const cart = useCartStore();
const router = useRouter();

const goTo = (path) => {
  cart.closeFlyout();
  router.push(path);
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-in-enter-active,
.slide-in-leave-active {
  transition: transform 0.3s ease-out;
}
.slide-in-enter-from,
.slide-in-leave-to {
  transform: translateX(100%);
}
</style>