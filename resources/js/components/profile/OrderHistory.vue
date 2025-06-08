<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Historia Zamówień</h2>

    <!-- Stan ładowania -->
    <div v-if="ordersLoading" class="text-center text-gray-500 py-8">
      <p>Ładowanie historii zamówień...</p>
    </div>

    <!-- Stan błędu -->
    <div v-else-if="ordersError" class="text-center text-red-500 py-8">
      <p>{{ ordersError }}</p>
    </div>

    <!-- Stan z danymi (lub pusty) -->
    <div v-else>
      <div v-if="orders.length === 0" class="text-center text-gray-500 py-8">
        <p>Nie masz jeszcze żadnych zamówień.</p>
      </div>
      <div v-else class="space-y-4">
        <div
          v-for="order in orders"
          :key="order.Id"
          class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
        >
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-4">
              <div
                class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center"
              >
                <Package class="h-6 w-6 text-gray-400" />
              </div>
              <div>
                <h3 class="font-bold text-gray-900">
                  Zamówienie #{{ order.Id }}
                </h3>
                <p class="text-sm text-gray-600">
                  {{ formatOrderDate(order.OrderDate) }}
                </p>
              </div>
            </div>
            <div class="text-right">
              <p class="font-bold text-gray-900">
                {{ order.TotalAmount.toFixed(2) }} zł
              </p>
              <p class="text-sm text-gray-500">
                {{ order.OrderItems?.length || 0 }} produkt(y)
              </p>
            </div>
          </div>
          <div class="flex items-center justify-between">
            <span
              :class="[
                'px-3 py-1 rounded-full text-sm font-medium',
                getStatusClass(order.Status),
              ]"
            >
              {{ order.Status }}
            </span>
            <!-- UPDATED BUTTON -->
            <button
              @click="goToDetails(order.Id)"
              class="text-orange-500 text-sm font-medium hover:text-orange-600 transition-colors"
            >
              Zobacz szczegóły
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { orderService } from "@/services/orderService";
import { Package } from "lucide-vue-next";

const router = useRouter();
const orders = ref([]);
const ordersLoading = ref(false);
const ordersError = ref(null);

const goToDetails = (orderId) => {
  router.push(`/orders/${orderId}`);
};

async function loadOrders() {
  ordersLoading.value = true;
  ordersError.value = null;
  try {
    const response = await orderService.getMyOrders();
    orders.value = response.data || [];
  } catch (e) {
    console.error("Nie udało się pobrać zamówień:", e);
    ordersError.value = "Nie można załadować historii zamówień.";
  } finally {
    ordersLoading.value = false;
  }
}

function formatOrderDate(dateString) {
  return new Date(dateString).toLocaleDateString("pl-PL", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
}

function getStatusClass(status) {
  const statusClasses = {
    completed: "bg-green-100 text-green-800",
    cancelled: "bg-red-100 text-red-800",
    shipped: "bg-blue-100 text-blue-800",
    processing: "bg-purple-100 text-purple-800",
    pending: "bg-yellow-100 text-yellow-800",
  };
  return statusClasses[status.toLowerCase()] || "bg-gray-100 text-gray-800";
}

onMounted(loadOrders);
</script>