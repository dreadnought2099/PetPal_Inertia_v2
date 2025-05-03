<template>
    <nav
        class="bg-primary flex justify-between items-center px-8 py-4 text-white z-10"
    >
        <div>
            <Link
                href="/"
                class="flex flex-col text-3xl text-white hover-underline hover:scale-115 transition-all duration-300 ease-in-out"
            >
                PetPal <span class="text-sm">Find.Love.Adopt</span>
            </Link>
        </div>

        <div class="flex items-center space-x-6 text-lg font-bold z-10">
            <Link href="/" class="hover-underline" title="Go Home">Home</Link>
            <Link
                href="/pets"
                class="hover-underline"
                title="Available pet listings"
                >Our Pets</Link
            >

            <!-- Shelter/Admin Links -->
            <template v-if="isShelterOrAdmin">
                <Link
                    href="/pets/create"
                    class="hover-underline"
                    title="Add pet listing"
                    >Add Pet</Link
                >
                <Link
                    href="/adopt/pending"
                    class="hover-underline"
                    title="View available pending requests"
                    >Pending Requests</Link
                >
            </template>

            <!-- Adopter Link -->
            <template v-if="isAdopter">
                <Link href="/adopt/apply" class="hover-underline"
                    >Apply for Adoption</Link
                >
            </template>

            <!-- Authenticated Dropdown -->
            <template v-if="user">
                <div class="relative z-20" @mouseleave="dropdownOpen = false">
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

                    <!-- Overlay for click-away -->
                    <div
                        v-if="dropdownOpen"
                        class="fixed inset-0 z-10"
                        @click="dropdownOpen = false"
                    ></div>

                    <!-- Dropdown menu -->
                    <div
                        v-if="dropdownOpen"
                        class="absolute right-0 mt-2 w-44 bg-white border border-gray-300 shadow-lg rounded-lg z-20"
                        @click.stop
                    >
                        <ul class="py-2">
                            <li>
                                <Link
                                    href="/profile"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                    title="See Profile"
                                    >Profile</Link
                                >
                            </li>
                            <li v-if="isAdopter">
                                <Link
                                    href="/adopt/log"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                    title="See Adoption History"
                                    >Adoption Log</Link
                                >
                            </li>
                            <li v-if="isAdmin">
                                <Link
                                    href="/admin/users"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                    title="Manage Users"
                                    >Dashboard</Link
                                >
                            </li>
                            <li>
                                <Link
                                    href="/settings"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                    title="Go to Settings"
                                    >Settings</Link
                                >
                            </li>
                            <li>
                                <form @submit.prevent="logout">
                                    <button
                                        type="submit"
                                        class="w-full text-left px-4 py-2 text-primary hover:bg-gray-100 cursor-pointer"
                                        title="Click to Logout"
                                    >
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </template>

            <!-- Guest Links -->
            <template v-else>
                <Link href="/login" class="hover-underline">Login</Link>
                <Link href="/register" class="hover-underline">Register</Link>
            </template>
        </div>
    </nav>
</template>

<script setup>
import { ref, computed } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";

// Always define user first!
const { user } = usePage().props;
const dropdownOpen = ref(false);

// Toggle the dropdown menu
const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

// Defensive role checks
const getRoles = () => (user && Array.isArray(user.roles) ? user.roles : []);

const isShelterOrAdmin = computed(
    () => getRoles().includes("Shelter") || getRoles().includes("Administrator")
);
const isAdopter = computed(() => getRoles().includes("Adopter"));
const isAdmin = computed(() => getRoles().includes("Administrator"));

// Logout function
const logout = () => {
    router.post("/logout");
    dropdownOpen.value = false;
};
</script>
