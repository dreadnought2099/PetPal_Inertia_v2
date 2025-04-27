<template>
  <AppLayout>
    <div class="container mx-auto max-w-4xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-auto h-[80vh] p-6 space-y-10">
    <h1 class="flex items-center justify-center text-xl font-bold bg-white sticky top-0 py-3 px-4 z-10">
      Pending Adoption <span class="text-primary ml-1">Requests</span>
    </h1>
    <p class="text-gray-400 sticky top-12 px-4 z-10">
      Below are the pending requests for pet adoption
    </p>

    <template v-if="pendingAdoptions.length">
      <div v-for="adoption in pendingAdoptions" :key="adoption.id" class="flex items-center justify-between gap-4 px-4 py-4 border-b border-gray-200 w-full">
        <p class="text-gray-700">
          <span class="text-base text-primary font-medium">{{ adoption.first_name }} {{ adoption.middle_name }} {{ adoption.last_name }}</span>
          requested an adoption for
          <span class="text-base text-primary font-medium">{{ adoption.pet.name }}</span>
        </p>
        <button
          @click="openModal(adoption)"
          class="bg-primary text-white text-sm font-semibold rounded-md hover:bg-white hover:text-primary border hover:border-primary transition duration-300 px-4 py-2 cursor-pointer"
        >
          View Details
        </button>
      </div>
    </template>
    <template v-else>
      <div class="text-center py-6">
        <p class="px-6 py-3 text-gray-700 text-center">No pending adoption requests at the moment...</p>
      </div>
    </template>

    <!-- Modal -->
    <div
      v-if="modalAdoption"
      class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40"
      @click.self="closeModal"
    >
      <div class="flex flex-col relative border-1 shadow-md border-primary bg-white rounded-xl max-w-lg w-full p-6">
        <button
          @click="closeModal"
          title="Click to close the modal"
          class="absolute right-3 top-2 text-red-500 text-4xl hover:text-gray-400 hover:cursor-pointer"
        >&times;</button>

        <h2 class="text-xl mb-4 text-center py-6">Adoption Request <span class="text-primary">Details</span></h2>
        <p class="text-gray-700">Adopter's Name: <span class="text-primary">{{ modalAdoption.first_name }} {{ modalAdoption.middle_name }} {{ modalAdoption.last_name }}</span></p>
        <p class="text-gray-700">Pet Name: <span class="text-primary">{{ modalAdoption.pet.name }}</span></p>
        <p class="text-gray-700">Address: <span class="text-primary">{{ modalAdoption.address }}</span></p>
        <p class="text-gray-700">Contact Number: <span class="text-primary">{{ modalAdoption.contact_number }}</span></p>
        <p class="text-gray-700">Date of Birth: <span class="text-primary">{{ formatDate(modalAdoption.dob) }}</span></p>
        <p class="text-gray-700">Previous Pet Experience: <span class="text-primary">{{ capitalize(modalAdoption.previous_experience) }}</span></p>
        <p class="text-gray-700">Other pet at home: <span class="text-primary">{{ capitalize(modalAdoption.other_pets) }}</span></p>
        <p class="text-gray-700">Financial Preparedness: <span class="text-primary">{{ capitalize(modalAdoption.financial_preparedness) }}</span></p>
        <p class="text-gray-700">
          Valid ID:
          <a
            :href="`/storage/${modalAdoption.valid_id}`"
            target="_blank"
            class="text-primary px-2 hover-underline-hyperlink hover:scale-125 transition-all ease-in-out duration-300"
          >View Front</a>
          <a
            :href="`/storage/${modalAdoption.valid_id_back}`"
            target="_blank"
            class="text-primary hover-underline-hyperlink hover:scale-125 transition-all ease-in-out duration-300"
          >View Back</a>
        </p>
        <div class="flex justify-end gap-4 mt-4">
          <form @submit.prevent="approveAdoption(modalAdoption.id)">
            <button
              type="submit"
              class="bg-primary text-white px-4 py-2 rounded hover:bg-white hover:text-primary transition-all ease-in-out duration-300 border-1 border-primary cursor-pointer"
            >Approve</button>
          </form>
          <form @submit.prevent="rejectAdoption(modalAdoption.id)">
            <button
              type="submit"
              class="bg-red-500 text-white px-4 py-2 rounded hover:bg-white hover:text-red-500 border-1 border-red-500 transition-all ease-in-out cursor-pointer"
            >Reject</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const adoptions = usePage().props.adoptions || [];
const pendingAdoptions = computed(() => adoptions.filter(a => a.status === 'pending'));

const modalAdoption = ref(null);

function openModal(adoption) {
  modalAdoption.value = adoption;
}
function closeModal() {
  modalAdoption.value = null;
}

function approveAdoption(id) {
  router.patch(route('adopt.approve', id));
  closeModal();
}
function rejectAdoption(id) {
  router.patch(route('adopt.reject', id));
  closeModal();
}
function capitalize(str) {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
}
function formatDate(date) {
  return dayjs(date).format('MMMM DD, YYYY');
}
</script>
