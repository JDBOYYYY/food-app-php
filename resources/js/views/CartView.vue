<template>
  <div class="min-h-screen bg-gray-100">
    <div class="bg-white shadow-sm">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <h1 class="text-3xl font-bold text-gray-900">Your Cart</h1>
        <p class="text-gray-600">{{ cart.itemCount }} items in your order</p>
      </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div
        v-if="cart.items.length === 0"
        class="text-center py-20 bg-white rounded-lg shadow-sm"
      >
        <ShoppingBag class="h-16 w-16 text-gray-300 mx-auto mb-4" />
        <h2 class="text-xl font-semibold text-gray-700">Your cart is empty</h2>
        <p class="text-gray-500 mt-2 mb-6">
          Looks like you haven't added anything yet.
        </p>
        <button
          @click="router.push('/')"
          class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium"
        >
          Start Shopping
        </button>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
          <div v-if="cart.getCartRestaurantName" class="mb-6 pb-4 border-b">
            <h3 class="text-xl font-bold text-gray-800">
              Order from: {{ cart.getCartRestaurantName }}
            </h3>
          </div>
          <div class="space-y-4">
            <div
              v-for="item in cart.items"
              :key="item.id"
              class="flex items-center gap-4 py-4 border-b border-gray-100 last:border-b-0"
            >
              <img
                :src="
                  item.image ||
                  'https://placehold.co/100x100/e2e8f0/4a5568?text=Item'
                "
                :alt="item.name"
                class="h-16 w-16 rounded-lg object-cover"
              />
              <div class="flex-1">
                <h4 class="font-bold text-gray-900">{{ item.name }}</h4>
                <p class="text-sm text-orange-500 font-bold">
                  {{ item.price.toFixed(2) }} zł
                </p>
              </div>
              <div class="flex items-center gap-3">
                <button
                  @click="cart.updateQuantity(item.id, item.quantity - 1)"
                  class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors"
                >
                  <Minus class="h-4 w-4" />
                </button>
                <span class="w-8 text-center font-medium">{{
                  item.quantity
                }}</span>
                <button
                  @click="cart.updateQuantity(item.id, item.quantity + 1)"
                  class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors"
                >
                  <Plus class="h-4 w-4" />
                </button>
              </div>
              <button
                @click="cart.removeFromCart(item.id)"
                class="text-gray-400 hover:text-red-500 transition-colors"
              >
                <Trash2 class="h-5 w-5" />
              </button>
            </div>
          </div>
        </div>

        <div class="lg:col-span-1">
          <div
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-24"
          >
            <h3 class="text-lg font-bold text-gray-900 mb-6">Order Summary</h3>
            <div class="space-y-3 border-t border-gray-200 pt-4">
              <div class="flex justify-between">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium"
                  >{{ cart.totalPrice.toFixed(2) }} zł</span
                >
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Delivery Fee</span>
                <span class="font-medium">{{ deliveryFee.toFixed(2) }} zł</span>
              </div>
            </div>
            <div class="border-t border-gray-200 pt-4 mt-4">
              <div class="flex justify-between text-lg font-bold">
                <span>Total</span>
                <span
                  >{{ (cart.totalPrice + deliveryFee).toFixed(2) }} zł</span
                >
              </div>
            </div>
            <button
              @click="proceedToCheckout"
              :disabled="cart.items.length === 0"
              class="w-full mt-6 bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium disabled:bg-gray-300 disabled:cursor-not-allowed"
            >
              Proceed to Checkout
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { Minus, Plus, Trash2, ShoppingBag } from "lucide-vue-next";
import { useCartStore } from "../stores/cart";
import { useAuthStore } from "../stores/auth";
import { useModalStore } from "../stores/modals";

const router = useRouter();
const cart = useCartStore();
const auth = useAuthStore();
const modal = useModalStore();

const deliveryFee = ref(5.99);

const proceedToCheckout = async () => {
  if (!auth.isAuthenticated) {
    const confirmed = await modal.showConfirm({
      title: "Login Required",
      message: "You must be logged in to proceed to checkout. Go to login page?",
      confirmText: "Go to Login",
    });
    if (confirmed) {
      router.push("/login");
    }
  } else {
    router.push("/checkout");
  }
};
</script>