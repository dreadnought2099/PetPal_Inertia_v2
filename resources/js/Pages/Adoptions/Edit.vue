<script setup>
import { useForm, Head } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({
  adoption: Object,
  pets: Array,
})

const form = useForm({
  pet_id: adoption.pet_id,
  last_name: adoption.last_name,
  first_name: adoption.first_name,
  middle_name: adoption.middle_name,
  address: adoption.address,
  contact_number: adoption.contact_number,
  dob: adoption.dob,
  valid_id: null,
  valid_id_back: null,
  previous_experience: adoption.previous_experience,
  other_pets: adoption.other_pets,
  financial_preparedness: adoption.financial_preparedness,
})

const showImageModal = ref(false)
const modalImageSrc = ref('')

function submit() {
  form.put(route('adopt.update', adoption.id))
}

function openImageModal(src) {
  modalImageSrc.value = src
  showImageModal.value = true
}

function closeImageModal() {
  showImageModal.value = false
}
</script>

<template>
  <Head title="Edit Adoption Request" />

  <div class="container mx-auto max-w-5xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh]">
    <h1 class="flex gap-[5px] sticky top-0 py-2 px-4 text-2xl font-bold bg-white z-10 justify-center">
      Edit
      <span class="text-primary"> Adoption</span>
      Details
    </h1>

    <form @submit.prevent="submit" class="space-y-4 p-6 mb-6 rounded-lg">
      <!-- Select Pet -->
      <div class="p-3 bg-gray-100 rounded-md">
        <label class="font-semibold">Select Pet</label>
        <select v-model="form.pet_id" class="w-full border p-2 rounded mt-2" required>
          <option value="" disabled>Choose a pet</option>
          <option v-for="pet in pets" :key="pet.id" :value="pet.id">
            {{ pet.name }} - {{ pet.breed }}
          </option>
        </select>
      </div>

      <!-- Input Fields -->
      <div v-for="(label, name) in {
        last_name: 'Last Name',
        first_name: 'First Name',
        middle_name: 'Middle Name',
        address: 'Address',
        contact_number: 'Contact Number',
        dob: 'Date of Birth'
      }" :key="name" class="relative bg-inherit">
        
        <input
          v-model="form[name]"
          :type="name === 'dob' ? 'date' : 'text'"
          :placeholder="label"
          :required="name !== 'middle_name'"
          class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
        />
        <label class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md">
          {{ label }}
        </label>
      </div>

      <!-- File Uploads -->
      <div class="mb-4">
        <div class="relative bg-inherit">
          <input type="file" @input="form.valid_id = $event.target.files[0]" accept=".jpeg,.png,.jpg,.pdf"
            class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none" />
          <label class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md">
            Upload Valid ID (JPEG, PNG, JPG, PDF)
          </label>
          <img v-if="adoption.valid_id" :src="'/storage/' + adoption.valid_id" alt="Valid ID"
            class="mt-2 w-32 h-32 object-cover rounded-md cursor-pointer"
            @click="openImageModal('/storage/' + adoption.valid_id)" />
        </div>
      </div>

      <div class="mb-4">
        <div class="relative bg-inherit">
          <input type="file" @input="form.valid_id_back = $event.target.files[0]" accept=".jpeg,.png,.jpg,.pdf"
            class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none" />
          <label class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md">
            Upload Back of Valid ID (JPEG, PNG, JPG, PDF)
          </label>
          <img v-if="adoption.valid_id_back" :src="'/storage/' + adoption.valid_id_back" alt="Valid ID Back"
            class="mt-2 w-32 h-32 object-cover rounded-md cursor-pointer"
            @click="openImageModal('/storage/' + adoption.valid_id_back)" />
        </div>
      </div>

      <!-- Radio Questions -->
      <div v-for="(question, name) in {
        previous_experience: 'Do you have previous pet ownership experience?',
        other_pets: 'Do you have other pets at home?',
        financial_preparedness: 'Are you financially prepared for pet care?'
      }" :key="name" class="mb-4">
        <fieldset class="font-semibold">
          <legend>{{ question }}</legend>
          <label class="mr-4">
            <input type="radio" :name="name" value="yes" v-model="form[name]" required /> Yes
          </label>
          <label>
            <input type="radio" :name="name" value="no" v-model="form[name]" required /> No
          </label>
        </fieldset>
      </div>

      <!-- Buttons -->
      <div class="flex flex-col space-y-4 md:flex-row md:space-x-4 md:space-y-0">
        <button type="submit"
          class="border-1 hover:border-primary bg-primary hover:bg-white hover:text-primary text-white font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 ease-in-out cursor-pointer">
          Save changes
        </button>
        <a :href="route().previous()"
          class="border-1 hover:border-primary bg-white hover:bg-white hover:text-primary text-dark font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 ease-in-out">
          Back
        </a>
      </div>
    </form>

    <!-- Image Modal -->
    <div v-if="showImageModal" class="fixed inset-0 flex items-center justify-center z-50 p-6 bg-opacity-75 bg-black"
      @click="closeImageModal">
      <div class="relative rounded-xl shadow-2xl p-0 bg-transparent max-w-lg">
        <img :src="modalImageSrc" alt="ID Image"
          class="w-auto h-auto max-w-full max-h-80 rounded-lg" />
        <button @click="closeImageModal"
          class="absolute -top-9 -right-6 text-gray-500 text-4xl hover:text-red-500 transition cursor-pointer">
          &times;
        </button>
      </div>
    </div>
  </div>
</template>