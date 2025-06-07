<template>
  <div>
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">
      Manage Categories
    </h2>
    <div class="bg-white shadow rounded-lg">
      <div v-if="isLoading" class="p-4">Loading...</div>
      <table v-else class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              ID
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Name
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="category in categories" :key="category.id">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ category.id }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ category.name }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { categoryService, type CategoryDto } from "../../services";

const categories = ref<CategoryDto[]>([]);
const isLoading = ref(true);

onMounted(async () => {
  try {
    categories.value = await categoryService.getAll();
  } catch (e) {
    console.error(e);
  } finally {
    isLoading.value = false;
  }
});
</script>