<template>
  <AppLayout>
    <div class="min-h-screen w-full flex justify-center items-center px-4 py-6">
      <form
        @submit.prevent="submit"
        class="bg-white w-[90%] max-w-xs p-4 rounded-lg shadow-lg border border-primary space-y-4"
      >
        <h2 class="text-xl font-bold text-dark text-center">
          Log <span class="text-primary">in</span>
        </h2>

        <!-- Flash Messages -->
        <div v-if="flash.error" class="bg-red-100 border-l-4 border-secondary text-secondary p-2 rounded-md text-xs text-center">
          {{ flash.error }}
        </div>
        <div v-else-if="flash.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-2 rounded-md text-xs text-center">
          {{ flash.success }}
        </div>

        <div class="relative">
          <input
            type="email"
            v-model="form.email"
            id="email"
            name="email"
            placeholder="Email"
            required
            :class="inputClasses"
            autocomplete="username"
          />
          <label for="email" :class="labelClasses">Email</label>
          <!-- <p v-if="form.errors.email" class="text-xs text-red-500 mt-1">{{ form.errors.email }}</p> -->
        </div>

        <div class="relative">
          <input
            type="password"
            v-model="form.password"
            id="password"
            name="password"
            placeholder="Password"
            required
            :class="inputClasses"
            autocomplete="current-password"
          />
          <label for="password" :class="labelClasses">Password</label>
          <!-- <p v-if="form.errors.password" class="text-xs text-red-500 mt-1">{{ form.errors.password }}</p> -->
        </div>

        <div class="text-right">
          <Link
            href="/forgot-password"
            class="text-xs text-dark hover:text-primary hover-underline-hyperlink"
          >
            Forgot your password?
          </Link>
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-primary text-white font-medium py-2 rounded-lg transition hover:bg-white hover:text-primary border hover:border-primary cursor-pointer"
        >
          Log in
        </button>

        <div class="text-center text-xs pt-1">
          <span class="text-gray-500">Don't have an account?</span>
          <Link
            href="/register"
            class="text-dark hover:text-primary hover-underline-hyperlink ml-1"
          >
            Register
          </Link>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
import { useForm, usePage, Link } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
});

const flash = usePage().props.flash || {};

const inputClasses =
  "peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none";
const labelClasses =
  "absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md";

function submit() {
  form.post('/login', {
    onFinish: () => {
      form.password = '';
    }
  });
}
</script>


