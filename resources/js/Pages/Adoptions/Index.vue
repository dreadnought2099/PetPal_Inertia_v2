<template>
    <AppLayout>
  <div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-6">All Adoption Requests</h2>
    <div v-if="adoptions.length === 0" class="text-gray-500">No adoption requests found.</div>
    <div v-else class="grid gap-4">
      <div v-for="adoption in adoptions" :key="adoption.id" class="bg-white p-4 rounded shadow">
        <PetCard :pet="adoption.pet" />
        <div class="mt-2">
          <div><strong>Status:</strong> {{ adoption.status }}</div>
          <div><strong>Applicant:</strong> {{ adoption.first_name }} {{ adoption.last_name }}</div>
          <!-- Add more adoption details as needed -->
        </div>
      </div>
    </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import PetCard from '@/Components/PetCard.vue';
import AppLayout from '../../Layouts/AppLayout.vue';

const adoptions = usePage().props.adoptions || [];
</script>
