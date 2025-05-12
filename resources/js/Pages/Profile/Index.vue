<template>
    <AppLayout>
        <div class="container mx-auto min-h-[calc(100vh-200px)] flex items-center justify-center">
            <div
                class="w-full max-w-lg mx-auto bg-white p-4 sm:p-6 rounded-lg shadow-lg border border-primary"
            >
                <h2 class="text-xl sm:text-2xl font-bold text-center mb-3 sm:mb-4">
                    Profil<span class="text-primary">e</span>
                </h2>
                <div class="space-y-2 sm:space-y-3">
                    <p class="text-[10px] sm:text-sm"><strong>Name: </strong>{{ user.name }}</p>
                    <p class="text-[10px] sm:text-sm"><strong>Email: </strong>{{ user.email }}</p>
                    <p class="text-[10px] sm:text-sm"><strong>Joined: </strong>{{ formattedDate }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "../../Layouts/AppLayout.vue";
import { usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const user = usePage().props.user;

// Format the date using dayjs
const formattedDate = dayjs(user.created_at).format('MMMM DD, YYYY');
</script>
