<template>
    <AppLayout>
    <div class="container mx-auto max-w-5xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh]">
      <h1 class="flex gap-[5px] sticky top-0 py-2 px-4 text-2xl font-bold bg-white z-10 justify-center">
        Apply for <span class="text-primary">Adoption</span>
      </h1>
      <PetCard v-if="selectedPet" :pet="selectedPet" class="mb-4" />
  
      <form @submit.prevent="submit" enctype="multipart/form-data" class="rounded-lg px-8 pt-6 pb-8 mb-4 space-y-6 z-9">
        <!-- ...form fields as in previous answer... -->
      </form>
    </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import dayjs from 'dayjs';
  import AppLayout from '../../Layouts/AppLayout.vue';
  import PetCard from '@/Components/PetCard.vue';

  const page = usePage();
  const selectedPet = ref(page.props.selectedPet || null);
  
  // ...rest of the form setup as in previous answer...
  </script>