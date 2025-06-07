<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow-sm sticky top-0 z-20">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center gap-4">
          <button
            @click="goBack"
            class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors"
          >
            <ArrowLeft class="h-5 w-5 text-gray-700" />
          </button>
          <div>
            <h1 class="text-2xl font-bold text-gray-900">
              {{ currentStep === 'cart' ? 'Your Order' : 'Checkout' }}
            </h1>
            <p class="text-gray-600">
              {{
                currentStep === 'cart'
                  ? `${cart.itemCount} items in your cart`
                  : 'Review and place your order'
              }}
            </p>
          </div>
        </div>
      </div>
      <!-- Progress Steps -->
      <div class="container mx-auto px-4 pb-4">
        <div class="flex items-center">
          <div
            class="flex-1 flex flex-col items-center text-center"
            :class="
              currentStep === 'cart' || currentStep === 'checkout'
                ? 'text-orange-500'
                : 'text-gray-400'
            "
          >
            <div
              :class="[
                'h-8 w-8 rounded-full flex items-center justify-center text-sm font-medium border-2 transition-all',
                currentStep === 'cart' || currentStep === 'checkout'
                  ? 'bg-orange-500 text-white border-orange-500'
                  : 'bg-gray-200 text-gray-600 border-gray-200',
              ]"
            >
              1
            </div>
            <span class="text-xs mt-1 font-medium">Cart</span>
          </div>
          <div
            class="flex-1 h-0.5"
            :class="
              currentStep === 'checkout' ? 'bg-orange-500' : 'bg-gray-200'
            "
          ></div>
          <div
            class="flex-1 flex flex-col items-center text-center"
            :class="
              currentStep === 'checkout' ? 'text-orange-500' : 'text-gray-400'
            "
          >
            <div
              :class="[
                'h-8 w-8 rounded-full flex items-center justify-center text-sm font-medium border-2 transition-all',
                currentStep === 'checkout'
                  ? 'bg-orange-500 text-white border-orange-500'
                  : 'bg-gray-200 text-gray-600 border-gray-200',
              ]"
            >
              2
            </div>
            <span class="text-xs mt-1 font-medium">Checkout</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Cart View -->
      <div
        v-if="currentStep === 'cart'"
        class="grid grid-cols-1 lg:grid-cols-3 gap-8"
      >
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-6">
          <div
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
          >
            <h3 class="text-lg font-bold text-gray-900 mb-6">Order Items</h3>
            <div class="space-y-4">
              <div
                v-for="item in cart.items"
                :key="item.id"
                class="flex items-center gap-4 py-4 border-b border-gray-100 last:border-b-0"
              >
                <img
                  :src="item.image || 'https://placehold.co/100x100/e2e8f0/4a5568?text=Item'"
                  :alt="item.name"
                  class="h-16 w-16 rounded-lg object-cover"
                />
                <div class="flex-1">
                  <h4 class="font-bold text-gray-900">{{ item.name }}</h4>
                  <p class="text-sm text-orange-500 font-bold">
                    {{ item.price.toFixed(2) }} zł
                  </p>
                </div>
                <div class="flex items-center gap-3">
                  <button
                    @click="cart.updateQuantity(item.id, item.quantity - 1)"
                    class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors"
                  >
                    <Minus class="h-4 w-4" />
                  </button>
                  <span class="w-8 text-center font-medium">{{
                    item.quantity
                  }}</span>
                  <button
                    @click="cart.updateQuantity(item.id, item.quantity + 1)"
                    class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors"
                  >
                    <Plus class="h-4 w-4" />
                  </button>
                </div>
                <button
                  @click="cart.removeFromCart(item.id)"
                  class="text-gray-400 hover:text-red-500 transition-colors"
                >
                  <Trash2 class="h-5 w-5" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-1">
          <div
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-24"
          >
            <h3 class="text-lg font-bold text-gray-900 mb-6">Order Summary</h3>
            <div class="space-y-3 border-t border-gray-200 pt-4">
              <div class="flex justify-between">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium"
                  >{{ cart.totalPrice.toFixed(2) }} zł</span
                >
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Delivery Fee</span>
                <span class="font-medium">{{ deliveryFee.toFixed(2) }} zł</span>
              </div>
            </div>
            <div class="border-t border-gray-200 pt-4 mt-4">
              <div class="flex justify-between text-lg font-bold">
                <span>Total</span>
                <span
                  >{{ (cart.totalPrice + deliveryFee).toFixed(2) }} zł</span
                >
              </div>
            </div>
            <button
              @click="proceedToCheckout"
              :disabled="cart.items.length === 0"
              class="w-full mt-6 bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium disabled:bg-gray-300 disabled:cursor-not-allowed"
            >
              Proceed to Checkout
            </button>
          </div>
        </div>
      </div>

      <!-- Checkout View -->
      <div
        v-else-if="currentStep === 'checkout'"
        class="grid grid-cols-1 lg:grid-cols-3 gap-8"
      >
        <!-- Checkout Form -->
        <div class="lg:col-span-2 space-y-6">
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">
              Delivery Address
            </h3>
            <div v-if="isLoadingAddresses" class="text-center">
              Loading addresses...
            </div>
            <div v-else class="space-y-4">
              <div
                v-for="address in savedAddresses"
                :key="address.Id"
                @click="selectedAddressId = address.Id"
                :class="[
                  'border rounded-lg p-4 cursor-pointer transition-colors',
                  selectedAddressId === address.Id
                    ? 'border-orange-500 bg-orange-50 ring-2 ring-orange-200'
                    : 'border-gray-200 hover:border-gray-300',
                ]"
              >
                <p class="font-medium text-gray-900">{{ address.Street }}</p>
                <p class="text-gray-600 text-sm">
                  {{ address.PostalCode }} {{ address.City }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Final Order Summary -->
        <div class="lg:col-span-1">
          <div
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-24"
          >
            <h3 class="text-lg font-bold text-gray-900 mb-6">Final Order</h3>
            <div class="border-t border-gray-200 pt-4 mt-4">
              <div class="flex justify-between text-lg font-bold">
                <span>Total</span>
                <span
                  >{{ (cart.totalPrice + deliveryFee).toFixed(2) }} zł</span
                >
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
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import {
  ArrowLeft,
  Star,
  Clock,
  Minus,
  Plus,
  Trash2,
  ShoppingBag,
} from 'lucide-vue-next';
import { useCartStore } from '../stores/cart';
import { useAuthStore } from '../stores/auth';
import { addressService, orderService } from '../services/index';
import type { AddressDto, CreateOrderDto } from '../services/types';

const router = useRouter();
const cart = useCartStore();
const auth = useAuthStore();

const currentStep = ref<'cart' | 'checkout'>('cart');
const deliveryFee = ref(5.99);

const savedAddresses = ref<AddressDto[]>([]);
const isLoadingAddresses = ref(false);
const selectedAddressId = ref<number | null>(null);

const isPlacingOrder = ref(false);
const orderError = ref<string | null>(null);

const canPlaceOrder = computed(() => {
  return selectedAddressId.value !== null && cart.items.length > 0;
});

const goBack = () => {
  if (currentStep.value === 'checkout') {
    currentStep.value = 'cart';
  } else {
    router.back();
  }
};

const proceedToCheckout = async () => {
  if (!auth.isAuthenticated) {
    router.push('/login');
    return;
  }

  isLoadingAddresses.value = true;
  try {
    const response = await addressService.getMyAddresses();
    savedAddresses.value = response.data;
    if (response.data.length > 0) {
      selectedAddressId.value = response.data[0].Id;
    } else {
      alert('Please add a delivery address to your profile first.');
      // Optionally redirect to profile page to add address
      // router.push('/profile');
      return; // Stop proceeding to checkout
    }
  } catch (error) {
    console.error('Failed to fetch addresses:', error);
    alert('Could not load your addresses. Please try again.');
    return; // Stop proceeding to checkout
  } finally {
    isLoadingAddresses.value = false;
  }
  currentStep.value = 'checkout';
};

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
    const newOrder = await orderService.createOrder(orderPayload);
    alert(`Order #${newOrder.Id} placed successfully!`);
    cart.clearCart();
    router.push('/');
  } catch (error: any) {
    orderError.value = error.message || 'Failed to place order.';
  } finally {
    isPlacingOrder.value = false;
  }
};
</script>