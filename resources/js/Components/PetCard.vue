<template>
  <div
    :class="[
      'hover:scale-95 transition-transform ease-in-out duration-300',
      'w-full sm:basis-[300px] bg-white p-1.5 sm:p-3 md:p-4 rounded-lg shadow-md relative border-1',
      pet.status === 'adopted'
        ? 'cursor-not-allowed pointer-events-none border-secondary opacity-75'
        : 'hover:shadow-lg border-primary transition-shadow duration-200 cursor-pointer'
    ]"
    @click="$emit('card-click', pet)"
  >
    <span
      v-if="pet.status === 'adopted'"
      class="absolute top-1.5 right-1.5 sm:top-2 sm:right-2 bg-note text-white text-[10px] sm:text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full z-20"
    >
      Adopted
    </span>

    <div class="relative h-28 sm:h-36 md:h-44 mb-1.5 sm:mb-3">
      <img
        :src="pet.pet_profile_path ? `/storage/${pet.pet_profile_path}` : '/images/LRM_20240517_192913-01.jpeg'"
        :alt="pet.name"
        class="w-full h-full object-cover rounded-lg"
        :class="{ grayscale: pet.status === 'adopted' }"
      />
    </div>

    <div class="space-y-0.5 sm:space-y-1.5">
      <h3 class="text-sm sm:text-base md:text-lg font-bold text-gray-800">{{ pet.name }}</h3>
      <p class="text-[10px] sm:text-xs md:text-sm text-gray-600">{{ pet.breed }}</p>
      
      <div class="flex flex-wrap items-center gap-x-1.5 sm:gap-x-2 text-[10px] sm:text-xs">
        <span class="text-gray-500">Age:</span>
        <span class="text-primary">{{ pet.age }} year/s</span>
        <span class="text-gray-300">|</span>
        <span class="text-gray-500">Sex:</span>
        <span class="text-primary">{{ pet.sex }}</span>
      </div>

      <p
        class="text-[10px] sm:text-xs font-medium"
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
        class="mt-1.5 sm:mt-3 text-primary cursor-pointer w-full text-center py-1 sm:py-1.5 text-[10px] sm:text-xs md:text-sm border border-primary rounded-md hover:bg-primary hover:text-white transition-colors duration-300"
      >
        See more
      </button>
    </template>
    <template v-else>
      <p class="mt-1.5 sm:mt-3 text-note text-[10px] sm:text-xs text-center">{{ pet.name }} has been adopted</p>
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
