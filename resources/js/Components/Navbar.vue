<template>
    <nav
        class="bg-primary flex justify-between items-center px-2 sm:px-4 md:px-8 py-2 sm:py-4 text-white z-50 fixed w-full top-0"
    >
        <div>
            <Link
                href="/"
                title="PetPal"
                class="flex flex-col text-lg sm:text-xl md:text-2xl text-white hover-underline hover:scale-105 transition-all duration-300 ease-in-out"
            >
                PetPal <span class="text-[8px] sm:text-xs md:text-sm">Find.Love.Adopt</span>
            </Link>
        </div>

        <!-- Mobile Menu Button -->
        <button 
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="md:hidden p-1.5 rounded-lg hover:bg-white/10 flex items-center gap-2"
            :class="{ 'bg-white/10': mobileMenuOpen }"
        >
            <span class="text-sm">{{ user ? user.name : 'Menu' }}</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-4 sm:space-x-6 text-sm sm:text-base font-bold z-10">
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
                <Link href="/login" class="hover-underline" title="Login">Login</Link>
                <Link href="/register" class="hover-underline" title="Register">Register</Link>
            </template>
        </div>

        <!-- Mobile Menu -->
        <div 
            v-show="mobileMenuOpen" 
            class="md:hidden fixed inset-x-0 top-[48px] sm:top-[64px] bg-white shadow-lg z-40 max-h-[calc(100vh-48px)] sm:max-h-[calc(100vh-64px)] overflow-y-auto"
        >
            <!-- Main Navigation -->
            <div class="flex flex-col divide-y divide-gray-100">
                <div class="p-3 space-y-1">
                    <Link 
                        @click="mobileMenuOpen = false"
                        href="/" 
                        class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-50 text-sm" 
                        title="Go Home"
                    >
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Home
                    </Link>
                    <Link 
                        @click="mobileMenuOpen = false"
                        href="/pets" 
                        class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-50 text-sm" 
                        title="Available pet listings"
                    >
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        Our Pets
                    </Link>

                    <!-- Shelter/Admin Links -->
                    <template v-if="isShelterOrAdmin">
                        <Link 
                            @click="mobileMenuOpen = false"
                            href="/pets/create" 
                            class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-50 text-sm" 
                            title="Add pet listing"
                        >
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Pet
                        </Link>
                        <Link 
                            @click="mobileMenuOpen = false"
                            href="/adopt/pending" 
                            class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-50 text-sm" 
                            title="View available pending requests"
                        >
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Pending Requests
                        </Link>
                    </template>

                    <!-- Adopter Link -->
                    <template v-if="isAdopter">
                        <Link 
                            @click="mobileMenuOpen = false"
                            href="/adopt/apply" 
                            class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-50 text-sm"
                        >
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Apply for Adoption
                        </Link>
                    </template>
                </div>

                <!-- User Section -->
                <template v-if="user">
                    <div class="p-3 space-y-1">
                        <div class="px-3 py-2 text-sm text-gray-500">Account</div>
                        <Link 
                            @click="mobileMenuOpen = false"
                            href="/profile" 
                            class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-50 text-sm" 
                            title="See Profile"
                        >
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile
                        </Link>
                        <Link 
                            v-if="isAdopter" 
                            @click="mobileMenuOpen = false"
                            href="/adopt/log" 
                            class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-50 text-sm" 
                            title="See Adoption History"
                        >
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Adoption Log
                        </Link>
                        <Link 
                            v-if="isAdmin" 
                            @click="mobileMenuOpen = false"
                            href="/admin/users" 
                            class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-50 text-sm" 
                            title="Manage Users"
                        >
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Dashboard
                        </Link>
                        <Link 
                            @click="mobileMenuOpen = false"
                            href="/settings" 
                            class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-50 text-sm" 
                            title="Go to Settings"
                        >
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Settings
                        </Link>
                        <form @submit.prevent="logout" class="mt-2">
                            <button 
                                type="submit" 
                                class="w-full flex items-center gap-2 px-3 py-2 text-red-600 rounded-lg hover:bg-red-50 cursor-pointer text-sm" 
                                title="Click to Logout"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </template>

                <!-- Guest Links -->
                <template v-else>
                    <div class="p-3 space-y-1">
                        <Link 
                            @click="mobileMenuOpen = false"
                            href="/login" 
                            class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-50 text-sm" 
                            title="Login"
                        >
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Login
                        </Link>
                        <Link 
                            @click="mobileMenuOpen = false"
                            href="/register" 
                            class="flex items-center gap-2 px-3 py-2 text-gray-700 rounded-lg hover:bg-gray-50 text-sm" 
                            title="Register"
                        >
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Register
                        </Link>
                    </div>
                </template>
            </div>
        </div>
    </nav>
    <!-- Add spacer to prevent content from being hidden under fixed navbar -->
    <div class="h-[48px] sm:h-[64px]"></div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";

// Always define user first!
const { user } = usePage().props;
const dropdownOpen = ref(false);
const mobileMenuOpen = ref(false);

// Toggle the dropdown menu
const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

// Close menus on route change
const handleRouteChange = () => {
    dropdownOpen.value = false;
    mobileMenuOpen.value = false;
};

onMounted(() => {
    window.addEventListener('popstate', handleRouteChange);
});

onUnmounted(() => {
    window.removeEventListener('popstate', handleRouteChange);
});

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
    mobileMenuOpen.value = false;
};
</script>
