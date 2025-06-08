<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Loading State -->
    <div v-if="isLoading" class="flex justify-center items-center h-screen">
      <div
        class="animate-spin rounded-full h-16 w-16 border-b-2 border-orange-500"
      ></div>
    </div>

    <!-- Error State -->
    <div
      v-else-if="error"
      class="flex flex-col justify-center items-center h-screen text-center px-4"
    >
      <h2 class="text-2xl font-bold text-red-600 mb-4">
        Could not load order
      </h2>
      <p class="text-gray-600 mb-6">{{ error }}</p>
      <button
        @click="router.back()"
        class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors"
      >
        Go Back
      </button>
    </div>

    <!-- Main Content -->
    <div v-else-if="order" class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="flex items-center gap-4 mb-8">
        <button
          @click="router.back()"
          class="h-10 w-10 rounded-full bg-white flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors border"
        >
          <ArrowLeft class="h-5 w-5" />
        </button>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">
            Order #{{ order.Id }}
          </h1>
          <p class="text-gray-600">
            Placed on {{ formatOrderDate(order.OrderDate) }}
          </p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Left Column: Order Items -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
          <h3 class="text-xl font-bold text-gray-900 mb-4">Items in Order</h3>
          <div class="space-y-4">
            <div
              v-for="item in order.OrderItems"
              :key="item.Id"
              class="flex items-center gap-4 py-4 border-b last:border-b-0"
            >
              <img
                :src="
                  item.product.ImageUrl ||
                  'https://placehold.co/100x100/e2e8f0/4a5568?text=Item'
                "
                :alt="item.product.Name"
                class="h-16 w-16 rounded-lg object-cover"
              />
              <div class="flex-1">
                <h4 class="font-bold text-gray-900">
                  {{ item.product.Name }}
                </h4>
                <p class="text-sm text-gray-500">
                  {{ item.Quantity }} x {{ parseFloat(item.UnitPrice).toFixed(2) }} zł
                </p>
              </div>
              <div class="font-bold text-gray-900">
                {{ (item.Quantity * item.UnitPrice).toFixed(2) }} zł
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: Summary & Address -->
        <div class="lg:col-span-1 space-y-6 sticky top-24">
          <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
              Shipping Address
            </h3>
            <div class="text-gray-700 space-y-1">
              <p>{{ order.ShippingAddress.Street }}</p>
              <p>
                {{ order.ShippingAddress.PostalCode }}
                {{ order.ShippingAddress.City }}
              </p>
              <p>{{ order.ShippingAddress.Country }}</p>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
              Order Summary
            </h3>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium"
                  >{{ order.TotalAmount.toFixed(2) }} zł</span
                >
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Delivery Fee</span>
                <span class="font-medium">0.00 zł</span>
              </div>
              <div
                class="flex justify-between text-lg font-bold border-t pt-2 mt-2"
              >
                <span>Total</span>
                <span>{{ order.TotalAmount.toFixed(2) }} zł</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { orderService } from "@/services";
import type { OrderDto } from "@/services/types";
import { ArrowLeft } from "lucide-vue-next";

const route = useRoute();
const router = useRouter();

const order = ref<OrderDto | null>(null);
const isLoading = ref(true);
const error = ref<string | null>(null);

const formatOrderDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString("en-GB", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

onMounted(async () => {
  const orderId = Number(route.params.id);
  if (isNaN(orderId)) {
    error.value = "Invalid Order ID provided.";
    isLoading.value = false;
    return;
  }

  try {
    const response = await orderService.getOrderById(orderId);
    order.value = response.data;
  } catch (e: any) {
    error.value = e.message || "Failed to fetch order details.";
  } finally {
    isLoading.value = false;
  }
});
</script>