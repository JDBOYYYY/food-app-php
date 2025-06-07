<template>
  <div class="bg-white shadow rounded-lg overflow-x-auto">
    <div class="p-4 flex justify-between items-center">
      <h3 class="text-lg font-medium text-gray-700">{{ title }}</h3>
      <button
        @click="$emit('create')"
        class="bg-green-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-600 transition"
      >
        Add New
      </button>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th
            v-for="header in headers"
            :key="header.key"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
          >
            {{ header.label }}
          </th>
          <th class="relative px-6 py-3">
            <span class="sr-only">Actions</span>
          </th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr v-if="isLoading">
          <td :colspan="headers.length + 1" class="text-center py-10">
            Loading...
          </td>
        </tr>
        <tr v-else-if="items.length === 0">
          <td :colspan="headers.length + 1" class="text-center py-10">
            No items found.
          </td>
        </tr>
        <tr v-for="item in items" :key="item.Id">
          <td
            v-for="header in headers"
            :key="header.key"
            class="px-6 py-4 whitespace-nowrap text-sm"
            :class="header.key === 'Name' ? 'font-medium text-gray-900' : 'text-gray-500'"
          >
            {{ getNestedValue(item, header.key) }}
          </td>
          <td
            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2"
          >
            <button
              @click="$emit('edit', item)"
              class="text-indigo-600 hover:text-indigo-900"
            >
              Edit
            </button>
            <button
              @click="$emit('delete', item)"
              class="text-red-600 hover:text-red-900"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string;
  headers: { key: string; label: string }[];
  items: any[];
  isLoading: boolean;
}>();

defineEmits(['create', 'edit', 'delete']);

// Helper to access nested properties like 'Category.Name'
const getNestedValue = (obj: any, path: string) => {
  return path.split('.').reduce((o, i) => (o ? o[i] : undefined), obj);
};
</script>