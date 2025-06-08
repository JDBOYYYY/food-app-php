<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Bezpieczeństwo</h2>
    <div class="border-b border-gray-200 pb-8">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Zmień hasło</h3>
      <form
        @submit.prevent="handleChangePassword"
        class="space-y-4 max-w-md"
      >
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2"
            >Aktualne hasło</label
          >
          <input
            v-model="passwordForm.current_password"
            type="password"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2"
            >Nowe hasło</label
          >
          <input
            v-model="passwordForm.new_password"
            type="password"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2"
            >Potwierdź nowe hasło</label
          >
          <input
            v-model="passwordForm.new_password_confirmation"
            type="password"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
          />
        </div>
        <div v-if="passwordSuccess" class="text-green-600 text-sm">
          {{ passwordSuccess }}
        </div>
        <div v-if="passwordError" class="text-red-500 text-sm">
          {{ passwordError }}
        </div>
        <button
          type="submit"
          :disabled="isChangingPassword"
          class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition-colors font-medium disabled:bg-orange-300"
        >
          {{ isChangingPassword ? "Aktualizowanie..." : "Zaktualizuj Hasło" }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { profileService } from "@/services/profileService";
import { ApiError } from "@/services/apiClient";

const passwordForm = ref({
  current_password: "",
  new_password: "",
  new_password_confirmation: "",
});
const isChangingPassword = ref(false);
const passwordError = ref(null);
const passwordSuccess = ref(null);

async function handleChangePassword() {
  isChangingPassword.value = true;
  passwordError.value = null;
  passwordSuccess.value = null;

  if (
    passwordForm.value.new_password !==
    passwordForm.value.new_password_confirmation
  ) {
    passwordError.value = "Nowe hasła nie są identyczne.";
    isChangingPassword.value = false;
    return;
  }

  try {
    const response = await profileService.changePassword(passwordForm.value);
    passwordSuccess.value = response.message;
    passwordForm.value = {
      current_password: "",
      new_password: "",
      new_password_confirmation: "",
    };
  } catch (e) {
    console.error("Błąd zmiany hasła:", e);
    if (e instanceof ApiError && e.response?.errors) {
      const errorKeys = Object.keys(e.response.errors);
      if (errorKeys.length > 0) {
        passwordError.value = e.response.errors[errorKeys[0]][0];
      }
    } else {
      passwordError.value = e.message || "Wystąpił nieznany błąd.";
    }
  } finally {
    isChangingPassword.value = false;
  }
}
</script>