<template>
  <AppLayout>
    <div class="max-w-lg mx-auto mt-10 p-6 bg-white rounded shadow-md">
      <h2 class="text-2xl font-semibold text-center mb-4">Verify Your Email Address</h2>
  
      <div v-if="resent" class="mb-4 p-4 bg-green-100 text-green-700 rounded">
        A new verification link has been sent to your email address.
      </div>
  
      <p class="text-gray-600 text-center mb-4">
        Before proceeding, please check your email and verify your account.
      </p>
  
      <form class="text-center" @submit.prevent="resendVerification">
        <button
          type="submit"
          class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-white hover:text-blue-500 border-1 border-blue-500 transition-all duration-300 ease-in-out cursor-pointer"
          :disabled="processing"
        >
          <span v-if="processing">Sending...</span>
          <span v-else>Resend Verification Email</span>
        </button>
      </form>
    </div>
  </AppLayout>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useForm, usePage } from '@inertiajs/vue3';
  import AppLayout from '../../Layouts/AppLayout.vue';
  const page = usePage();
  const resent = ref(page.props.resent || false);
  
  const form = useForm();
  
  const processing = ref(false);
  
  function resendVerification() {
    processing.value = true;
    form.post('/email/resend', {
      onSuccess: () => {
        resent.value = true;
        processing.value = false;
      },
      onError: () => {
        processing.value = false;
      }
    });
  }
  </script>