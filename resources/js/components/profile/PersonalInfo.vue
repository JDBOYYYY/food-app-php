<template>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Dane Osobowe</h2>
        <form @submit.prevent="handleUpdateProfile" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imię i Nazwisko</label>
                    <input v-model="profileForm.name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Adres E-mail</label>
                    <input v-model="profileForm.email" type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors" />
                </div>
            </div>
            <div v-if="profileSuccess" class="text-green-600 text-sm">{{ profileSuccess }}</div>
            <div v-if="profileError" class="text-red-500 text-sm">{{ profileError }}</div>
            <button type="submit" :disabled="isUpdatingProfile" class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium disabled:bg-orange-300">
                {{ isUpdatingProfile ? 'Zapisywanie...' : 'Zapisz Zmiany' }}
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, watchEffect } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { profileService } from '@/services/profileService'

const authStore = useAuthStore()
const profileForm = ref({ name: '', email: '' })
const isUpdatingProfile = ref(false)
const profileError = ref(null)
const profileSuccess = ref(null)

watchEffect(() => {
    if (authStore.user) {
        profileForm.value.name = authStore.user.name
        profileForm.value.email = authStore.user.email
    }
})

async function handleUpdateProfile() {
    isUpdatingProfile.value = true
    profileError.value = null
    profileSuccess.value = null
    try {
        const updatedUser = await profileService.updateProfile(profileForm.value)
        authStore.updateUser(updatedUser)
        profileSuccess.value = 'Profil został pomyślnie zaktualizowany.'
    } catch (e) {
        console.error('Błąd aktualizacji profilu:', e)
        profileError.value = e.response?.data?.message || 'Wystąpił błąd.'
    } finally {
        isUpdatingProfile.value = false
    }
}
</script>
