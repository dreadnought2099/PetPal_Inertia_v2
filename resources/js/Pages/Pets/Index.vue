<script setup>
import { ref } from "vue";
import { usePage, router, Link } from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";
import PetCard from "@/Components/PetCard.vue";

const page = usePage();
const pets = page.props.pets || [];
const selectedPet = ref(null);

const user = usePage().props.auth?.user || null;
const roles = user?.roles || [];
const isAdopter = roles.includes("Adopter");
const isShelterOrAdmin =
    roles.includes("Shelter") || roles.includes("Administrator");

function showPetModal(pet) {
    selectedPet.value = pet;
}

const deletePet = (id, name) => {
    if (confirm(`Are you sure you want to delete pet ${name}?`)) {
        router.delete(`/pets/${id}`, {
            preserveScroll: true,
            onSuccess: () => {
                // Close the modal if it shows the deleted pet
                if (selectedPet.value?.id === id) {
                    selectedPet.value = null;
                }

                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            },
        });
    }
};
</script>

<template>
    <AppLayout>
        <div class="container mx-auto">
            <div class="text-center mt-2 sm:mt-4 relative w-full">
                <h2 class="text-xl sm:text-3xl font-bold text-dark">
                    Our <span class="text-primary">Pets</span>
                </h2>
            </div>

            <!-- Pagination -->
            <div class="flex justify-end mt-2 sm:mt-4 mr-2 sm:mr-6 space-x-1 sm:space-x-2">
                <button
                    v-for="(link, index) in pets.links"
                    :key="index"
                    @click="router.get(link.url)"
                    class="px-2 sm:px-4 py-1 sm:py-2 rounded-md transition-all duration-300 text-[10px] sm:text-sm"
                    :class="{
                        'bg-primary text-white': link.active,
                        'bg-gray-200 text-gray-700 hover:bg-gray-300': !link.active && link.url,
                        'bg-gray-100 text-gray-400 cursor-not-allowed': !link.url,
                    }"
                    :disabled="!link.url"
                >
                    <span v-html="link.label"></span>
                </button>
            </div>

            <div
                v-if="pets.data && pets.data.length"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-6 p-2 sm:p-6 h-[70vh] overflow-y-auto select-none"
            >
                <PetCard
                    v-for="pet in pets.data"
                    :key="pet.id"
                    :pet="pet"
                    @see-more="showPetModal"
                    @card-click="showPetModal"
                />
            </div>
            <div
                v-else
                class="w-full flex flex-col items-center justify-center text-center py-8 sm:py-16"
            >
                <p class="text-sm sm:text-lg text-gray-500 mb-2 sm:mb-4">
                    No pets available for adoption at this time.
                </p>
                <Link
                    v-if="$page.props.auth?.user?.can?.['create pet listing']"
                    href="/pets/create"
                    class="inline-block bg-primary text-white px-3 sm:px-6 py-1.5 sm:py-2.5 rounded-lg hover:bg-primary-dark transition duration-200 ease-in-out shadow-md text-[10px] sm:text-sm"
                >
                    Add a Pet
                </Link>
            </div>

            <!-- Modals -->
            <div v-if="selectedPet">
                <div class="modal fixed inset-0 flex items-center justify-center z-20" @click.self="selectedPet = null">
                    <div class="bg-white flex relative flex-col rounded-xl w-100 sm:max-w-md mx-2">
                        <button
                            @click="selectedPet = null"
                            title="Click to close the modal"
                            class="flex absolute right-2 sm:right-3 text-red-500 text-2xl sm:text-4xl hover:text-gray-200 hover:cursor-pointer"
                        >
                            &times;
                        </button>

                        <img
                            :src="selectedPet.pet_profile_path ? `/storage/${selectedPet.pet_profile_path}` : '/images/LRM_20240517_192913-01.jpeg'"
                            :alt="selectedPet.name"
                            class="w-full h-32 sm:h-48 object-cover rounded-t-xl mb-2 sm:mb-4"
                        />

                        <h2 class="text-lg sm:text-2xl font-bold text-center mb-2 sm:mb-4">
                            <span class="text-primary">{{ selectedPet.name }}</span>
                        </h2>

                        <div class="p-1 sm:p-2 grid grid-cols-2 gap-2 sm:gap-4 text-[10px] sm:text-sm">
                            <p>Breed: <span class="text-primary">{{ selectedPet.breed }}</span></p>
                            <p>Age: <span class="text-primary">{{ selectedPet.age }} year/s</span></p>
                            <p>Sex: <span class="text-primary">{{ selectedPet.sex }}</span></p>
                            <p>Species: <span class="text-primary">{{ selectedPet.species_text }}</span></p>
                            <p>Allergies: <span class="text-primary">{{ selectedPet.allergies }}</span></p>
                            <p>Vaccination: <span class="text-primary">{{ selectedPet.vaccination_text }}</span></p>
                            <p>Spayed/Neutered: <span class="text-primary">{{ selectedPet.spayed_neutered ? "Yes" : "No" }}</span></p>
                        </div>

                        <div class="p-1 sm:p-2">
                            <p class="mt-2 sm:mt-3 text-[10px] sm:text-sm">Description:</p>
                            <p class="border p-1 sm:p-2 rounded bg-gray-50 text-[10px] sm:text-sm break-words max-h-32 sm:max-h-40 overflow-auto">
                                {{ selectedPet.description }}
                            </p>
                        </div>

                        <div class="flex justify-between p-1 sm:p-2 w-full">
                            <Link
                                v-if="isShelterOrAdmin"
                                :href="`/pets/${selectedPet.id}/edit`"
                                class="bg-primary text-white px-2 sm:px-4 py-1 sm:py-2 rounded hover:bg-white hover:text-primary hover:scale-105 border border-primary transition-all text-[10px] sm:text-sm"
                            >
                                Edit
                            </Link>

                            <button
                                v-if="isShelterOrAdmin"
                                @click="deletePet(selectedPet.id, selectedPet.name)"
                                class="bg-red-500 text-white px-2 sm:px-4 py-1 sm:py-2 rounded hover:bg-white hover:text-secondary border hover:border-secondary hover:scale-105 transition-all cursor-pointer text-[10px] sm:text-sm"
                            >
                                Delete
                            </button>
                        </div>

                        <div class="flex p-1 sm:p-2 w-full">
                            <Link
                                v-if="!user || isAdopter"
                                :href="`/adopt/apply/${selectedPet.id}`"
                                class="w-full px-2 sm:px-4 py-1 sm:py-2 bg-primary text-white text-[10px] sm:text-sm font-semibold rounded-md hover:bg-white hover:text-primary border hover:border-primary transition duration-300 text-center"
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
