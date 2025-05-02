<template>
  <div
    :class="[
      'hover:scale-95 transition-transform ease-in-out duration-300',
      'basis-[300px] bg-white p-4 rounded-lg shadow-md relative border-1',
      pet.status === 'adopted'
        ? 'cursor-not-allowed pointer-events-none border-secondary opacity-75'
        : 'hover:shadow-lg border-primary transition-shadow duration-200 cursor-pointer'
    ]"
    @click="$emit('card-click', pet)"
  >
    <span
      v-if="pet.status === 'adopted'"
      class="absolute top-2 right-2 bg-note text-white text-xs px-2 py-1 rounded-full z-20"
    >
      Adopted
    </span>

    <div class="relative h-48 mb-4">
      <img
        :src="pet.pet_profile_path ? `/storage/${pet.pet_profile_path}` : '/images/LRM_20240517_192913-01.jpeg'"
        :alt="pet.name"
        class="w-full h-full object-cover rounded-lg"
        :class="{ grayscale: pet.status === 'adopted' }"
      />
    </div>

    <div class="space-y-2">
      <h3 class="text-xl font-bold text-gray-800">{{ pet.name }}</h3>
      <p class="text-gray-600">{{ pet.breed }}</p>
      
      <div class="flex items-center space-x-2 text-sm">
        <span class="text-gray-500">Age:</span>
        <span class="text-primary">{{ pet.age }} year/s</span>
        <span class="text-gray-300">|</span>
        <span class="text-gray-500">Sex:</span>
        <span class="text-primary">{{ pet.sex }}</span>
      </div>

      <p
        class="text-sm font-medium"
        :class="{
          'text-note': pet.status === 'adopted',
          'text-yellow-500': pet.status === 'pending',
          'text-primary': !pet.status || pet.status === 'available'
        }"
      >
        <span class="text-gray-600">Status:</span> {{ capitalize(pet.status || 'available') }}
      </p>
    </div>

    <template v-if="pet.status !== 'adopted'">
      <button
        @click.stop="$emit('see-more', pet)"
        class="mt-4 text-primary cursor-pointer w-full text-center py-2 border border-primary rounded-md hover:bg-primary hover:text-white transition-colors duration-300"
      >
        See more
      </button>
    </template>
    <template v-else>
      <p class="mt-4 text-note text-sm text-center">{{ pet.name }} has been adopted</p>
    </template>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  pet: Object,
});
const emit = defineEmits(['see-more', 'card-click']);

function capitalize(str) {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
}
</script>
