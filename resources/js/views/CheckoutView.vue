<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
      <div class="container mx-auto px-4 py-6">
        <div class="flex items-center gap-4">
          <button
            @click="goBack"
            class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors"
          >
            <ArrowLeft class="h-5 w-5" />
          </button>
          <div>
            <h1 class="text-2xl font-bold text-gray-900">
              {{ currentStep === 'cart' ? 'Your Order' : 'Checkout' }}
            </h1>
            <p class="text-gray-600">
              {{ currentStep === 'cart' ? `${cartItems.length} items` : 'Review and place your order' }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Progress Steps -->
    <div class="bg-white border-b border-gray-200">
      <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-center">
          <div class="flex items-center gap-8">
            <!-- Cart Step -->
            <div class="flex items-center gap-3">
              <div
                :class="[
                  'h-8 w-8 rounded-full flex items-center justify-center text-sm font-medium',
                  currentStep === 'cart' || currentStep === 'checkout'
                    ? 'bg-orange-500 text-white'
                    : 'bg-gray-200 text-gray-600'
                ]"
              >
                1
              </div>
              <span
                :class="[
                  'text-sm font-medium',
                  currentStep === 'cart' || currentStep === 'checkout'
                    ? 'text-orange-600'
                    : 'text-gray-500'
                ]"
              >
                Cart
              </span>
            </div>
            
            <div class="h-px w-16 bg-gray-300"></div>
            
            <!-- Checkout Step -->
            <div class="flex items-center gap-3">
              <div
                :class="[
                  'h-8 w-8 rounded-full flex items-center justify-center text-sm font-medium',
                  currentStep === 'checkout'
                    ? 'bg-orange-500 text-white'
                    : 'bg-gray-200 text-gray-600'
                ]"
              >
                2
              </div>
              <span
                :class="[
                  'text-sm font-medium',
                  currentStep === 'checkout'
                    ? 'text-orange-600'
                    : 'text-gray-500'
                ]"
              >
                Checkout
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 py-8">
      <!-- Cart View -->
      <div v-if="currentStep === 'cart'" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Restaurant Header -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-4">
              <img
                :src="restaurant.image"
                :alt="restaurant.name"
                class="h-16 w-16 rounded-lg object-cover"
              />
              <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900">{{ restaurant.name }}</h2>
                <p class="text-gray-600">{{ restaurant.category }}</p>
                <div class="flex items-center gap-4 mt-2">
                  <div class="flex items-center gap-1">
                    <Star class="h-4 w-4 text-yellow-400 fill-yellow-400" />
                    <span class="text-sm font-medium">{{ restaurant.rating }}</span>
                  </div>
                  <div class="flex items-center gap-1 text-gray-500 text-sm">
                    <Clock class="h-4 w-4" />
                    <span>{{ restaurant.deliveryTime }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Cart Items List -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Order Items</h3>
            
            <div class="space-y-4">
              <div
                v-for="item in cartItems"
                :key="item.id"
                class="flex items-center gap-4 py-4 border-b border-gray-100 last:border-b-0"
              >
                <img
                  :src="item.image"
                  :alt="item.name"
                  class="h-16 w-16 rounded-lg object-cover"
                />
                
                <div class="flex-1">
                  <h4 class="font-bold text-gray-900">{{ item.name }}</h4>
                  <p class="text-gray-600 text-sm mb-2">{{ item.description }}</p>
                  <div class="flex items-center gap-4">
                    <span class="font-bold text-orange-500">${{ item.price.toFixed(2) }}</span>
                    <div v-if="item.customizations?.length" class="text-xs text-gray-500">
                      {{ item.customizations.join(', ') }}
                    </div>
                  </div>
                </div>
                
                <!-- Quantity Controls -->
                <div class="flex items-center gap-3">
                  <button
                    @click="updateQuantity(item.id, item.quantity - 1)"
                    class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors"
                  >
                    <Minus class="h-4 w-4" />
                  </button>
                  <span class="w-8 text-center font-medium">{{ item.quantity }}</span>
                  <button
                    @click="updateQuantity(item.id, item.quantity + 1)"
                    class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors"
                  >
                    <Plus class="h-4 w-4" />
                  </button>
                </div>
                
                <!-- Remove Button -->
                <button
                  @click="removeItem(item.id)"
                  class="text-gray-400 hover:text-red-500 transition-colors"
                >
                  <Trash2 class="h-5 w-5" />
                </button>
              </div>
            </div>

            <!-- Add More Items -->
            <button
              @click="addMoreItems"
              class="w-full mt-6 py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-orange-300 hover:text-orange-600 transition-colors font-medium"
            >
              <Plus class="h-5 w-5 inline mr-2" />
              Add more items
            </button>
          </div>

          <!-- Special Instructions -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Special Instructions</h3>
            <textarea
              v-model="specialInstructions"
              placeholder="Any special requests for your order? (e.g., extra spicy, no onions, etc.)"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors resize-none"
              rows="3"
            ></textarea>
          </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-24">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Order Summary</h3>
            
            <!-- Items Summary -->
            <div class="space-y-3 mb-6">
              <div
                v-for="item in cartItems"
                :key="item.id"
                class="flex justify-between text-sm"
              >
                <span class="text-gray-600">{{ item.quantity }}x {{ item.name }}</span>
                <span class="font-medium">${{ (item.price * item.quantity).toFixed(2) }}</span>
              </div>
            </div>
            
            <!-- Pricing Breakdown -->
            <div class="space-y-3 border-t border-gray-200 pt-4">
              <div class="flex justify-between">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium">${{ subtotal.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Delivery Fee</span>
                <span class="font-medium">${{ deliveryFee.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Service Fee</span>
                <span class="font-medium">${{ serviceFee.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Tax</span>
                <span class="font-medium">${{ tax.toFixed(2) }}</span>
              </div>
              <div v-if="discount > 0" class="flex justify-between text-green-600">
                <span>Discount</span>
                <span>-${{ discount.toFixed(2) }}</span>
              </div>
            </div>
            
            <!-- Total -->
            <div class="border-t border-gray-200 pt-4 mt-4">
              <div class="flex justify-between text-lg font-bold">
                <span>Total</span>
                <span>${{ total.toFixed(2) }}</span>
              </div>
              <p class="text-sm text-gray-500 mt-1">Estimated delivery: {{ estimatedDelivery }}</p>
            </div>
            
            <!-- Promo Code -->
            <div class="mt-6">
              <div class="flex gap-2">
                <input
                  v-model="promoCode"
                  placeholder="Promo code"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors text-sm"
                />
                <button
                  @click="applyPromoCode"
                  class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium"
                >
                  Apply
                </button>
              </div>
            </div>
            
            <!-- Checkout Button -->
            <button
              @click="proceedToCheckout"
              :disabled="cartItems.length === 0"
              class="w-full mt-6 bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium disabled:bg-gray-300 disabled:cursor-not-allowed"
            >
              Proceed to Checkout
            </button>
          </div>
        </div>
      </div>

      <!-- Checkout View -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Delivery Address -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Delivery Address</h3>
            
            <div class="space-y-4">
              <div
                v-for="address in savedAddresses"
                :key="address.id"
                @click="selectedAddress = address.id"
                :class="[
                  'border rounded-lg p-4 cursor-pointer transition-colors',
                  selectedAddress === address.id
                    ? 'border-orange-500 bg-orange-50'
                    : 'border-gray-200 hover:border-gray-300'
                ]"
              >
                <div class="flex items-start justify-between">
                  <div class="flex items-center gap-3">
                    <div
                      :class="[
                        'h-4 w-4 rounded-full border-2 transition-colors',
                        selectedAddress === address.id
                          ? 'border-orange-500 bg-orange-500'
                          : 'border-gray-300'
                      ]"
                    ></div>
                    <div>
                      <p class="font-medium text-gray-900">{{ address.label }}</p>
                      <p class="text-gray-600 text-sm">{{ address.fullAddress }}</p>
                      <p class="text-gray-500 text-xs mt-1">{{ address.instructions }}</p>
                    </div>
                  </div>
                  <span v-if="address.isDefault" class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full">
                    Default
                  </span>
                </div>
              </div>
              
              <button class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-orange-300 hover:text-orange-600 transition-colors font-medium">
                <Plus class="h-5 w-5 inline mr-2" />
                Add new address
              </button>
            </div>
          </div>

          <!-- Delivery Time -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Delivery Time</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div
                @click="deliveryOption = 'asap'"
                :class="[
                  'border rounded-lg p-4 cursor-pointer transition-colors',
                  deliveryOption === 'asap'
                    ? 'border-orange-500 bg-orange-50'
                    : 'border-gray-200 hover:border-gray-300'
                ]"
              >
                <div class="flex items-center gap-3">
                  <div
                    :class="[
                      'h-4 w-4 rounded-full border-2 transition-colors',
                      deliveryOption === 'asap'
                        ? 'border-orange-500 bg-orange-500'
                        : 'border-gray-300'
                    ]"
                  ></div>
                  <div>
                    <p class="font-medium text-gray-900">ASAP</p>
                    <p class="text-gray-600 text-sm">{{ estimatedDelivery }}</p>
                  </div>
                </div>
              </div>
              
              <div
                @click="deliveryOption = 'scheduled'"
                :class="[
                  'border rounded-lg p-4 cursor-pointer transition-colors',
                  deliveryOption === 'scheduled'
                    ? 'border-orange-500 bg-orange-50'
                    : 'border-gray-200 hover:border-gray-300'
                ]"
              >
                <div class="flex items-center gap-3">
                  <div
                    :class="[
                      'h-4 w-4 rounded-full border-2 transition-colors',
                      deliveryOption === 'scheduled'
                        ? 'border-orange-500 bg-orange-500'
                        : 'border-gray-300'
                    ]"
                  ></div>
                  <div>
                    <p class="font-medium text-gray-900">Schedule</p>
                    <p class="text-gray-600 text-sm">Choose delivery time</p>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-if="deliveryOption === 'scheduled'" class="mt-4 grid grid-cols-2 gap-4">
              <input
                v-model="scheduledDate"
                type="date"
                class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
              />
              <select
                v-model="scheduledTime"
                class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
              >
                <option value="">Select time</option>
                <option value="12:00">12:00 PM</option>
                <option value="12:30">12:30 PM</option>
                <option value="13:00">1:00 PM</option>
                <option value="13:30">1:30 PM</option>
                <option value="14:00">2:00 PM</option>
                <option value="14:30">2:30 PM</option>
                <option value="18:00">6:00 PM</option>
                <option value="18:30">6:30 PM</option>
                <option value="19:00">7:00 PM</option>
                <option value="19:30">7:30 PM</option>
                <option value="20:00">8:00 PM</option>
              </select>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Payment Method</h3>
            
            <div class="space-y-4">
              <div
                v-for="payment in paymentMethods"
                :key="payment.id"
                @click="selectedPayment = payment.id"
                :class="[
                  'border rounded-lg p-4 cursor-pointer transition-colors',
                  selectedPayment === payment.id
                    ? 'border-orange-500 bg-orange-50'
                    : 'border-gray-200 hover:border-gray-300'
                ]"
              >
                <div class="flex items-center gap-3">
                  <div
                    :class="[
                      'h-4 w-4 rounded-full border-2 transition-colors',
                      selectedPayment === payment.id
                        ? 'border-orange-500 bg-orange-500'
                        : 'border-gray-300'
                    ]"
                  ></div>
                  <CreditCard class="h-6 w-6 text-gray-400" />
                  <div class="flex-1">
                    <p class="font-medium text-gray-900">**** **** **** {{ payment.lastFour }}</p>
                    <p class="text-gray-600 text-sm">{{ payment.brand }} • Expires {{ payment.expiry }}</p>
                  </div>
                  <span v-if="payment.isDefault" class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full">
                    Default
                  </span>
                </div>
              </div>
              
              <div
                @click="selectedPayment = 'cash'"
                :class="[
                  'border rounded-lg p-4 cursor-pointer transition-colors',
                  selectedPayment === 'cash'
                    ? 'border-orange-500 bg-orange-50'
                    : 'border-gray-200 hover:border-gray-300'
                ]"
              >
                <div class="flex items-center gap-3">
                  <div
                    :class="[
                      'h-4 w-4 rounded-full border-2 transition-colors',
                      selectedPayment === 'cash'
                        ? 'border-orange-500 bg-orange-500'
                        : 'border-gray-300'
                    ]"
                  ></div>
                  <DollarSign class="h-6 w-6 text-gray-400" />
                  <div>
                    <p class="font-medium text-gray-900">Cash on Delivery</p>
                    <p class="text-gray-600 text-sm">Pay when your order arrives</p>
                  </div>
                </div>
              </div>
              
              <button class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-orange-300 hover:text-orange-600 transition-colors font-medium">
                <Plus class="h-5 w-5 inline mr-2" />
                Add new payment method
              </button>
            </div>
          </div>

          <!-- Order Notes -->
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Order Notes</h3>
            <textarea
              v-model="orderNotes"
              placeholder="Any additional notes for the restaurant or delivery driver?"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors resize-none"
              rows="3"
            ></textarea>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-24">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Final Order</h3>
            
            <!-- Restaurant Info -->
            <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-200">
              <img
                :src="restaurant.image"
                :alt="restaurant.name"
                class="h-12 w-12 rounded-lg object-cover"
              />
              <div>
                <p class="font-medium text-gray-900">{{ restaurant.name }}</p>
                <p class="text-gray-600 text-sm">{{ restaurant.category }}</p>
              </div>
            </div>
            
            <!-- Items -->
            <div class="space-y-3 mb-6">
              <div
                v-for="item in cartItems"
                :key="item.id"
                class="flex justify-between text-sm"
              >
                <span class="text-gray-600">{{ item.quantity }}x {{ item.name }}</span>
                <span class="font-medium">${{ (item.price * item.quantity).toFixed(2) }}</span>
              </div>
            </div>
            
            <!-- Pricing -->
            <div class="space-y-3 border-t border-gray-200 pt-4">
              <div class="flex justify-between">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium">${{ subtotal.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Delivery Fee</span>
                <span class="font-medium">${{ deliveryFee.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Service Fee</span>
                <span class="font-medium">${{ serviceFee.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Tax</span>
                <span class="font-medium">${{ tax.toFixed(2) }}</span>
              </div>
              <div v-if="discount > 0" class="flex justify-between text-green-600">
                <span>Discount</span>
                <span>-${{ discount.toFixed(2) }}</span>
              </div>
            </div>
            
            <!-- Total -->
            <div class="border-t border-gray-200 pt-4 mt-4">
              <div class="flex justify-between text-lg font-bold">
                <span>Total</span>
                <span>${{ total.toFixed(2) }}</span>
              </div>
            </div>
            
            <!-- Place Order Button -->
            <button
              @click="placeOrder"
              :disabled="!canPlaceOrder"
              class="w-full mt-6 bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium disabled:bg-gray-300 disabled:cursor-not-allowed"
            >
              <span v-if="isPlacingOrder" class="flex items-center justify-center gap-2">
                <div class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>
                Placing Order...
              </span>
              <span v-else>Place Order • ${{ total.toFixed(2) }}</span>
            </button>
            
            <p class="text-xs text-gray-500 mt-3 text-center">
              By placing this order, you agree to our Terms of Service and Privacy Policy
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Confirmation Modal -->
    <div
      v-if="showOrderConfirmation"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
    >
      <div class="bg-white rounded-lg p-8 max-w-md w-full text-center">
        <div class="h-16 w-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <Check class="h-8 w-8 text-green-600" />
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Order Placed!</h2>
        <p class="text-gray-600 mb-6">
          Your order #{{ orderNumber }} has been confirmed and is being prepared.
        </p>
        <div class="space-y-3">
          <button
            @click="trackOrder"
            class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium"
          >
            Track Your Order
          </button>
          <button
            @click="goToHome"
            class="w-full border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition-colors font-medium"
          >
            Continue Shopping
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { 
  ArrowLeft, 
  Star, 
  Clock, 
  Minus, 
  Plus, 
  Trash2, 
  CreditCard, 
  DollarSign, 
  Check 
} from 'lucide-vue-next'

const currentStep = ref('cart')
const specialInstructions = ref('')
const promoCode = ref('')
const selectedAddress = ref(1)
const deliveryOption = ref('asap')
const scheduledDate = ref('')
const scheduledTime = ref('')
const selectedPayment = ref(1)
const orderNotes = ref('')
const showOrderConfirmation = ref(false)
const isPlacingOrder = ref(false)
const orderNumber = ref('')

const restaurant = ref({
  name: "Luigi's Pizza Place",
  category: "Italian • Pizza",
  image: "https://images.unsplash.com/photo-1513104890138-7c749659a591?w=100&h=100&fit=crop",
  rating: 4.5,
  deliveryTime: "30-45 min"
})

const cartItems = ref([
  {
    id: 1,
    name: "Margherita Pizza",
    description: "Classic pizza with fresh tomato sauce, mozzarella cheese, and fresh basil leaves",
    price: 18.99,
    quantity: 2,
    image: "https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?w=100&h=100&fit=crop",
    customizations: ["Extra cheese", "Thin crust"]
  },
  {
    id: 2,
    name: "Caesar Salad",
    description: "Crisp romaine lettuce with parmesan cheese, croutons, and Caesar dressing",
    price: 12.99,
    quantity: 1,
    image: "https://images.unsplash.com/photo-1546793665-c74683f339c1?w=100&h=100&fit=crop"
  },
  {
    id: 3,
    name: "Coca Cola",
    description: "Classic refreshing cola drink served chilled",
    price: 2.99,
    quantity: 2,
    image: "https://images.unsplash.com/photo-1629203851122-3726ecdf080e?w=100&h=100&fit=crop"
  }
])

const savedAddresses = ref([
  {
    id: 1,
    label: 'Home',
    fullAddress: '123 Main Street, Apt 4B, New York, NY 10001',
    instructions: 'Ring doorbell twice, leave at door',
    isDefault: true
  },
  {
    id: 2,
    label: 'Work',
    fullAddress: '456 Business Ave, Suite 200, New York, NY 10002',
    instructions: 'Call when arrived, reception desk',
    isDefault: false
  }
])

const paymentMethods = ref([
  {
    id: 1,
    brand: 'Visa',
    lastFour: '4242',
    expiry: '12/25',
    isDefault: true
  },
  {
    id: 2,
    brand: 'Mastercard',
    lastFour: '8888',
    expiry: '08/26',
    isDefault: false
  }
])

const deliveryFee = ref(3.99)
const serviceFee = ref(2.50)
const discount = ref(0)

const subtotal = computed(() => {
  return cartItems.value.reduce((total, item) => total + (item.price * item.quantity), 0)
})

const tax = computed(() => {
  return subtotal.value * 0.08 // 8% tax
})

const total = computed(() => {
  return subtotal.value + deliveryFee.value + serviceFee.value + tax.value - discount.value
})

const estimatedDelivery = computed(() => {
  const now = new Date()
  const deliveryTime = new Date(now.getTime() + 45 * 60000) // 45 minutes from now
  return deliveryTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
})

const canPlaceOrder = computed(() => {
  return selectedAddress.value && selectedPayment.value && cartItems.value.length > 0
})

const updateQuantity = (itemId, newQuantity) => {
  if (newQuantity <= 0) {
    removeItem(itemId)
  } else {
    const item = cartItems.value.find(i => i.id === itemId)
    if (item) {
      item.quantity = newQuantity
    }
  }
}

const removeItem = (itemId) => {
  cartItems.value = cartItems.value.filter(item => item.id !== itemId)
}

const addMoreItems = () => {
  console.log('Adding more items')
  // Navigate back to restaurant menu
}

const applyPromoCode = () => {
  if (promoCode.value.toLowerCase() === 'save10') {
    discount.value = subtotal.value * 0.1
    console.log('Promo code applied!')
  } else {
    console.log('Invalid promo code')
  }
}

const proceedToCheckout = () => {
  currentStep.value = 'checkout'
}

const placeOrder = async () => {
  isPlacingOrder.value = true
  
  // Simulate API call
  await new Promise(resolve => setTimeout(resolve, 2000))
  
  orderNumber.value = 'ORD' + Math.random().toString(36).substr(2, 9).toUpperCase()
  isPlacingOrder.value = false
  showOrderConfirmation.value = true
}

const trackOrder = () => {
  showOrderConfirmation.value = false
  console.log('Tracking order:', orderNumber.value)
  // Navigate to order tracking
}

const goToHome = () => {
  console.log('Going to home')
  // Navigate to home
}

const goBack = () => {
  if (currentStep.value === 'checkout') {
    currentStep.value = 'cart'
  } else {
    window.history.back()
  }
}

onMounted(() => {
  // Initialize component
})
</script>