<template>
  <aside
    class="w-64 flex-shrink-0 bg-gray-800 text-gray-300 flex flex-col"
  >
    <div class="h-16 flex items-center justify-center text-white text-xl font-bold">
      FoodApp Admin
    </div>
    <nav class="flex-1 px-2 py-4 space-y-1">
      <router-link
        v-for="item in navItems"
        :key="item.name"
        :to="item.path"
        class="flex items-center px-4 py-2 rounded-md text-sm font-medium transition-colors"
        active-class="bg-gray-900 text-white"
        exact-active-class="bg-gray-900 text-white"
      >
        {{ item.name }}
      </router-link>
    </nav>
    <div class="p-4 border-t border-gray-700">
      <router-link
        to="/"
        class="flex items-center px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700"
        >Back to Site</router-link
      >
    </div>
  </aside>
</template>

<script setup lang="ts">
const navItems = [
  { name: "Dashboard", path: "/admin" },
  { name: "Categories", path: "/admin/categories" },
  { name: "Products", path: "/admin/products" },
  { name: "Restaurants", path: "/admin/restaurants" },
  { name: "Orders", path: "/admin/orders" },
  { name: "Users", path: "/admin/users" },
];
</script>