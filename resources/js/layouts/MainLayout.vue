<template>
  <div class="min-h-screen bg-gray-100">
    <Navbar />
    <main>
      <router-view />
    </main>
    <CartFlyout />
  </div>
</template>

<script setup lang="ts">
import Navbar from "@/layouts/Navbar.vue";
import CartFlyout from "@/components/checkout/CartFlyout.vue";
</script>