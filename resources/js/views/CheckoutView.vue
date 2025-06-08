<template>
  <div class="min-h-screen bg-gray-100">
    <div class="bg-white shadow-sm">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
        <p class="text-gray-600">Select your address and place the order</p>
      </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="lg:col-span-2 space-y-6">
          <AddressManager v-model="selectedAddressId" />
        </div>

        <div class="lg:col-span-1">
          <div
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-24"
          >
            <h3 class="text-lg font-bold text-gray-900 mb-6">Final Order</h3>
            <div class="border-t border-gray-200 pt-4">
              <div class="flex justify-between text-lg font-bold">
                <span>Total</span>
                <span>{{ cart.totalPrice.toFixed(2) }} zł</span>
              </div>
            </div>
            <button
              @click="placeOrder"
              :disabled="!canPlaceOrder || isPlacingOrder"
              class="w-full mt-6 bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium disabled:bg-gray-300 disabled:cursor-not-allowed"
            >
              <span v-if="isPlacingOrder">Placing Order...</span>
              <span v-else>Place Order</span>
            </button>
            <p v-if="orderError" class="text-red-500 text-sm mt-2 text-center">
              {{ orderError }}
            </p>
          </div>
        </div>
      </div>
    </div>
    <OrderConfirmation
      v-if="showOrderConfirmation && confirmedOrder"
      :orderId="confirmedOrder.Id"
      @trackOrder="trackOrder"
      @goHome="goToHome"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useCartStore } from "../stores/cart";
import { orderService } from "../services/index";
import type { CreateOrderDto, OrderDto } from "../services/types";
import AddressManager from "../components/checkout/AddressManager.vue";
import OrderConfirmation from "../components/checkout/OrderConfirmation.vue";

const router = useRouter();
const cart = useCartStore();

const selectedAddressId = ref<number | null>(null);
const isPlacingOrder = ref(false);
const orderError = ref<string | null>(null);

const showOrderConfirmation = ref(false);
const confirmedOrder = ref<OrderDto | null>(null);

const canPlaceOrder = computed(() => {
  return selectedAddressId.value !== null && cart.items.length > 0;
});

const placeOrder = async () => {
  if (!canPlaceOrder.value) return;

  isPlacingOrder.value = true;
  orderError.value = null;

  const orderPayload: CreateOrderDto = {
    ShippingAddressId: selectedAddressId.value!,
    items: cart.items.map((item) => ({
      ProductId: Number(item.id),
      Quantity: item.quantity,
    })),
  };

  try {
    const response = await orderService.createOrder(orderPayload);
    const newOrder = response.data;

    cart.clearCart();
    confirmedOrder.value = newOrder;
    showOrderConfirmation.value = true;
  } catch (error: any) {
    orderError.value = error.message || "Failed to place order.";
  } finally {
    isPlacingOrder.value = false;
  }
};

const trackOrder = () => {
  showOrderConfirmation.value = false;
  router.push({
    name: "DeliveryTracker",
    params: { orderId: confirmedOrder.value?.Id },
  });
};

const goToHome = () => {
  showOrderConfirmation.value = false;
  router.push("/");
};
</script>