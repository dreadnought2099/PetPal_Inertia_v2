<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 px-4">
      <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-700 text-center mb-4">
          Reset Your Password
        </h2>
  
        <p class="text-sm text-gray-600 text-center mb-6">
          Enter your new password below to reset your account.
        </p>
  
        <div v-if="form.errors.token || form.errors.email" class="bg-red-100 border border-red-400 text-red-700 p-3 rounded-md mb-4">
          <div v-if="form.errors.token">{{ form.errors.token }}</div>
          <div v-if="form.errors.email">{{ form.errors.email }}</div>
        </div>
        <div v-if="status" class="bg-green-100 border border-green-400 text-green-700 p-3 rounded-md mb-4">
          {{ status }}
        </div>
  
        <form @submit.prevent="submit" class="space-y-4">
          <input type="hidden" v-model="form.token" />
          <input type="hidden" v-model="form.email" />
  
          <div class="bg-inherit relative">
            <input
              type="password"
              v-model="form.password"
              placeholder="Enter new password"
              class="peer block w-full bg-transparent border border-gray-300 rounded-md px-4 py-3 text-gray-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none placeholder-transparent"
              required
            />
            <label
              class="absolute left-3 top-3 text-gray-500 text-sm bg-white px-1 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-xs peer-focus:text-primary peer-focus:px-1 transition-all"
            >
              Enter new password
            </label>
            <p class="text-primary text-sm min-h-[20px]">
              {{ form.errors.password }}
            </p>
          </div>
  
          <div class="bg-inherit relative">
            <input
              type="password"
              v-model="form.password_confirmation"
              placeholder="Confirm new password"
              class="peer block w-full bg-transparent border border-gray-300 rounded-md px-4 py-3 text-gray-700 focus:ring-2 focus:ring-primary focus:border-primary outline-none placeholder-transparent"
              required
            />
            <label
              class="absolute left-3 top-3 text-gray-500 text-sm bg-white px-1 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:-top-2 peer-focus:text-xs peer-focus:text-primary peer-focus:px-1 transition-all"
            >
              Confirm new password
            </label>
            <p class="text-primary text-sm min-h-[20px]">
              {{ form.errors.password_confirmation }}
            </p>
          </div>
  
          <button
            type="submit"
            class="mt-2 w-full bg-primary hover:bg-white hover:text-primary border-1 hover:border-primary text-white font-medium py-2 rounded-lg transition duration-300"
          >
            Reset Password
          </button>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useForm, usePage } from '@inertiajs/vue3';
  
  // Get props from Inertia (token, email, status)
  const page = usePage();
  const props = page.props;
  
  // Initialize form
  const form = useForm({
    token: props.token || '', // Pass these as props from your controller
    email: props.email || '',
    password: '',
    password_confirmation: '',
  });
  
  const status = ref(props.status || '');
  
  function submit() {
    form.post('/reset-password', {
      onSuccess: () => {
        status.value = 'Password reset successful!';
        form.reset('password', 'password_confirmation');
      },
    });
  }
  </script>