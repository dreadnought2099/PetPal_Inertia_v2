<template>
  <div class="container mx-auto max-w-5xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh]">
    <h1 class="flex gap-[5px] sticky top-0 py-2 px-4 text-2xl font-bold bg-white z-10 justify-center">
      Edit <span class="text-primary">Adoption</span> Details
    </h1>

    <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4 p-6 mb-6 rounded-lg">
      <!-- Select Pet -->
      <div class="p-3 bg-gray-100 rounded-md">
        <label class="font-semibold">Select Pet</label>
        <select v-model="form.pet_id" class="w-full border p-2 rounded mt-2" required>
          <option value="" disabled>Choose a pet</option>
          <option
            v-for="pet in pets"
            :key="pet.id"
            :value="pet.id"
          >
            {{ pet.name }} - {{ pet.breed }}
          </option>
        </select>
        <p v-if="form.errors.pet_id" class="text-red-500 text-xs mt-1">{{ form.errors.pet_id }}</p>
      </div>

      <!-- Name, Address, Contact, DOB -->
      <div v-for="field in fields" :key="field.name" class="relative bg-inherit">
        <input
          :type="field.type"
          :name="field.name"
          v-model="form[field.name]"
          :placeholder="field.label"
          :required="field.required"
          class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
        />
        <label
          class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
        >
          {{ field.label }}
        </label>
        <p v-if="form.errors[field.name]" class="text-red-500 text-xs mt-1">{{ form.errors[field.name] }}</p>
      </div>

      <!-- Valid ID Upload -->
      <div class="mb-4">
        <div class="relative bg-inherit">
          <input
            type="file"
            id="valid_id"
            name="valid_id"
            accept=".jpeg,.png,.jpg,.pdf"
            @change="e => form.valid_id = e.target.files[0]"
            class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
          />
          <label for="valid_id" class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md">
            Upload Valid ID (JPEG, PNG, JPG, PDF)
          </label>
          <div v-if="adoption.valid_id" class="mt-2">
            <img
              :src="`/storage/${adoption.valid_id}`"
              alt="Valid ID"
              class="w-32 h-32 object-cover rounded-md cursor-pointer"
              @click="showImageModal(`/storage/${adoption.valid_id}`)"
            />
          </div>
          <p v-if="form.errors.valid_id" class="text-red-500 text-xs mt-1">{{ form.errors.valid_id }}</p>
        </div>
      </div>

      <!-- Back of Valid ID Upload -->
      <div class="mb-4">
        <div class="relative bg-inherit">
          <input
            type="file"
            id="valid_id_back"
            name="valid_id_back"
            accept=".jpeg,.png,.jpg,.pdf"
            @change="e => form.valid_id_back = e.target.files[0]"
            class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
          />
          <label for="valid_id_back" class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md">
            Upload Back of Valid ID (JPEG, PNG, JPG, PDF)
          </label>
          <div v-if="adoption.valid_id_back" class="mt-2">
            <img
              :src="`/storage/${adoption.valid_id_back}`"
              alt="Valid ID Back"
              class="w-32 h-32 object-cover rounded-md cursor-pointer"
              @click="showImageModal(`/storage/${adoption.valid_id_back}`)"
            />
          </div>
          <p v-if="form.errors.valid_id_back" class="text-red-500 text-xs mt-1">{{ form.errors.valid_id_back }}</p>
        </div>
      </div>

      <!-- Image Modal -->
      <div
        v-if="modalImage"
        class="fixed inset-0 flex items-center justify-center z-50 p-6 bg-black bg-opacity-75"
        @click.self="closeImageModal"
      >
        <div class="relative rounded-xl shadow-2xl p-0 bg-transparent max-w-lg">
          <img :src="modalImage" alt="Valid ID" class="w-auto h-auto max-w-full max-h-80 rounded-lg" />
          <button
            type="button"
            @click="closeImageModal"
            class="absolute -top-9 -right-6 text-gray-500 text-4xl hover:text-red-500 transition cursor-pointer"
          >&times;</button>
        </div>
      </div>

      <!-- Radio Questions -->
      <div v-for="(question, name) in questions" :key="name" class="mb-4">
        <fieldset>
          <legend class="font-semibold">{{ question }}</legend>
          <label>
            <input type="radio" :name="name" value="yes" v-model="form[name]" required /> Yes
          </label>
          <label class="ml-4">
            <input type="radio" :name="name" value="no" v-model="form[name]" required /> No
          </label>
        </fieldset>
        <p v-if="form.errors[name]" class="text-red-500 text-xs mt-1">{{ form.errors[name] }}</p>
      </div>

      <!-- Buttons -->
      <div class="flex flex-col space-y-4 md:flex-row md:space-x-4 md:space-y-0">
        <button
          type="submit"
          class="border-1 hover:border-primary bg-primary hover:bg-white hover:text-primary text-white font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 ease-in-out cursor-pointer"
        >
          Save changes
        </button>
        <button
          type="button"
          @click="goBack"
          class="border-1 hover:border-primary bg-white hover:bg-white hover:text-primary text-dark font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 ease-in-out"
        >
          Back
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

const page = usePage();
const adoption = page.props.adoption;
const pets = page.props.pets || [];

const fields = [
  { name: 'last_name', label: 'Last Name', type: 'text', required: true },
  { name: 'first_name', label: 'First Name', type: 'text', required: true },
  { name: 'middle_name', label: 'Middle Name', type: 'text', required: false },
  { name: 'address', label: 'Address', type: 'text', required: true },
  { name: 'contact_number', label: 'Contact Number', type: 'text', required: true },
  { name: 'dob', label: 'Date of Birth', type: 'date', required: true },
];

const questions = {
  previous_experience: 'Do you have previous pet ownership experience?',
  other_pets: 'Do you have other pets at home?',
  financial_preparedness: 'Are you financially prepared for pet care?',
};

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
});

const modalImage = ref(null);

function showImageModal(src) {
  modalImage.value = src;
}
function closeImageModal() {
  modalImage.value = null;
}

function submit() {
  form.post(route('adopt.update', adoption.id), {
    method: 'put',
    forceFormData: true,
    onSuccess: () => form.reset('valid_id', 'valid_id_back'),
  });
}

function goBack() {
  window.history.back();
}
</script>
