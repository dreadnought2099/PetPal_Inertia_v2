<template>
    <AppLayout>
        <div
            class="container mx-auto max-w-5xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh]"
        >
            <h1
                class="flex gap-2 sticky top-0 py-2 px-4 text-2xl font-bold bg-white z-10 justify-center"
            >
                Edit <span class="text-primary">Adoption</span> Details
            </h1>

            <form
                @submit.prevent="submitForm"
                class="space-y-4 p-6 mb-6 rounded-lg"
                enctype="multipart/form-data"
            >
                <!-- Select Pet -->
                <div class="p-3 bg-gray-100 rounded-md">
                    <label class="font-semibold">Select Pet</label>
                    <select
                        v-model="form.pet_id"
                        class="w-full border p-2 rounded mt-2"
                        required
                    >
                        <option value="" disabled>Choose a pet</option>
                        <option
                            v-for="pet in pets"
                            :key="pet.id"
                            :value="pet.id"
                        >
                            {{ pet.name }} - {{ pet.breed }}
                        </option>
                    </select>
                </div>

                <!-- Text Fields -->
                <div
                    v-for="(label, key) in fieldLabels"
                    :key="key"
                    class="relative bg-inherit"
                >
                    <input
                        :type="key === 'dob' ? 'date' : 'text'"
                        v-model="form[key]"
                        :placeholder="label"
                        :required="key !== 'middle_name'"
                        class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                    />
                    <label
                        class="absolute left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                    >
                        {{ label }}
                    </label>
                </div>

                <!-- Valid ID Uploads -->
                <div class="mb-4 relative bg-inherit">
                    <input
                        type="file"
                        @change="handleFileChange($event, 'valid_id')"
                        accept=".jpeg,.png,.jpg,.pdf"
                        class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary outline-none"
                    />
                    <label
                        class="absolute left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1"
                        >Upload Valid ID</label
                    >
                    <img
                        v-if="adoption.valid_id"
                        :src="`/storage/${adoption.valid_id}`"
                        class="mt-2 w-32 h-32 object-cover rounded-md cursor-pointer"
                        @click="showImage(adoption.valid_id)"
                    />
                </div>

                <div class="mb-4 relative bg-inherit">
                    <input
                        type="file"
                        @change="handleFileChange($event, 'valid_id_back')"
                        accept=".jpeg,.png,.jpg,.pdf"
                        class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary outline-none"
                    />
                    <label
                        class="absolute left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1"
                        >Upload Valid ID Back</label
                    >
                    <img
                        v-if="adoption.valid_id_back"
                        :src="`/storage/${adoption.valid_id_back}`"
                        class="mt-2 w-32 h-32 object-cover rounded-md cursor-pointer"
                        @click="showImage(adoption.valid_id_back)"
                    />
                </div>

                <!-- Radio Questions -->
                <fieldset
                    v-for="(question, key) in questions"
                    :key="key"
                    class="mb-4"
                >
                    <legend class="font-semibold">{{ question }}</legend>
                    <label
                        ><input
                            type="radio"
                            :name="key"
                            value="yes"
                            v-model="form[key]"
                            required
                        />
                        Yes</label
                    >
                    <label class="ml-4"
                        ><input
                            type="radio"
                            :name="key"
                            value="no"
                            v-model="form[key]"
                            required
                        />
                        No</label
                    >
                </fieldset>

                <!-- Buttons -->
                <div
                    class="flex flex-col space-y-4 md:flex-row md:space-x-4 md:space-y-0"
                >
                    <button
                        type="submit"
                        class="bg-primary text-white font-bold py-2 px-4 rounded-lg hover:bg-white hover:text-primary border hover:border-primary transition hover:scale-105 cursor-pointer"
                    >
                        Save changes
                    </button>
                    <button
                        type="button"
                        @click="goBack"
                        class="bg-white text-dark font-bold py-2 px-4 rounded-lg hover:bg-white hover:text-primary border hover:border-primary transition hover:scale-105 cursor-pointer"
                    >
                        Back
                    </button>
                </div>
            </form>
        </div>

        <!-- Image Modal -->
        <div
            v-if="modalImage"
            @click.self="closeImageModal"
            class="modal fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
        >
            <div class="relative">
                <img
                    :src="`/storage/${modalImage}`"
                    class="w-auto h-auto max-w-full max-h-80 rounded-lg hover:scale-105 transition-all duration-300 ease-in-out cursor-pointer"
                />
                <button
                    @click="closeImageModal"
                    class="absolute -top-9 -right-6 text-gray-500 text-4xl hover:text-red-500 transition cursor-pointer"
                >
                    &times;
                </button>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'
import AppLayout from '../../Layouts/AppLayout.vue'

const props = defineProps({
  adoption: Object,
  pets: Array,
})

const fieldLabels = {
  last_name: 'Last Name',
  first_name: 'First Name',
  middle_name: 'Middle Name',
  address: 'Address',
  contact_number: 'Contact Number',
  dob: 'Date of Birth',
}

const questions = {
  previous_experience: 'Do you have previous pet ownership experience?',
  other_pets: 'Do you have other pets at home?',
  financial_preparedness: 'Are you financially prepared for pet care?',
}

const form = reactive({ ...props.adoption })
const files = reactive({ valid_id: null, valid_id_back: null })
const modalImage = ref(null)

function handleFileChange(event, field) {
  files[field] = event.target.files[0]
}

function showImage(path) {
  modalImage.value = path
}

function closeImageModal() {
  modalImage.value = null
}

function goBack() {
  router.visit('/adopt/log')
}

async function submitForm() {
  const data = new FormData()
  
  // Append all non-file form fields
  Object.keys(form).forEach(key => {
    if (key !== 'valid_id' && key !== 'valid_id_back') {
      data.append(key, form[key] ?? '')
    }
  })

  // Append actual file objects
  if (files.valid_id) data.append('valid_id', files.valid_id)
  if (files.valid_id_back) data.append('valid_id_back', files.valid_id_back)

  try {
    await axios.post(`/adopt/${props.adoption.id}`, data, {
      headers: { 
        'Content-Type': 'multipart/form-data',
        'X-HTTP-Method-Override': 'PUT',
      },
    })
    router.visit('/adopt/log')
  } catch (error) {
    console.error('Form submission failed:', error.response?.data ?? error)
  }
}
</script>

<style scoped>
  .modal {
    background-color: rgba(0, 0, 0, 0.4);
  }
</style>