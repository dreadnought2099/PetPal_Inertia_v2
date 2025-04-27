<template>
  <div
    :class="[
      'hover:scale-90 transition-transform ease-in-out duration-300',
      'basis-[249px] bg-white p-4 rounded-lg shadow-md relative border-1',
      pet.status === 'adopted'
        ? 'cursor-not-allowed pointer-events-none border-secondary opacity-75'
        : 'hover:shadow-lg border-primary transition-shadow duration-200 cursor-pointer'
    ]"
    @click="handleCardClick"
  >
    <span
      v-if="pet.status === 'adopted'"
      class="absolute top-2 right-2 bg-note text-white text-xs px-2 py-1 rounded-full z-20"
    >
      Adopted
    </span>

    <img
      :src="pet.pet_profile_path ? `/storage/${pet.pet_profile_path}` : '/images/LRM_20240517_192913-01.jpeg'"
      :alt="pet.name"
      class="w-full h-48 object-cover rounded-lg"
      :class="{ grayscale: pet.status === 'adopted' }"
    />

    <h3 class="text-xl font-bold mt-4">{{ pet.name }}</h3>
    <p class="text-gray-600">{{ pet.breed }}</p>

    <p
      class="text-sm mt-2 font-medium"
      :class="{
        'text-note': pet.status === 'adopted',
        'text-yellow-500': pet.status === 'pending',
        'text-primary': !pet.status || pet.status === 'available'
      }"
    >
      <span class="text-black">Status:</span> {{ capitalize(pet.status || 'available') }}
    </p>

    <template v-if="pet.status !== 'adopted'">
      <button
        @click.stop="onSeeMore"
        class="mt-4 text-primary hover-underline-hyperlink cursor-pointer"
      >
        See more
      </button>
    </template>
    <template v-else>
      <p class="mt-4 text-note text-sm">{{ pet.name }} has been adopted</p>
    </template>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  pet: Object,
});
const emit = defineEmits(['see-more', 'card-click']);

function onSeeMore() {
  emit('see-more', props.pet);
}
function handleCardClick() {
  if (props.pet.status !== 'adopted') {
    emit('card-click', props.pet);
  }
}
function capitalize(str) {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
}
</script>
