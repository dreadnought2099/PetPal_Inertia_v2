<template>
  <AppLayout>
    <div class="container mx-auto max-w-5xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh] px-4 sm:px-8">
      <h2 class="flex gap-1 sticky top-0 py-2 px-4 text-xl sm:text-2xl font-bold bg-white z-10 justify-center">
        Edit Pet <span class="text-primary">Listing</span>
      </h2>

      <!-- <pre>{{ form }}</pre> -->

      <form @submit.prevent="submit" enctype="multipart/form-data" class="pt-6 pb-8 space-y-4 sm:space-y-6">
        <!-- Profile Image -->
        <div class="mb-4">
          <label for="profile" class="block text-[10px] sm:text-xs md:text-sm font-medium text-gray-700">Profile Image</label>
          <input
            type="file"
            id="profile"
            name="profile"
            @change="e => form.profile = e.target.files[0]"
            class="mt-1 p-1.5 sm:p-2 border border-gray-300 rounded-md w-full text-[10px] sm:text-xs md:text-sm"
          />
          <div v-if="pet.pet_profile_path">
            <img
              :src="`/storage/${pet.pet_profile_path}`"
              alt="Current Profile Image"
              class="mt-1 sm:mt-2 w-24 sm:w-32 md:w-40 h-auto object-cover rounded-md"
            />
          </div>
          <p v-if="form.errors.profile" class="text-red-500 text-[10px] sm:text-xs mt-0.5 sm:mt-1">{{ form.errors.profile }}</p>
        </div>

        <!-- Pet Name -->
        <div class="mb-2 sm:mb-4">
          <label for="name" class="block text-[10px] sm:text-xs md:text-sm font-medium text-gray-700">Pet Name</label>
          <input
            type="text"
            id="name"
            name="name"
            v-model="form.name"
            class="mt-1 p-1.5 sm:p-2 border border-gray-300 rounded-md w-full text-[10px] sm:text-xs md:text-sm"
          />
          <p v-if="form.errors.name" class="text-red-500 text-[10px] sm:text-xs mt-0.5 sm:mt-1">{{ form.errors.name }}</p>
        </div>

        <!-- Age -->
        <div class="mb-4">
          <label for="age" class="block text-xs sm:text-sm font-medium text-gray-700">Age (in years)</label>
          <input
            type="number"
            id="age"
            name="age"
            v-model="form.age"
            class="mt-1 p-2 border border-gray-300 rounded-md w-full text-sm sm:text-base"
          />
          <p v-if="form.errors.age" class="text-red-500 text-xs mt-1">{{ form.errors.age }}</p>
        </div>

        <!-- Breed -->
        <div class="mb-4">
          <label for="breed" class="block text-xs sm:text-sm font-medium text-gray-700">Breed</label>
          <input
            type="text"
            id="breed"
            name="breed"
            v-model="form.breed"
            class="mt-1 p-2 border border-gray-300 rounded-md w-full text-sm sm:text-base"
          />
          <p v-if="form.errors.breed" class="text-red-500 text-xs mt-1">{{ form.errors.breed }}</p>
        </div>

        <!-- Sex -->
        <div class="mb-4">
          <label for="sex" class="block text-xs sm:text-sm font-medium text-gray-700">Sex</label>
          <select
            name="sex"
            id="sex"
            v-model="form.sex"
            class="mt-1 p-2 border border-gray-300 rounded-md w-full text-sm sm:text-base"
          >
            <option value="M">Male</option>
            <option value="F">Female</option>
          </select>
          <p v-if="form.errors.sex" class="text-red-500 text-xs mt-1">{{ form.errors.sex }}</p>
        </div>

        <!-- Species -->
        <div class="mb-4">
          <label for="species" class="block text-xs sm:text-sm font-medium text-gray-700">Species</label>
          <select
            name="species"
            id="species"
            v-model="form.species"
            class="mt-1 p-2 border border-gray-300 rounded-md w-full text-sm sm:text-base"
          >
            <option value="0">Dog</option>
            <option value="1">Cat</option>
          </select>
          <p v-if="form.errors.species" class="text-red-500 text-xs mt-1">{{ form.errors.species }}</p>
        </div>

        <!-- Vaccination -->
        <div class="mb-4">
          <label for="vaccination" class="block text-xs sm:text-sm font-medium text-gray-700">Vaccination Status</label>
          <select
            name="vaccination"
            id="vaccination"
            v-model="form.vaccination"
            class="mt-1 p-2 border border-gray-300 rounded-md w-full text-sm sm:text-base"
          >
            <option value="0">None</option>
            <option value="1">Partially</option>
            <option value="2">Fully</option>
          </select>
          <p v-if="form.errors.vaccination" class="text-red-500 text-xs mt-1">{{ form.errors.vaccination }}</p>
        </div>

        <!-- Spayed/Neutered -->
        <div class="mb-4">
          <label for="spayed_neutered" class="block text-xs sm:text-sm font-medium text-gray-700">Spayed/Neutered</label>
          <div class="flex items-center mt-1">
            <input
              type="checkbox"
              id="spayed_neutered"
              name="spayed_neutered"
              v-model="form.spayed_neutered"
              true-value="1"
              false-value="0"
              class="p-2 border border-gray-300 rounded-md text-sm sm:text-base"
            />
            <span class="ml-2 text-sm sm:text-base">{{ form.spayed_neutered == 1 ? 'Yes' : 'No' }}</span>
          </div>
          <p v-if="form.errors.spayed_neutered" class="text-red-500 text-xs mt-1">{{ form.errors.spayed_neutered }}</p>
        </div>

        <!-- Allergies -->
        <div class="mb-4">
          <label for="allergies" class="block text-xs sm:text-sm font-medium text-gray-700">Allergies</label>
          <input
            type="text"
            id="allergies"
            name="allergies"
            v-model="form.allergies"
            class="mt-1 p-2 border border-gray-300 rounded-md w-full text-sm sm:text-base"
          />
          <p v-if="form.errors.allergies" class="text-red-500 text-xs mt-1">{{ form.errors.allergies }}</p>
        </div>

        <!-- Description -->
        <div class="mb-4">
          <label for="description" class="block text-xs sm:text-sm font-medium text-gray-700">Description</label>
          <textarea
            id="description"
            name="description"
            v-model="form.description"
            class="mt-1 p-2 border border-gray-300 rounded-md w-full text-sm sm:text-base"
            rows="4"
          ></textarea>
          <p v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</p>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
          <button
            type="submit"
            :disabled="form.processing"
            class="border border-primary bg-primary text-white font-bold py-2 px-4 rounded-lg transition-all hover:scale-105 hover:bg-white hover:text-primary hover:border-primary duration-300 cursor-pointer text-sm sm:text-base"
          >
            Update
          </button>
          <button
            type="button"
            @click="goBack"
            class="border border-dark hover:border-primary bg-white text-dark font-bold py-2 px-4 rounded-lg transition-all hover:scale-105 hover:text-primary duration-300 text-sm sm:text-base"
          >
            Cancel
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

const pet = usePage().props.pet;

const form = useForm({
  profile: null,
  name: pet.name,
  age: pet.age,
  breed: pet.breed,
  sex: pet.sex,
  species: pet.species !== undefined ? String(pet.species) : '0',
  vaccination: pet.vaccination !== undefined ? String(pet.vaccination) : '0',
  spayed_neutered: pet.spayed_neutered ? 1 : 0,
  allergies: pet.allergies,
  description: pet.description,
});

function submit() {
  form.species = Number(form.species);
  form.vaccination = Number(form.vaccination);
  form.spayed_neutered = Number(form.spayed_neutered);

  form.post(`/pets/${pet.id}`, {
    method: 'put',
    forceFormData: true,
    onSuccess: () => form.reset('profile'),
  });
}

function goBack() {
  window.history.back();
}
</script>
