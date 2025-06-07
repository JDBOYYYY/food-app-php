<template>
  <header class="bg-white shadow-sm sticky top-0 z-50">
    <nav
      class="container mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16"
    >
      <router-link to="/" class="text-2xl font-bold text-green-600">
        FoodApp
      </router-link>
      <div class="hidden md:flex items-center space-x-6">
        <router-link to="/" class="nav-link">Home</router-link>
        <router-link to="/favorites" class="nav-link">Favorites</router-link>
        <router-link v-if="auth.isAdmin" to="/admin" class="nav-link"
          >Admin Panel</router-link
        >
      </div>
      <div class="flex items-center space-x-4">
        <router-link to="/cart" class="relative p-2">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            class="text-gray-600"
          >
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path
              d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"
            ></path>
          </svg>
          <span
            v-if="cart.itemCount > 0"
            class="absolute -top-1 -right-1 bg-green-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
          >
            {{ cart.itemCount }}
          </span>
        </router-link>
        <div v-if="auth.isAuthenticated" class="flex items-center">
          <router-link to="/profile" class="nav-link">{{
            auth.user?.name || "Profile"
          }}</router-link>
          <button @click="auth.signOut()" class="nav-link ml-4">Logout</button>
        </div>
        <div v-else class="hidden sm:flex items-center space-x-4">
          <router-link to="/login" class="nav-link">Login</router-link>
          <router-link
            to="/register"
            class="bg-green-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-600 transition"
            >Register</router-link
          >
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
import { useAuthStore } from "../../stores/auth";
import { useCartStore } from "../../stores/cart";
const auth = useAuthStore();
const cart = useCartStore();
</script>

<style scoped>
.nav-link {
  @apply text-gray-600 hover:text-green-600 text-sm font-medium transition-colors;
}
</style>