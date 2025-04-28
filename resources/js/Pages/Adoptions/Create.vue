<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '../../Layouts/AppLayout.vue'

const props = defineProps({
  selectedPet: Object,
  pets: Array,
})

const form = useForm({
  pet_id: props.selectedPet?.id || '',
  last_name: '',
  first_name: '',
  middle_name: '',
  address: '',
  contact_number: '',
  dob: '',
  valid_id: null,
  valid_id_back: null,
  previous_experience: '',
  other_pets: '',
  financial_preparedness: '',
})

const maxDob = computed(() => {
  const today = new Date()
  today.setFullYear(today.getFullYear() - 18)
  return today.toISOString().split('T')[0]
})

function submit() {
  form.post(route('adopt.store'))
}
</script>

<template>
  <AppLayout>
  <Head title="Apply for Adoption" />

  <div class="container mx-auto max-w-5xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh]">
    <h1 class="flex gap-[5px] sticky top-0 py-2 px-4 text-2xl font-bold bg-white z-10 justify-center">
      Apply for
      <span class="text-primary"> Adoption</span>
    </h1>

    <form @submit.prevent="submit" class="rounded-lg px-8 pt-6 pb-8 mb-4 space-y-6">
      
      <!-- Select Pet -->
      <div class="p-3 bg-gray-100 rounded-md">
        <label class="font-semibold">Select Pet</label>
        <select v-model="form.pet_id" class="w-full border p-2 rounded mt-2" required>
          <option value="" disabled>Choose a pet</option>
          <option v-for="pet in pets" :key="pet.id" :value="pet.id" :disabled="pet.status === 'adopted'">
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
          :max="name === 'dob' ? maxDob : null"
          :placeholder="label"
          :required="name !== 'middle_name'"
          class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
        />
        <label class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md">
          {{ label }}
        </label>

        <div v-if="form.errors[name]" class="text-red-500 text-sm mt-1">{{ form.errors[name] }}</div>
      </div>

      <!-- File Uploads -->
      <div class="relative bg-inherit">
        <label class="text-sm text-gray-600 font-semibold">Upload Valid ID (JPEG, PNG, JPG, PDF)</label>
        <input type="file" @input="form.valid_id = $event.target.files[0]" accept=".jpeg,.png,.jpg,.pdf" required
          class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none mt-2" />
        <div v-if="form.errors.valid_id" class="text-red-500 text-sm mt-1">{{ form.errors.valid_id }}</div>
      </div>

      <div class="relative bg-inherit">
        <label class="text-sm text-gray-600 font-semibold">Upload Back of Valid ID (JPEG, PNG, JPG, PDF)</label>
        <input type="file" @input="form.valid_id_back = $event.target.files[0]" accept=".jpeg,.png,.jpg,.pdf" required
          class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none mt-2" />
        <div v-if="form.errors.valid_id_back" class="text-red-500 text-sm mt-1">{{ form.errors.valid_id_back }}</div>
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
      <div class="flex space-y-4 md:flex-row md:space-x-4 md:space-y-0">
        <button type="submit"
          class="border-1 hover:border-primary bg-primary hover:bg-white hover:text-primary cursor-pointer text-white font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 ease-in-out">
          Apply Now
        </button>
        <Link href="/pets" class="border-1 hover:border-primary bg-white text-dark hover:text-primary font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 ease-in-out">
          Back
        </Link>
      </div>

    </form>
  </div>
  </AppLayout>
</template>