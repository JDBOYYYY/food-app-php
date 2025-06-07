<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Profile Header -->
    <div class="bg-white shadow-sm">
      <div class="container mx-auto px-4 py-8">
        <div class="flex items-center gap-6">
          <div class="relative">
            <div class="h-24 w-24 bg-orange-500 rounded-full flex items-center justify-center">
              <User class="h-12 w-12 text-white" v-if="!user.avatar" />
              <img 
                v-else
                :src="user.avatar" 
                :alt="user.name"
                class="h-24 w-24 rounded-full object-cover"
              />
            </div>
            <button class="absolute bottom-0 right-0 h-8 w-8 bg-orange-500 rounded-full flex items-center justify-center text-white hover:bg-orange-600 transition-colors">
              <Camera class="h-4 w-4" />
            </button>
          </div>
          <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ user.name }}</h1>
            <p class="text-gray-600 mb-1">{{ user.email }}</p>
            <p class="text-sm text-gray-500">Member since {{ user.memberSince }}</p>
            <div class="flex items-center gap-4 mt-3">
              <div class="flex items-center gap-1">
                <Star class="h-4 w-4 text-yellow-400 fill-yellow-400" />
                <span class="text-sm font-medium">{{ user.rating }} rating</span>
              </div>
              <div class="flex items-center gap-1">
                <Package class="h-4 w-4 text-gray-400" />
                <span class="text-sm text-gray-600">{{ user.totalOrders }} orders</span>
              </div>
            </div>
          </div>
          <button class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium">
            Edit Profile
          </button>
        </div>
      </div>
    </div>

    <!-- Profile Content -->
    <div class="container mx-auto px-4 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <nav class="space-y-2">
              <button
                v-for="tab in tabs"
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                  'w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-colors',
                  activeTab === tab.id
                    ? 'bg-orange-50 text-orange-600 border border-orange-200'
                    : 'text-gray-700 hover:bg-gray-50'
                ]"
              >
                <component :is="tab.icon" class="h-5 w-5" />
                <span class="font-medium">{{ tab.name }}</span>
              </button>
            </nav>
          </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
          <!-- Personal Information -->
          <div v-if="activeTab === 'personal'" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Personal Information</h2>
            
            <form @submit.prevent="updatePersonalInfo" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                  <input
                    v-model="personalInfo.firstName"
                    type="text"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                  <input
                    v-model="personalInfo.lastName"
                    type="text"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                  />
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input
                  v-model="personalInfo.email"
                  type="email"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input
                  v-model="personalInfo.phone"
                  type="tel"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                <input
                  v-model="personalInfo.dateOfBirth"
                  type="date"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                />
              </div>
              
              <button
                type="submit"
                class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium"
              >
                Save Changes
              </button>
            </form>
          </div>

          <!-- Addresses -->
          <div v-if="activeTab === 'addresses'" class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Delivery Addresses</h2>
                <button
                  @click="showAddAddressModal = true"
                  class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium flex items-center gap-2"
                >
                  <Plus class="h-4 w-4" />
                  Add Address
                </button>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                  v-for="address in addresses"
                  :key="address.id"
                  :class="[
                    'border rounded-lg p-4 cursor-pointer transition-colors',
                    address.isDefault
                      ? 'border-orange-500 bg-orange-50'
                      : 'border-gray-200 hover:border-gray-300'
                  ]"
                >
                  <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-2">
                      <MapPin class="h-5 w-5 text-gray-400" />
                      <span class="font-medium text-gray-900">{{ address.label }}</span>
                      <span v-if="address.isDefault" class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full">
                        Default
                      </span>
                    </div>
                    <div class="flex items-center gap-2">
                      <button
                        @click="editAddress(address)"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                      >
                        <Edit2 class="h-4 w-4" />
                      </button>
                      <button
                        @click="deleteAddress(address.id)"
                        class="text-gray-400 hover:text-red-500 transition-colors"
                      >
                        <Trash2 class="h-4 w-4" />
                      </button>
                    </div>
                  </div>
                  <p class="text-gray-600 text-sm leading-relaxed">{{ address.fullAddress }}</p>
                  <p class="text-gray-500 text-xs mt-2">{{ address.instructions }}</p>
                  <button
                    v-if="!address.isDefault"
                    @click="setDefaultAddress(address.id)"
                    class="text-orange-500 text-sm font-medium mt-3 hover:text-orange-600 transition-colors"
                  >
                    Set as Default
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Order History -->
          <div v-if="activeTab === 'orders'" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Order History</h2>
            
            <div class="space-y-4">
              <div
                v-for="order in orderHistory"
                :key="order.id"
                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
              >
                <div class="flex items-center justify-between mb-3">
                  <div class="flex items-center gap-4">
                    <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center">
                      <Package class="h-6 w-6 text-gray-400" />
                    </div>
                    <div>
                      <h3 class="font-bold text-gray-900">{{ order.restaurantName }}</h3>
                      <p class="text-sm text-gray-600">Order #{{ order.id }}</p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="font-bold text-gray-900">${{ order.total.toFixed(2) }}</p>
                    <p class="text-sm text-gray-500">{{ order.date }}</p>
                  </div>
                </div>
                
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-4">
                    <span
                      :class="[
                        'px-3 py-1 rounded-full text-sm font-medium',
                        order.status === 'delivered' ? 'bg-green-100 text-green-800' :
                        order.status === 'cancelled' ? 'bg-red-100 text-red-800' :
                        'bg-yellow-100 text-yellow-800'
                      ]"
                    >
                      {{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}
                    </span>
                    <span class="text-sm text-gray-600">{{ order.items.length }} items</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <button class="text-orange-500 text-sm font-medium hover:text-orange-600 transition-colors">
                      View Details
                    </button>
                    <button
                      v-if="order.status === 'delivered'"
                      class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-600 transition-colors"
                    >
                      Reorder
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Payment Methods -->
          <div v-if="activeTab === 'payment'" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-900">Payment Methods</h2>
              <button
                @click="showAddPaymentModal = true"
                class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium flex items-center gap-2"
              >
                <Plus class="h-4 w-4" />
                Add Card
              </button>
            </div>
            
            <div class="space-y-4">
              <div
                v-for="card in paymentMethods"
                :key="card.id"
                :class="[
                  'border rounded-lg p-4 transition-colors',
                  card.isDefault
                    ? 'border-orange-500 bg-orange-50'
                    : 'border-gray-200'
                ]"
              >
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-4">
                    <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center">
                      <CreditCard class="h-6 w-6 text-gray-400" />
                    </div>
                    <div>
                      <p class="font-medium text-gray-900">**** **** **** {{ card.lastFour }}</p>
                      <p class="text-sm text-gray-600">{{ card.brand }} • Expires {{ card.expiry }}</p>
                    </div>
                    <span v-if="card.isDefault" class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full">
                      Default
                    </span>
                  </div>
                  <div class="flex items-center gap-2">
                    <button
                      v-if="!card.isDefault"
                      @click="setDefaultPayment(card.id)"
                      class="text-orange-500 text-sm font-medium hover:text-orange-600 transition-colors"
                    >
                      Set Default
                    </button>
                    <button
                      @click="deletePaymentMethod(card.id)"
                      class="text-gray-400 hover:text-red-500 transition-colors"
                    >
                      <Trash2 class="h-4 w-4" />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Notifications -->
          <div v-if="activeTab === 'notifications'" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Notification Preferences</h2>
            
            <div class="space-y-6">
              <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Updates</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="font-medium text-gray-900">Order confirmations</p>
                      <p class="text-sm text-gray-600">Get notified when your order is confirmed</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input v-model="notifications.orderConfirmation" type="checkbox" class="sr-only peer">
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                    </label>
                  </div>
                  
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="font-medium text-gray-900">Delivery updates</p>
                      <p class="text-sm text-gray-600">Track your order status in real-time</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input v-model="notifications.deliveryUpdates" type="checkbox" class="sr-only peer">
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                    </label>
                  </div>
                </div>
              </div>
              
              <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Marketing</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="font-medium text-gray-900">Promotional offers</p>
                      <p class="text-sm text-gray-600">Receive special deals and discounts</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input v-model="notifications.promotions" type="checkbox" class="sr-only peer">
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                    </label>
                  </div>
                  
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="font-medium text-gray-900">New restaurants</p>
                      <p class="text-sm text-gray-600">Be the first to know about new restaurants</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input v-model="notifications.newRestaurants" type="checkbox" class="sr-only peer">
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                    </label>
                  </div>
                </div>
              </div>
              
              <button
                @click="saveNotificationSettings"
                class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium"
              >
                Save Preferences
              </button>
            </div>
          </div>

          <!-- Security -->
          <div v-if="activeTab === 'security'" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Security Settings</h2>
            
            <div class="space-y-8">
              <!-- Change Password -->
              <div class="border-b border-gray-200 pb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                <form @submit.prevent="changePassword" class="space-y-4 max-w-md">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input
                      v-model="passwordForm.current"
                      type="password"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input
                      v-model="passwordForm.new"
                      type="password"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input
                      v-model="passwordForm.confirm"
                      type="password"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                    />
                  </div>
                  <button
                    type="submit"
                    class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium"
                  >
                    Update Password
                  </button>
                </form>
              </div>
              
              <!-- Two-Factor Authentication -->
              <div class="border-b border-gray-200 pb-8">
                <div class="flex items-center justify-between mb-4">
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">Two-Factor Authentication</h3>
                    <p class="text-sm text-gray-600">Add an extra layer of security to your account</p>
                  </div>
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input v-model="security.twoFactorEnabled" type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                  </label>
                </div>
              </div>
              
              <!-- Login Sessions -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Active Sessions</h3>
                <div class="space-y-3">
                  <div
                    v-for="session in activeSessions"
                    :key="session.id"
                    class="flex items-center justify-between p-4 border border-gray-200 rounded-lg"
                  >
                    <div class="flex items-center gap-3">
                      <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <Monitor class="h-5 w-5 text-gray-400" />
                      </div>
                      <div>
                        <p class="font-medium text-gray-900">{{ session.device }}</p>
                        <p class="text-sm text-gray-600">{{ session.location }} • {{ session.lastActive }}</p>
                      </div>
                      <span v-if="session.isCurrent" class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                        Current
                      </span>
                    </div>
                    <button
                      v-if="!session.isCurrent"
                      @click="terminateSession(session.id)"
                      class="text-red-500 text-sm font-medium hover:text-red-600 transition-colors"
                    >
                      Terminate
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { 
  User, 
  Camera, 
  Star, 
  Package, 
  Settings, 
  MapPin, 
  CreditCard, 
  Bell, 
  Shield, 
  Plus, 
  Edit2, 
  Trash2, 
  Monitor 
} from 'lucide-vue-next'

const activeTab = ref('personal')
const showAddAddressModal = ref(false)
const showAddPaymentModal = ref(false)

const tabs = ref([
  { id: 'personal', name: 'Personal Info', icon: User },
  { id: 'addresses', name: 'Addresses', icon: MapPin },
  { id: 'orders', name: 'Order History', icon: Package },
  { id: 'payment', name: 'Payment', icon: CreditCard },
  { id: 'notifications', name: 'Notifications', icon: Bell },
  { id: 'security', name: 'Security', icon: Shield },
])

const user = ref({
  name: 'John Doe',
  email: 'john.doe@example.com',
  avatar: null,
  memberSince: 'January 2023',
  rating: 4.8,
  totalOrders: 47
})

const personalInfo = ref({
  firstName: 'John',
  lastName: 'Doe',
  email: 'john.doe@example.com',
  phone: '+1 (555) 123-4567',
  dateOfBirth: '1990-05-15'
})

const addresses = ref([
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
  },
  {
    id: 3,
    label: 'Mom\'s House',
    fullAddress: '789 Family Lane, Brooklyn, NY 11201',
    instructions: 'Use side entrance',
    isDefault: false
  }
])

const orderHistory = ref([
  {
    id: '12345',
    restaurantName: 'Luigi\'s Pizza Place',
    total: 28.50,
    date: '2 days ago',
    status: 'delivered',
    items: ['Margherita Pizza', 'Caesar Salad', 'Coca Cola']
  },
  {
    id: '12344',
    restaurantName: 'Burger Palace',
    total: 15.99,
    date: '1 week ago',
    status: 'delivered',
    items: ['Classic Burger', 'Fries']
  },
  {
    id: '12343',
    restaurantName: 'Sushi Express',
    total: 42.00,
    date: '2 weeks ago',
    status: 'cancelled',
    items: ['California Roll', 'Salmon Sashimi', 'Miso Soup']
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

const notifications = ref({
  orderConfirmation: true,
  deliveryUpdates: true,
  promotions: false,
  newRestaurants: true
})

const security = ref({
  twoFactorEnabled: false
})

const passwordForm = ref({
  current: '',
  new: '',
  confirm: ''
})

const activeSessions = ref([
  {
    id: 1,
    device: 'Chrome on MacBook Pro',
    location: 'New York, NY',
    lastActive: 'Active now',
    isCurrent: true
  },
  {
    id: 2,
    device: 'Safari on iPhone',
    location: 'New York, NY',
    lastActive: '2 hours ago',
    isCurrent: false
  }
])

const updatePersonalInfo = () => {
  console.log('Updating personal info:', personalInfo.value)
  // Handle form submission
}

const setDefaultAddress = (addressId) => {
  addresses.value.forEach(addr => {
    addr.isDefault = addr.id === addressId
  })
}

const editAddress = (address) => {
  console.log('Editing address:', address)
  // Handle address editing
}

const deleteAddress = (addressId) => {
  addresses.value = addresses.value.filter(addr => addr.id !== addressId)
}

const setDefaultPayment = (cardId) => {
  paymentMethods.value.forEach(card => {
    card.isDefault = card.id === cardId
  })
}

const deletePaymentMethod = (cardId) => {
  paymentMethods.value = paymentMethods.value.filter(card => card.id !== cardId)
}

const saveNotificationSettings = () => {
  console.log('Saving notification settings:', notifications.value)
  // Handle saving preferences
}

const changePassword = () => {
  console.log('Changing password')
  // Handle password change
}

const terminateSession = (sessionId) => {
  activeSessions.value = activeSessions.value.filter(session => session.id !== sessionId)
}

onMounted(() => {
  // Initialize component
})
</script>