<template>
    <AppLayout>
        <div class="min-h-screen w-full flex justify-center items-center px-4 py-6">
            <form @submit.prevent="submitForm" class="bg-white w-[90%] max-w-xs p-4 rounded-lg shadow-lg border border-primary space-y-4">
                <h2 class="text-xl font-bold text-dark text-center">
                    Register <span class="text-primary">!</span>
                </h2>

                <!-- Flash messages -->
                <div v-if="flashMessage" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-2 rounded-md text-xs text-center">
                    {{ flashMessage }}
                </div>
                <div v-if="flashError" class="bg-red-100 border-l-4 border-secondary text-secondary p-2 rounded-md text-xs text-center">
                    {{ flashError }}
                </div>

                <!-- Form fields -->
                <div v-for="{ name, label, type } in fields" :key="name" class="relative">
                    <input
                        v-model="form[name]"
                        :type="type"
                        :id="name"
                        :placeholder="label"
                        required
                        class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                    />
                    <label
                        :for="name"
                        class="absolute cursor-text left-0 -top-2 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                    >
                        {{ label }}
                    </label>
                    <p class="text-secondary text-xs min-h-[14px] mt-1">
                        {{ form.errors[name] || '' }}
                    </p>
                </div>

                <div class="space-y-3">
                    <button
                        type="submit"
                        class="w-full bg-primary text-white font-medium py-2 rounded-lg transition hover:bg-white hover:text-primary border hover:border-primary cursor-pointer"
                    >
                        Register
                    </button>

                    <div class="text-center text-xs pt-1">
                        <span class="text-gray-500">Already have an account?</span>
                        <Link href="/login" class="text-dark hover:text-primary hover-underline-hyperlink ml-1">
                            Log in
                        </Link>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script>
import { ref } from 'vue';
import { useForm, usePage, Link } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    components: {
        AppLayout,
        Link,
    },
    setup() {
        // Define form fields
        const fields = [
            { name: 'name', label: 'Enter name', type: 'text' },
            { name: 'email', label: 'Enter email', type: 'email' },
            { name: 'password', label: 'Enter password', type: 'password' },
            { name: 'password_confirmation', label: 'Confirm password', type: 'password' },
        ];

        // Initialize form with useForm
        const form = useForm({
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        });

        const page = usePage();
        const flashMessage = ref(page.props.flash?.success || null);
        const flashError = ref(page.props.flash?.error || null);

        const submitForm = () => {
            form.post('/register', {
                onError: (errors) => {
                    flashError.value = Object.values(errors)[0] || 'Registration failed. Please check your inputs.';
                },
                onSuccess: () => {
                    flashMessage.value = 'Registration successful!';
                    Inertia.get('/pets');
                },
            });
        };

        return {
            form,
            fields,
            submitForm,
            flashMessage,
            flashError,
        };
    },
};
</script>