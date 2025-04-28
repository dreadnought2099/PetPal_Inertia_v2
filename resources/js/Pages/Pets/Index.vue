<script setup>
import { ref, computed } from "vue";
import { usePage, router, Link } from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";
import PetCard from "@/Components/PetCard.vue";

const pets = usePage().props.pets || [];
const selectedPet = ref(null);

const user = usePage().props.auth?.user || null;
const roles = user?.roles || [];
const isAdopter = roles.includes('Adopter');
const isShelterOrAdmin = roles.includes('Shelter') || roles.includes('Administrator');

function showPetModal(pet) {
    selectedPet.value = pet;
}

const deletePet = (id, name) => {
    if (confirm(`Are you sure you want to delete pet ${name}?`)) {
        router.delete(`/pets/${id}`);
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

        <div
                v-if="pets.length"
                class="flex flex-wrap gap-2 p-6 h-[70vh] overflow-y-auto select-none"
            >
                <PetCard
          v-for="pet in pets"
          :key="pet.id"
                    :pet="pet"
                    @see-more="showPetModal"
                    @card-click="showPetModal"
                />
        </div>
            <div
                v-else
                class="w-full flex flex-col items-center justify-center text-center py-16"
            >
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
            <div v-if="selectedPet">
                <div
                    class="modal fixed inset-0 flex items-center justify-center z-50"
                    @click.self="selectedPet = null"
      >
                    <div
                        class="bg-white flex relative flex-col rounded-xl w-100 max-w-md mx-2"
                    >
          <button
                            @click="selectedPet = null"
            title="Click to close the modal"
            class="flex absolute right-3 text-red-500 text-4xl hover:text-gray-200 hover:cursor-pointer"
          >
            &times;
          </button>

          <img
                            :src="
                                selectedPet.pet_profile_path
                                    ? `/storage/${selectedPet.pet_profile_path}`
                                    : '/images/LRM_20240517_192913-01.jpeg'
                            "
                            :alt="selectedPet.name"
            class="w-full h-48 object-cover rounded-t-xl mb-4"
          />

          <h2 class="text-2xl font-bold text-center mb-4">
                            <span class="text-primary">{{ selectedPet.name }}</span>
          </h2>

          <div class="p-2 grid grid-cols-2 gap-4 text-sm">
                            <p>
                                Breed:
                                <span class="text-primary">{{
                                    selectedPet.breed
                                }}</span>
                            </p>
                            <p>
                                Age:
                                <span class="text-primary"
                                    >{{ selectedPet.age }} year/s</span
                                >
                            </p>
                            <p>
                                Sex:
                                <span class="text-primary">{{ selectedPet.sex }}</span>
                            </p>
                            <p>
                                Species:
                                <span class="text-primary">{{
                                    selectedPet.species_text
                                }}</span>
                            </p>
                            <p>
                                Allergies:
                                <span class="text-primary">{{
                                    selectedPet.allergies
                                }}</span>
                            </p>
                            <p>
                                Vaccination:
                                <span class="text-primary">{{
                                    selectedPet.vaccination_text
                                }}</span>
                            </p>
                            <p>
                                Spayed/Neutered:
                                <span class="text-primary">{{
                                    selectedPet.spayed_neutered ? "Yes" : "No"
                                }}</span>
                            </p>
          </div>

          <div class="p-2">
            <p class="mt-3">Description:</p>
                            <p
                                class="border p-2 rounded bg-gray-50 text-sm break-words max-h-40 overflow-auto"
                            >
                                {{ selectedPet.description }}
            </p>
          </div>

          <div class="flex justify-between p-2 w-full">
            <Link
                                v-if="isShelterOrAdmin"
                                :href="`/pets/${selectedPet.id}/edit`"
              class="bg-primary text-white px-4 py-2 rounded hover:bg-white hover:text-primary hover:scale-105 border border-primary transition-all"
            >
              Edit
            </Link>

            <button
                                v-if="isShelterOrAdmin"
                                @click="deletePet(selectedPet.id, selectedPet.name)"
              class="bg-red-500 text-white px-4 py-2 rounded hover:bg-white hover:text-secondary border hover:border-secondary hover:scale-105 transition-all"
            >
              Delete
            </button>
          </div>

          <div class="flex p-2 w-full">
            <Link
                                v-if="!user || isAdopter"
                                :href="`/adopt/apply/${selectedPet.id}`"
                                class="w-full px-4 py-2 bg-primary text-white text-sm font-semibold rounded-md hover:bg-white hover:text-primary border hover:border-primary transition duration-300 text-center"
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

<style scoped>
.modal {
    background-color: rgba(0, 0, 0, 0.4);
}
</style>