<template>
    <AppLayout>
        <div class="container mx-auto p-6">
            <div
                class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg border border-primary"
            >
                <h2 class="text-2xl font-bold text-center mb-4">
                    Profil<span class="text-primary">e</span>
                </h2>
                <div class="space-y-3">
                    <p><strong>Name: </strong>{{ user.name }}</p>
                    <p><strong>Email: </strong>{{ user.email }}</p>
                    <p><strong>Joined: </strong>{{ formattedDate }}</p>
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
