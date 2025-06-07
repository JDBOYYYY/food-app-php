<template>
  <div class="flex h-screen bg-gray-100">
    <AdminSidebar />
    <div class="flex-1 flex flex-col overflow-hidden">
      <header
        class="flex justify-between items-center p-4 bg-white border-b"
      >
        <h1 class="text-xl font-semibold">Admin Panel</h1>
        <div class="text-sm">
          Logged in as:
          <span class="font-medium">{{ auth.user?.name }}</span>
        </div>
      </header>
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import AdminSidebar from "./AdminSidebar.vue"
import { useAuthStore } from "../stores/auth";
const auth = useAuthStore();
</script>