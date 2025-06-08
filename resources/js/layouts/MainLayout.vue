<template>
  <div class="min-h-screen bg-gray-100">
    <header class="bg-white shadow-md sticky top-0 z-50">
<!--      <nav-->
<!--        class="container mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16"-->
<!--      >-->
<!--        <router-link to="/" class="text-2xl font-bold text-green-600">-->
<!--          FoodApp-->
<!--        </router-link>-->
<!--        <div class="hidden md:flex items-center space-x-6">-->
<!--          <router-link to="/" class="nav-link">Home</router-link>-->
<!--          <router-link to="/restaurants" class="nav-link"-->
<!--            >Restaurants</router-link-->
<!--          >-->
<!--          <router-link to="/favorites" class="nav-link"-->
<!--            >Favorites</router-link-->
<!--          >-->
<!--        </div>-->
<!--        <div class="flex items-center space-x-4">-->
<!--          <div v-if="auth.isAuthenticated">-->
<!--            <router-link to="/profile" class="nav-link">{{-->
<!--              auth.user?.name || "profile"-->
<!--            }}</router-link>-->
<!--            <button @click="auth.signOut()" class="nav-link ml-4">-->
<!--              Logout-->
<!--            </button>-->
<!--          </div>-->
<!--          <div v-else class="space-x-4">-->
<!--            <router-link to="/login" class="nav-link">Login</router-link>-->
<!--            <router-link-->
<!--              to="/register"-->
<!--              class="bg-green-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-600 transition"-->
<!--              >Register</router-link-->
<!--            >-->
<!--          </div>-->
<!--        </div>-->
<!--      </nav>-->
        <Navbar />
    </header>
    <main>
      <router-view />
    </main>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from "../stores/auth";
import Navbar from "@/layouts/Navbar.vue";

const auth = useAuthStore();
</script>

<style scoped>
.nav-link {
  @apply text-gray-600 hover:text-green-600 text-sm font-medium transition-colors;
}
</style>
