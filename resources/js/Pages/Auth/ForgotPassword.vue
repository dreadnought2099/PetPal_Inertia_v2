<template>
    <div class="w-screen h-screen flex flex-col justify-start items-center bg-gray-200 pt-32">
      <div v-if="statusMessage" class="absolute top-24 right-4 z-0">
        <div id="message" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-md w-full text-center">
          {{ statusMessage }}
        </div>
      </div>
  
      <form @submit.prevent="sendResetLink" class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm border border-primary space-y-6">
        <h2 class="text-2xl font-bold text-dark text-center mb-6">
          Forgot <span class="text-primary">Password</span>
        </h2>
  
        <p class="text-sm text-gray-600 text-center">
          Enter your registered email, and we'll send you a reset link.
        </p>
  
        <div class="relative bg-inherit">
          <input v-model="form.email" type="email" id="email" name="email" placeholder="email" required
            class="peer bg-transparent py-3 w-full rounded-md text-gray-700 placeholder-transparent ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
          <label for="email"
            class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-inherit mx-1 px-1 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm transition-all">
            Email
          </label>
        </div>
  
        <button type="submit" class="w-full bg-primary hover:bg-white hover:text-primary border border-primary text-white font-medium py-3 rounded-lg transition duration-300 cursor-pointer">
          Send Reset Link
        </button>
  
        <div class="text-center">
          <span class="text-primary">or</span>
          <Link href="/login" class="text-dark hover:text-primary hover-underline-hyperlink">Back to Login</Link>
        </div>
  
        <p v-if="form.errors.email" class="text-red-500">{{ form.errors.email }}</p>
      </form>
    </div>
  </template>
  
  <script>
import { useForm, Link } from '@inertiajs/vue3';

export default {
  components: { Link },
  setup() {
    const form = useForm({ email: '' });
    const statusMessage = ref(null);

    const sendResetLink = () => {
      form.post('/password/email', {
        onSuccess: () => {
          statusMessage.value = 'Reset link sent!';
          setTimeout(() => { statusMessage.value = null; }, 4000);
        },
        onError: () => {
          statusMessage.value = form.errors.email || 'Error sending reset link.';
        }
      });
    };

    return { form, sendResetLink, statusMessage };
  }
};
  </script>
  