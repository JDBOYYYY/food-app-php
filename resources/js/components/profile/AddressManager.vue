<template>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Adresy Dostawy</h2>
                <button @click="showAddAddressModal = true" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors font-medium flex items-center gap-2">
                    <Plus class="h-4 w-4" />
                    Dodaj Adres
                </button>
            </div>
            <div v-if="addressesLoading" class="text-center text-gray-500 py-8"><p>Ładowanie adresów...</p></div>
            <div v-else-if="addressesError" class="text-center text-red-500 py-8"><p>{{ addressesError }}</p></div>
            <div v-else>
                <div v-if="addresses.length === 0" class="text-center text-gray-500 py-8"><p>Nie masz jeszcze zapisanych żadnych adresów.</p></div>
                <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-for="address in addresses" :key="address.id" :class="['border rounded-lg p-4 cursor-pointer transition-colors', address.isDefault ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300']">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <MapPin class="h-5 w-5 text-gray-400" />
                                <span class="font-medium text-gray-900">{{ address.Street }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="text-gray-400 hover:text-gray-600"><Edit2 class="h-4 w-4" /></button>
                                <button class="text-gray-400 hover:text-red-500"><Trash2 class="h-4 w-4" /></button>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ address.Street }}, {{ address.City }} {{ address.PostalCode }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal do dodawania adresu -->
        <div v-if="showAddAddressModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-lg">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Dodaj nowy adres</h3>
                <form @submit.prevent="handleAddAddress" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="Street" class="block text-sm font-medium text-gray-700">Ulica i numer</label>
                            <input v-model="newAddress.Street" id="Street" type="text" required class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500" />
                        </div>
                        <div>
                            <label for="Apartment" class="block text-sm font-medium text-gray-700">Numer mieszkania (opcjonalnie)</label>
                            <input v-model="newAddress.Apartment" id="Apartment" type="text" class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="City" class="block text-sm font-medium text-gray-700">Miasto</label>
                            <input v-model="newAddress.City" id="City" type="text" required class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500" />
                        </div>
                        <div>
                            <label for="PostalCode" class="block text-sm font-medium text-gray-700">Kod pocztowy</label>
                            <input v-model="newAddress.PostalCode" id="PostalCode" type="text" required class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500" />
                        </div>
                    </div>
                    <div>
                        <label for="Country" class="block text-sm font-medium text-gray-700">Kraj</label>
                        <input v-model="newAddress.Country" id="Country" type="text" required class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500" />
                    </div>
                    <div v-if="addAddressError" class="text-red-500 text-sm">{{ addAddressError }}</div>
                    <div class="flex justify-end gap-4 pt-4">
                        <button type="button" @click="showAddAddressModal = false" class="px-4 py-2 rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200">Anuluj</button>
                        <button type="submit" :disabled="isAddingAddress" class="px-4 py-2 rounded-lg text-white bg-orange-500 hover:bg-orange-600 disabled:bg-orange-300">
                            {{ isAddingAddress ? 'Zapisywanie...' : 'Zapisz Adres' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { addressService } from '@/services/addressService'
import { MapPin, Plus, Edit2, Trash2 } from 'lucide-vue-next'

const addresses = ref([])
const addressesLoading = ref(false)
const addressesError = ref(null)
const showAddAddressModal = ref(false)
const newAddress = ref({ Street: '', Apartment: '', City: '', PostalCode: '', Country: 'Polska' })
const isAddingAddress = ref(false)
const addAddressError = ref(null)

async function loadAddresses() {
    addressesLoading.value = true
    addressesError.value = null
    try {
        const response = await addressService.getMyAddresses()
        addresses.value = response.data || []
    } catch (e) {
        console.error('Nie udało się pobrać adresów:', e)
        addressesError.value = 'Nie można załadować adresów. Spróbuj ponownie później.'
    } finally {
        addressesLoading.value = false
    }
}

async function handleAddAddress() {
    isAddingAddress.value = true
    addAddressError.value = null
    try {
        const addressData = { ...newAddress.value }
        if (!addressData.Apartment) delete addressData.Apartment
        await addressService.addAddress(addressData)
        showAddAddressModal.value = false
        newAddress.value = { Street: '', Apartment: '', City: '', PostalCode: '', Country: 'Polska' }
        await loadAddresses()
    } catch (e) {
        console.error('Błąd podczas dodawania adresu:', e)
        addAddressError.value = e.response?.data?.message || 'Wystąpił błąd. Sprawdź dane i spróbuj ponownie.'
    } finally {
        isAddingAddress.value = false
    }
}

onMounted(loadAddresses)
</script>
