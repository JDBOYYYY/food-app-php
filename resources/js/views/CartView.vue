<template>
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-6">Your Cart</h1>
    <div v-if="cart.items.length === 0" class="text-center py-10">
      <p>Your cart is empty.</p>
    </div>
    <div v-else>
      <div class="space-y-4">
        <div
          v-for="item in cart.items"
          :key="item.id"
          class="flex items-center justify-between bg-white p-4 rounded-lg shadow"
        >
          <div>
            <h3 class="font-bold">{{ item.name }}</h3>
            <p>{{ item.price.toFixed(2) }} zł</p>
          </div>
          <div class="flex items-center space-x-4">
            <p>Qty: {{ item.quantity }}</p>
            <button
              @click="cart.removeFromCart(item.id)"
              class="text-red-500 hover:text-red-700"
            >
              Remove
            </button>
          </div>
        </div>
      </div>
      <div class="mt-6 text-right">
        <p class="text-xl font-bold">
          Total: {{ cart.totalPrice.toFixed(2) }} zł
        </p>
        <button
          class="mt-4 bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700"
        >
          Checkout
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useCartStore } from "../stores/cart";
const cart = useCartStore();
</script>