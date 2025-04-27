<template>
    <nav
        class="bg-primary flex justify-between items-center px-8 py-4 text-white"
    >
        <div>
            <Link
                href="/"
                class="flex flex-col text-3xl text-white hover-underline"
            >
                PetPal <span class="text-sm">Find.Love.Adopt</span>
            </Link>
        </div>

        <div class="flex items-center space-x-6 text-lg font-bold">
            <Link href="/" class="hover-underline">Home</Link>
            <Link href="/pets" class="hover-underline">Our Pets</Link>

            <!-- Role-based Links -->
            <template v-if="isShelterOrAdmin">
                <Link href="/pets/create" class="hover-underline">Add Pet</Link>
                <Link href="/adopt/pending" class="hover-underline"
                    >Pending Requests</Link
                >
            </template>

            <!-- Adopter-specific Link -->
            <template v-if="isAdopter">
                <Link href="/adopt" class="hover-underline"
                    >Apply for Adoption</Link
                >
            </template>

            <!-- User Dropdown -->
            <template v-if="user">
                <div class="relative z-20">
                    <button
                        @click="toggleDropdown"
                        class="flex items-center space-x-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 cursor-pointer"
                    >
                        <span class="text-gray-700">{{ user.name }}</span>
                        <svg
                            class="w-5 h-5 text-gray-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            ></path>
                        </svg>
                    </button>

                    <div
                        v-if="dropdownOpen"
                        @click.away="dropdownOpen = false"
                        class="absolute right-0 mt-2 w-44 bg-white border border-gray-300 shadow-lg rounded-lg z-10"
                    >
                        <ul class="py-2">
                            <li>
                                <Link
                                    href="/profile"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                    >Profile</Link
                                >
                            </li>
                            <template v-if="isAdopter">
                                <li>
                                    <Link
                                        href="/adopt/log"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                        >Adoption Log</Link
                                    >
                                </li>
                            </template>
                            <template v-if="isAdmin">
                                <li>
                                    <Link
                                        href="/admin/dashboard"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                        >Dashboard</Link
                                    >
                                </li>
                            </template>
                            <li>
                                <Link
                                    href="/settings"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                    >Settings</Link
                                >
                            </li>
                            <li>
                                <form @submit.prevent="logout">
                                    <button
                                        type="submit"
                                        class="w-full text-left px-4 py-2 text-primary hover:bg-gray-100 cursor-pointer"
                                    >
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </template>

            <!-- Login and Register Links -->
            <template v-else>
                <Link href="/login" class="hover-underline">Log in</Link>
                <Link href="/register" class="hover-underline">Register</Link>
            </template>
        </div>
    </nav>
</template>

<script setup>
import { ref, computed } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";

const dropdownOpen = ref(false);

// Get user info from Inertia's page props
const { user } = usePage().props;

// Toggle the dropdown menu
const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

// Check user roles
const isShelterOrAdmin = computed(
    () =>
        user &&
        (user.roles.includes("Shelter") || user.roles.includes("Administrator"))
);
const isAdopter = computed(() => user && user.roles.includes("Adopter"));
const isAdmin = computed(() => user && user.roles.includes("Administrator"));

// Logout function
const logout = () => {
    // Inertia form submission for logout
    router.post("/logout");
};
</script>
