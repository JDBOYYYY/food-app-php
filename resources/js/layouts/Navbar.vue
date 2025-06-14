<template>
  <nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center">
          <a href="/" class="text-2xl font-bold text-gray-900"> FoodApp </a>
        </div>

        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-8">
            <a
              href="/"
              :class="[
                'px-3 py-2 rounded-md text-sm font-medium transition-colors',
                currentRoute === 'home'
                  ? 'text-orange-500 bg-orange-50'
                  : 'text-gray-700 hover:text-orange-500 hover:bg-gray-50',
              ]"
            >
              Home
            </a>
            <a
              href="/favorites"
              :class="[
                'px-3 py-2 rounded-md text-sm font-medium transition-colors',
                currentRoute === 'favorites'
                  ? 'text-orange-500 bg-orange-50'
                  : 'text-gray-700 hover:text-orange-500 hover:bg-gray-50',
              ]"
            >
              Favorites
            </a>
          </div>
        </div>

        <div class="flex items-center space-x-4">
          <button
            @click="cartStore.openFlyout()"
            class="relative p-2 text-gray-700 hover:text-orange-500 transition-colors"
          >
            <ShoppingBag class="h-6 w-6" />
            <span
              v-if="cartItemCount > 0"
              class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium"
            >
              {{ cartItemCount }}
            </span>
          </button>

          <div
            v-if="authStore.isAuthenticated && authStore.user"
            class="relative"
          >
            <button
              @click="showUserMenu = !showUserMenu"
              class="flex items-center space-x-2 p-2 rounded-md hover:bg-gray-50 transition-colors"
            >
              <div
                class="h-8 w-8 bg-orange-500 rounded-full flex items-center justify-center"
              >
                <User class="h-5 w-5 text-white" />
              </div>
              <span class="text-sm font-medium text-gray-700">{{
                authStore.user.name
              }}</span>
              <ChevronDown class="h-4 w-4 text-gray-500" />
            </button>

            <div
              v-if="showUserMenu"
              class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
            >
              <router-link
                :to="{ path: '/profile', query: { tab: 'personal' } }"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
              >
                <User class="inline h-4 w-4 mr-2" />
                Dane Osobowe
              </router-link>
              <router-link
                :to="{ path: '/profile', query: { tab: 'addresses' } }"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
              >
                <MapPin class="inline h-4 w-4 mr-2" />
                Adresy
              </router-link>
              <router-link
                :to="{ path: '/profile', query: { tab: 'orders' } }"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
              >
                <Package class="inline h-4 w-4 mr-2" />
                Historia Zamówień
              </router-link>
              <router-link
                :to="{ path: '/profile', query: { tab: 'security' } }"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
              >
                <Settings class="inline h-4 w-4 mr-2" />
                Bezpieczeństwo
              </router-link>
              <hr class="my-1" />
              <button
                @click="handleLogout"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
              >
                <LogOut class="inline h-4 w-4 mr-2" />
                Sign out
              </button>
            </div>
          </div>

          <div v-else class="flex items-center space-x-3">
            <router-link
              to="/login"
              class="text-sm font-medium text-gray-700 hover:text-orange-500 transition-colors"
            >
              Sign in
            </router-link>
            <router-link
              to="/register"
              class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-600 transition-colors"
            >
              Sign up
            </router-link>
          </div>

          <button
            @click="showMobileMenu = !showMobileMenu"
            class="md:hidden p-2 rounded-md text-gray-700 hover:text-orange-500 hover:bg-gray-50 transition-colors"
          >
            <Menu v-if="!showMobileMenu" class="h-6 w-6" />
            <X v-else class="h-6 w-6" />
          </button>
        </div>
      </div>

      <div v-if="showMobileMenu" class="md:hidden border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
          <a
            href="/"
            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-500 hover:bg-gray-50 transition-colors"
          >
            Home
          </a>
          <a
            href="/favorites"
            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-500 hover:bg-gray-50 transition-colors"
          >
            Favorites
          </a>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useCartStore } from "@/stores/cart";
import {
  ShoppingBag,
  User,
  ChevronDown,
  Menu,
  X,
  Package,
  Settings,
  LogOut,
  MapPin,
} from "lucide-vue-next";

const authStore = useAuthStore();
const cartStore = useCartStore();

const currentRoute = ref("home");
const cartItemCount = computed(() => cartStore.itemCount);
const showUserMenu = ref(false);
const showMobileMenu = ref(false);

const handleLogout = () => {
  authStore.signOut();
  showUserMenu.value = false;
};

const handleClickOutside = (event) => {
  if (showUserMenu.value && !event.target.closest(".relative")) {
    showUserMenu.value = false;
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>