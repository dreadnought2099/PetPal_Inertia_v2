<script setup>
import { ref } from 'vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import PetCard from '@/Components/PetCard.vue';

const pets = usePage().props.pets || [];
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

    <div v-if="pets.length" class="flex flex-wrap gap-2 p-6 h-[70vh] overflow-y-auto select-none">
      <PetCard
        v-for="pet in pets"
        :key="pet.id"
        :pet="pet"
        @see-more="showPetModal"
        @card-click="showPetModal"
      />
    </div>
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
