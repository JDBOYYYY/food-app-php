<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-lg font-bold text-gray-900">Delivery Address</h3>
      <button
        v-if="!isAddingAddress"
        @click="isAddingAddress = true"
        class="text-sm font-medium text-orange-500 hover:text-orange-600"
      >
        Add New
      </button>
    </div>

    <div v-if="isLoading" class="text-center py-4">
      <p class="text-gray-500">Loading addresses...</p>
    </div>

    <div v-else-if="error" class="text-center py-4 text-red-500">
      <p>{{ error }}</p>
    </div>

    <div v-else-if="!isAddingAddress" class="space-y-4">
      <div v-if="addresses.length === 0" class="text-center text-gray-500 py-4">
        <p>No saved addresses found. Please add one.</p>
      </div>
      <div
        v-for="address in addresses"
        :key="address.Id"
        @click="selectAddress(address.Id)"
        :class="[
          'border rounded-lg p-4 cursor-pointer transition-all',
          modelValue === address.Id
            ? 'border-orange-500 bg-orange-50 ring-2 ring-orange-200'
            : 'border-gray-200 hover:border-gray-300',
        ]"
      >
        <div class="flex items-start justify-between">
          <div class="flex items-center gap-3">
            <div
              :class="[
                'h-4 w-4 mt-1 rounded-full border-2 transition-colors',
                modelValue === address.Id
                  ? 'border-orange-500 bg-orange-500'
                  : 'border-gray-300',
              ]"
            ></div>
            <div>
              <p class="font-medium text-gray-900">{{ address.Street }}</p>
              <p class="text-gray-600 text-sm">
                {{ address.PostalCode }} {{ address.City }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="isAddingAddress" class="space-y-4">
      <input
        v-model="newAddress.Street"
        placeholder="Street and number"
        class="form-input"
      />
      <input
        v-model="newAddress.Apartment"
        placeholder="Apartment (optional)"
        class="form-input"
      />
      <input
        v-model="newAddress.City"
        placeholder="City"
        class="form-input"
      />
      <input
        v-model="newAddress.PostalCode"
        placeholder="Postal Code"
        class="form-input"
      />
      <input
        v-model="newAddress.Country"
        placeholder="Country"
        class="form-input"
      />
      <div class="flex justify-end gap-2 mt-2">
        <button @click="isAddingAddress = false" class="btn-secondary">
          Cancel
        </button>
        <button @click="handleSaveAddress" class="btn-primary">Save</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { addressService } from '../../services/index';
import type { AddressDto, CreateAddressDto } from '../../services/types';

const props = defineProps<{
  modelValue: number | null;
}>();

const emit = defineEmits(['update:modelValue']);

const addresses = ref<AddressDto[]>([]);
const isLoading = ref(true);
const error = ref<string | null>(null);
const isAddingAddress = ref(false);

const newAddress = ref<Omit<CreateAddressDto, 'UserId'>>({
  Street: '',
  Apartment: '',
  City: '',
  PostalCode: '',
  Country: 'Poland',
});

const fetchAddresses = async () => {
  isLoading.value = true;
  error.value = null;
  try {
    const response = await addressService.getMyAddresses();
    addresses.value = response.data;
    if (props.modelValue === null && addresses.value.length > 0) {
      selectAddress(addresses.value[0].Id);
    }
  } catch (e: any) {
    error.value = 'Could not load addresses.';
  } finally {
    isLoading.value = false;
  }
};

const selectAddress = (id: number) => {
  emit('update:modelValue', id);
};

const handleSaveAddress = async () => {
  if (
    !newAddress.value.Street ||
    !newAddress.value.City ||
    !newAddress.value.PostalCode
  ) {
    alert('Please fill in all required address fields.');
    return;
  }
  try {
    const createdAddress = await addressService.createAddress(newAddress.value);
    addresses.value.push(createdAddress.data);
    selectAddress(createdAddress.data.Id);
    isAddingAddress.value = false;
    newAddress.value = {
      Street: '',
      Apartment: '',
      City: '',
      PostalCode: '',
      Country: 'Poland',
    };
  } catch (e: any) {
    alert(`Error saving address: ${e.message}`);
  }
};

onMounted(fetchAddresses);
</script>

<style scoped>
.form-input {
  @apply w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm;
}
.btn-primary {
  @apply bg-orange-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-orange-600 transition;
}
.btn-secondary {
  @apply bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-300 transition;
}
</style>