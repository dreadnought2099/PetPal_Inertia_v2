<script setup>
import { ref } from 'vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

const pets = usePage().props.pets;  // You should pass 'pets' from the controller
const selectedPetId = ref(null);

const showPetModal = (id) => {
    selectedPetId.value = (selectedPetId.value === id) ? null : id;
};

const deletePet = (id, name) => {
    if (confirm(`Are you sure you want to delete pet ${name}?`)) {
        router.delete(route('pets.destroy', id));
    }
};
</script>

<template>
    <AppLayout>
        <div>
    <div class="text-center mt-4 relative w-full">
      <h2 class="text-3xl font-bold text-dark">
        Our <span class="text-primary">Pets</span>
      </h2>
    </div>

    <div class="flex flex-wrap gap-2 p-6 h-[70vh] overflow-y-auto select-none">
      <template v-if="pets.length">
        <div
          v-for="pet in pets"
          :key="pet.id"
          :class="[
            'basis-[249px] bg-white p-4 rounded-lg shadow-md relative border-1 transition-transform ease-in-out duration-300',
            pet.status === 'adopted'
              ? 'cursor-not-allowed pointer-events-none border-secondary opacity-75'
              : 'hover:scale-90 hover:shadow-lg border-primary cursor-pointer'
          ]"
          @click="pet.status !== 'adopted' && showPetModal(pet.id)"
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
            :class="{'grayscale': pet.status === 'adopted'}"
          />

          <h3 class="text-xl font-bold mt-4">{{ pet.name }}</h3>
          <p class="text-gray-600">{{ pet.breed }}</p>

          <p class="text-sm mt-2 font-medium"
             :class="{
                'text-note': pet.status === 'adopted',
                'text-yellow-500': pet.status === 'pending',
                'text-primary': pet.status === 'available'
             }">
            <span class="text-black">Status:</span> {{ pet.status ?? 'Available' }}
          </p>

          <template v-if="pet.status !== 'adopted'">
            <button
              @click.stop="showPetModal(pet.id)"
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

      <div v-else class="w-full flex flex-col items-center justify-center text-center py-16">
        <p class="text-lg text-gray-500 mb-4">
          No pets available for adoption at this time.
        </p>
        <Link
          v-if="$page.props.auth?.user?.can?.['create pet listing']"
          href="/pets/create"
          class="inline-block bg-primary text-white px-6 py-2.5 rounded-lg hover:bg-primary-dark transition duration-200 ease-in-out shadow-md"
        >
          Add a Pet
        </Link>
      </div>
    </div>

    <!-- Modals -->
    <div v-for="pet in pets" :key="`modal-${pet.id}`">
      <div
        v-if="selectedPetId === pet.id"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        @click.self="showPetModal(pet.id)"
      >
        <div class="bg-white flex relative flex-col rounded-xl w-100 max-w-md mx-2">
          <button
            @click="showPetModal(pet.id)"
            title="Click to close the modal"
            class="flex absolute right-3 text-red-500 text-4xl hover:text-gray-200 hover:cursor-pointer"
          >
            &times;
          </button>

          <img
            :src="pet.pet_profile_path ? `/storage/${pet.pet_profile_path}` : '/images/LRM_20240517_192913-01.jpeg'"
            :alt="pet.name"
            class="w-full h-48 object-cover rounded-t-xl mb-4"
          />

          <h2 class="text-2xl font-bold text-center mb-4">
            <span class="text-primary">{{ pet.name }}</span>
          </h2>

          <div class="p-2 grid grid-cols-2 gap-4 text-sm">
            <p>Breed: <span class="text-primary">{{ pet.breed }}</span></p>
            <p>Age: <span class="text-primary">{{ pet.age }} year/s</span></p>
            <p>Sex: <span class="text-primary">{{ pet.sex }}</span></p>
            <p>Species: <span class="text-primary">{{ pet.species_text }}</span></p>
            <p>Allergies: <span class="text-primary">{{ pet.allergies }}</span></p>
            <p>Vaccination: <span class="text-primary">{{ pet.vaccination_text }}</span></p>
            <p>Spayed/Neutered: <span class="text-primary">{{ pet.spayed_neutered ? 'Yes' : 'No' }}</span></p>
          </div>

          <div class="p-2">
            <p class="mt-3">Description:</p>
            <p class="border p-2 rounded bg-gray-50 text-sm break-words max-h-40 overflow-auto">
              {{ pet.description }}
            </p>
          </div>

          <div class="flex justify-between p-2 w-full">
            <Link
              v-if="$page.props.auth?.user?.can?.['edit pet listing']"
              :href="route('pets.edit', pet.id)"
              class="bg-primary text-white px-4 py-2 rounded hover:bg-white hover:text-primary hover:scale-105 border border-primary transition-all"
            >
              Edit
            </Link>

            <button
              v-if="$page.props.auth?.user?.can?.['delete pet listing']"
              @click="deletePet(pet.id, pet.name)"
              class="bg-red-500 text-white px-4 py-2 rounded hover:bg-white hover:text-secondary border hover:border-secondary hover:scale-105 transition-all"
            >
              Delete
            </button>
          </div>

          <div class="flex p-2 w-full">
            <Link
              v-if="!$page.props.auth?.user || $page.props.auth?.user?.roles.includes('Adopter')"
              :href="route('adopt.apply', pet.id)"
              class="w-full px-4 py-2 bg-primary text-white text-sm font-semibold rounded-md
                      hover:bg-white hover:text-primary border hover:border-primary transition duration-300 text-center"
            >
              Adopt Now
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
    </AppLayout>
</template>
