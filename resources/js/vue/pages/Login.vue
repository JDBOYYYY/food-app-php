<template>
  <div class="p-4 max-w-sm mx-auto">
    <h1 class="text-2xl font-bold mb-4">Logowanie</h1>
    <div class="mb-2">
      <label>Email</label>
      <input v-model="email" class="border p-1 w-full"/>
    </div>
    <div class="mb-4">
      <label>Hasło</label>
      <input type="password" v-model="password" class="border p-1 w-full"/>
    </div>
    <button @click="submit" class="bg-blue-600 text-white px-4 py-2" :disabled="loading">
      {{ loading ? '...' : 'Zaloguj' }}
    </button>
    <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../stores/auth.js';

const email = ref('');
const password = ref('');
const error = ref('');
const loading = ref(false);
const router = useRouter();
const auth = useAuth();

async function submit() {
  error.value = '';
  loading.value = true;
  try {
    await auth.login(email.value, password.value);
    router.push('/');
  } catch (e) {
    error.value = 'Błąd logowania';
  } finally {
    loading.value = false;
  }
}
</script>
