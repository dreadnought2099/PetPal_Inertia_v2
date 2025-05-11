<template>
    <AppLayout>
        <div class="min-h-screen w-full flex justify-center items-center px-4 py-6">
            <form @submit.prevent="submitForm" class="bg-white w-full max-w-xs p-4 sm:p-6 rounded-lg shadow-lg border border-primary space-y-4">
                <h2 class="text-xl sm:text-2xl font-bold text-dark text-center">
                    Register <span class="text-primary">!</span>
                </h2>

                <!-- Flash messages -->
                <div v-if="flashMessage" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-2 rounded-md text-xs text-center">
                    {{ flashMessage }}
                </div>
                <div v-if="flashError" class="bg-red-100 border-l-4 border-secondary text-secondary p-2 rounded-md text-xs text-center">
                    {{ flashError }}
                </div>

                <div class="relative">
                    <input
                        type="text"
                        v-model="form.name"
                        id="name"
                        name="name"
                        placeholder="Name"
                        required
                        class="peer py-2 sm:py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none text-sm sm:text-base"
                    />
                    <label
                        class="absolute cursor-text left-0 -top-3 text-xs sm:text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                    >
                        Name
                    </label>
                </div>

                <div class="relative">
                    <input
                        type="email"
                        v-model="form.email"
                        id="email"
                        name="email"
                        placeholder="Email"
                        required
                        class="peer py-2 sm:py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none text-sm sm:text-base"
                    />
                    <label
                        class="absolute cursor-text left-0 -top-3 text-xs sm:text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                    >
                        Email
                    </label>
                </div>

                <div class="relative">
                    <input
                        type="password"
                        v-model="form.password"
                        id="password"
                        name="password"
                        placeholder="Password"
                        required
                        class="peer py-2 sm:py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none text-sm sm:text-base"
                    />
                    <label
                        class="absolute cursor-text left-0 -top-3 text-xs sm:text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                    >
                        Password
                    </label>
                </div>

                <div class="relative">
                    <input
                        type="password"
                        v-model="form.password_confirmation"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirm Password"
                        required
                        class="peer py-2 sm:py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none text-sm sm:text-base"
                    />
                    <label
                        class="absolute cursor-text left-0 -top-3 text-xs sm:text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                    >
                        Confirm Password
                    </label>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-primary text-white font-medium py-2 rounded-lg transition hover:bg-white hover:text-primary border hover:border-primary cursor-pointer text-sm sm:text-base"
                >
                    Register
                </button>

                <div class="text-center text-xs sm:text-sm pt-1">
                    <span class="text-gray-500">Already have an account?</span>
                    <Link
                        href="/login"
                        class="text-dark hover:text-primary hover-underline-hyperlink ml-1"
                    >
                        Login
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
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const flashMessage = usePage().props.flash?.success;
const flashError = usePage().props.flash?.error;

function submitForm() {
    form.post('/register', {
        onFinish: () => {
            form.password = '';
            form.password_confirmation = '';
        }
    });
}
</script>