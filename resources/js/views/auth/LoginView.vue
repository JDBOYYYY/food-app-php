<template>
    <div class="flex min-h-screen">
        <!-- Left side - Login form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
            <div class="w-full max-w-md space-y-8">
                <div>
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-700 tracking-tighter">Witamy ponownie!</h2>
                    <p class="mt-2 text-sm text-gray-500 tracking-tight">
                        Zaloguj się, aby uzyskać nieograniczony dostęp do danych i informacji.
                    </p>
                </div>

                <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ error }}</span>
                </div>

                <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                E-mail <span class="text-orange-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input
                                    id="email"
                                    v-model="email"
                                    name="email"
                                    type="email"
                                    autocomplete="email"
                                    required
                                    placeholder="Wprowadź swój adres e-mail"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                />
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Hasło <span class="text-orange-500">*</span>
                            </label>
                            <div class="mt-1 relative">
                                <input
                                    id="password"
                                    v-model="password"
                                    name="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    autocomplete="current-password"
                                    required
                                    placeholder="Wprowadź hasło"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500"
                                >
                                    <EyeOffIcon v-if="showPassword" class="h-5 w-5" />
                                    <EyeIcon v-else class="h-5 w-5" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                id="remember-me"
                                v-model="rememberMe"
                                type="checkbox"
                                class="h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300 rounded"
                            />
                            <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                                Zapamiętaj mnie
                            </label>
                        </div>
                        <div class="text-sm">
                            <router-link to="/forgot-password" class="font-medium text-orange-500 hover:text-orange-400">
                                Zapomniałeś hasła?
                            </router-link>
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="isLoading"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:bg-orange-300 font-medium"
                        >
                            {{ isLoading ? 'Logowanie...' : 'Zaloguj się' }}
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">

                    </div>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Nie masz konta?
                        <router-link to="/register" class="font-medium text-orange-500 hover:text-orange-400 ml-1">
                            Zarejestruj się tutaj
                        </router-link>
                    </p>
                </div>
            </div>
        </div>

        <!-- Right side - Image -->
        <div class="hidden lg:block lg:w-1/2 bg-gradient-to-br from-gray-700 to-gray-800 relative overflow-hidden">
            <div class="absolute inset-0 flex items-center justify-center">
                <img
                    src="https://images.unsplash.com/photo-1505932049984-db368d92fa63?q=80&w=2536&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    alt="Ilustracja logowania"
                    class="w-full h-full object-cover"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { EyeIcon, EyeOffIcon } from 'lucide-vue-next'
import { useAuthStore } from "../../stores/auth"
import { useRouter } from 'vue-router';

const auth = useAuthStore()
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const rememberMe = ref(false)
const isLoading = ref(false)
const error = ref(null)

const router = useRouter();

const handleLogin = async () => {
    isLoading.value = true
    error.value = null

    try {
        await auth.signIn({ email: email.value, password: password.value })
        // Router will redirect automatically on success
        await router.push('/');
    } catch (e) {
        error.value = e.message || 'Wystąpił nieznany błąd.'
    } finally {
        isLoading.value = false
    }
}
</script>