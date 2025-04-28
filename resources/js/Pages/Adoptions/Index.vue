<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'

defineProps({
  adoptions: Array,
  pets: Array,
})
</script>

<template>
  <Head title="Available Pets for Adoption" />

  <AppLayout>
    <div class="container mx-auto max-w-5xl bg-white mt-6 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh] p-6">
      <div class="relative w-full">
        <h2 class="flex gap-[5px] sticky top-0 py-3 px-6 text-2xl font-semibold bg-white z-10 justify-center">
          Available <span class="text-primary">Pets</span>
        </h2>

        <div v-if="pets && pets.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
          <div v-for="pet in pets" :key="pet.id" 
               class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300"
               v-if="pet.status === 'available'">
            <div class="aspect-w-16 aspect-h-9">
              <img :src="pet.pet_profile_path" :alt="pet.name" class="object-cover w-full h-48">
            </div>
            <div class="p-4">
              <h3 class="text-lg font-semibold text-gray-800">{{ pet.name }}</h3>
              <p class="text-sm text-gray-600">{{ pet.breed }}</p>
              <p class="text-sm text-gray-500 mt-2">{{ pet.description }}</p>
              <div class="mt-4 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                  <span class="text-sm text-gray-600">Age: {{ pet.age }}</span>
                  <span class="text-sm text-gray-600">|</span>
                  <span class="text-sm text-gray-600">{{ pet.sex }}</span>
                </div>
                <Link 
                  :href="`/adopt/apply/${pet.id}`"
                  class="bg-primary text-white px-4 py-2 rounded-md hover:bg-white hover:text-primary border border-primary transition-all duration-300"
                >
                  Adopt Now
                </Link>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="text-center py-8">
          <p class="text-gray-500">No pets available for adoption at the moment.</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
