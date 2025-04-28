<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({
  adoptions: Array,
  pets: Array,
})
</script>

<template>
  <Head title="Adoption List" />

  <div class="container mx-auto mt-8">
    <h1 class="text-3xl font-bold mb-6">Adoption Applications</h1>

    <div class="bg-white shadow rounded-lg overflow-hidden">
      <table class="min-w-full leading-normal">
        <thead>
          <tr>
            <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Pet Name
            </th>
            <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Adopter Name
            </th>
            <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Status
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="adoption in adoptions" :key="adoption.id" class="border-b">
            <td class="px-5 py-5">{{ adoption.pet?.name || '-' }}</td>
            <td class="px-5 py-5">{{ adoption.user?.name || '-' }}</td>
            <td class="px-5 py-5 capitalize">{{ adoption.status }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
