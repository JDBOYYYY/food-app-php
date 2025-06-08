<template>
  <div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow-sm">
      <div class="container mx-auto px-4 py-8">
        <div class="flex items-center gap-6">
          <div class="relative">
            <div
              class="h-24 w-24 bg-orange-500 rounded-full flex items-center justify-center"
            >
              <User class="h-12 w-12 text-white" v-if="!user.avatar" />
              <img
                v-else
                :src="user.avatar"
                :alt="user.name"
                class="h-24 w-24 rounded-full object-cover"
              />
            </div>
            <button
              class="absolute bottom-0 right-0 h-8 w-8 bg-orange-500 rounded-full flex items-center justify-center text-white hover:bg-orange-600 transition-colors"
            >
              <Camera class="h-4 w-4" />
            </button>
          </div>
          <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
              {{ user.name }}
            </h1>
            <p class="text-gray-600 mb-1">{{ user.email }}</p>
            <p class="text-sm text-gray-500">
              Użytkownik od {{ user.memberSince }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1">
          <div
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
          >
            <nav class="space-y-2">
              <button
                v-for="tab in tabs"
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                  'w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-colors',
                  activeTab === tab.id
                    ? 'bg-orange-50 text-orange-600 border border-orange-200'
                    : 'text-gray-700 hover:bg-gray-50',
                ]"
              >
                <component :is="tab.icon" class="h-5 w-5" />
                <span class="font-medium">{{ tab.name }}</span>
              </button>
            </nav>
          </div>
        </div>

        <div class="lg:col-span-3">
          <component :is="activeComponent" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { User, Camera, Package, MapPin, Shield } from "lucide-vue-next";

import PersonalInfo from "@/components/profile/PersonalInfo.vue";
import AddressManager from "@/components/profile/AddressManager.vue";
import OrderHistory from "@/components/profile/OrderHistory.vue";
import SecuritySettings from "@/components/profile/SecuritySettings.vue";

const authStore = useAuthStore();
const route = useRoute();
const activeTab = ref("personal");

const tabs = ref([
  { id: "personal", name: "Dane Osobowe", icon: User, component: PersonalInfo },
  { id: "addresses", name: "Adresy", icon: MapPin, component: AddressManager },
  { id: "orders", name: "Historia Zamówień", icon: Package, component: OrderHistory },
  { id: "security", name: "Bezpieczeństwo", icon: Shield, component: SecuritySettings },
]);

const activeComponent = computed(() => {
  const currentTab = tabs.value.find((tab) => tab.id === activeTab.value);
  return currentTab ? currentTab.component : null;
});

const user = computed(() => {
  if (!authStore.user)
    return {
      name: "Ładowanie...",
      email: "...",
      created_at: new Date().toISOString(),
    };
  return {
    ...authStore.user,
    avatar: null,
    memberSince: new Date(authStore.user.created_at).toLocaleDateString(
      "pl-PL",
      { year: "numeric", month: "long" },
    ),
  };
});

watch(activeTab, (newTab) => {
  localStorage.setItem("lastProfileTab", newTab);
});

onMounted(() => {
  const queryTab = route.query.tab;
  const savedTab = localStorage.getItem("lastProfileTab");
  const validTabIds = tabs.value.map((t) => t.id);

  if (queryTab && validTabIds.includes(queryTab)) {
    activeTab.value = queryTab;
  } else if (savedTab && validTabIds.includes(savedTab)) {
    activeTab.value = savedTab;
  }
});
</script>