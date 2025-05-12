<template>
    <Head title="Adoption Log" />

    <AppLayout>
        <div class="container mx-auto max-w-5xl bg-white mt-2 sm:mt-6 border border-primary rounded-lg shadow-md overflow-hidden">
            <!-- Sticky Header -->
            <div class="sticky top-0 z-9 bg-white border-b border-gray-100 pb-2 sm:pb-3 px-2 sm:px-6">
                <h2 class="flex items-center justify-center text-lg sm:text-xl font-bold py-2 sm:py-3">
                    Adoption <span class="text-primary">Log</span>
                </h2>

                <!-- Dropdown Filter -->
                <div class="flex flex-col sm:flex-row sm:justify-start items-center gap-2 sm:gap-4">
                    <label for="statusFilter" class="text-sm sm:text-lg font-semibold">Filter by Status:</label>
                    <select v-model="selectedFilter" id="statusFilter" class="px-2 py-1 sm:py-2 border rounded text-[10px] sm:text-sm">
                        <option value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="overflow-y-auto h-[75vh] p-2 sm:p-6">
                <div v-if="filteredAdoptions.length === 0" class="text-center mt-4">
                    <p class="text-gray-400 text-sm sm:text-xl">No adoption requests found for the selected status.</p>
                </div>

                <div v-else class="overflow-x-auto mt-4">
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-2 sm:p-3 text-left text-[10px] sm:text-sm font-semibold">ID</th>
                                <th class="border p-2 sm:p-3 text-left text-[10px] sm:text-sm font-semibold">Adopter</th>
                                <th class="border p-2 sm:p-3 text-left text-[10px] sm:text-sm font-semibold">Pet</th>
                                <th class="border p-2 sm:p-3 text-left text-[10px] sm:text-sm font-semibold">Status</th>
                                <th class="border p-2 sm:p-3 text-center text-[10px] sm:text-sm font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="adoption in filteredAdoptions" :key="adoption.id" class="hover:bg-gray-50 transition">
                                <td class="border p-2 sm:p-3 text-[10px] sm:text-sm">{{ adoption.id }}</td>
                                <td class="border p-2 sm:p-3 text-[10px] sm:text-sm">{{ adoption.first_name }} {{ adoption.last_name }}</td>
                                <td class="border p-2 sm:p-3 text-[10px] sm:text-sm">{{ adoption.pet?.name || "Unknown Pet" }}</td>
                                <td class="border p-2 sm:p-3 text-[10px] sm:text-sm" :class="statusColor(adoption.status)">
                                    {{ capitalize(adoption.status) }}
                                </td>
                                <td class="border p-2 sm:p-3 text-center">
                                    <template v-if="canManageAdoption(adoption)">
                                        <div class="flex flex-col sm:flex-row justify-center items-center gap-1 sm:gap-4">
                                            <Link
                                                :href="`/adopt/${adoption.id}/edit`"
                                                class="bg-primary text-white py-1 sm:py-2 px-2 sm:px-4 rounded-md hover:bg-white hover:text-primary border border-primary transition text-[10px] sm:text-sm"
                                            >
                                                Edit
                                            </Link>
                                            <form @submit.prevent="deleteAdoption(adoption.id, adoption.status)" class="inline">
                                                <button
                                                    type="submit"
                                                    class="relative w-16 sm:w-24 bg-red-500 text-white py-1 sm:py-2 px-2 sm:px-4 rounded-md hover:bg-white hover:text-red-500 border border-red-500 transition cursor-pointer text-[10px] sm:text-sm"
                                                    :disabled="deleting[adoption.id]"
                                                >
                                                    <span :class="deleting[adoption.id] ? 'opacity-100' : 'opacity-0'" class="absolute inset-0 flex items-center justify-center">
                                                        Deleting...
                                                    </span>
                                                    <span :class="!deleting[adoption.id] ? 'opacity-100' : 'opacity-0'" class="flex items-center justify-center">
                                                        Delete
                                                    </span>
                                                </button>
                                            </form>
                                        </div>
                                    </template>
                                    <span v-else class="text-gray-500 text-[10px] sm:text-sm">No actions available</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, Link, usePage, useForm } from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";

const page = usePage();
const adoptions = page.props.adoptions || [];
const authUser = page.props.auth?.user || null;
const form = useForm({});
const selectedFilter = ref("all"); // Default filter set to all

// Add state to track deletion process for each adoption
const deleting = ref({});

// Computed property to filter adoptions based on selected status
const filteredAdoptions = computed(() => {
    if (selectedFilter.value === "all") return adoptions;
    return adoptions.filter(
        (adoption) => adoption.status.toLowerCase() === selectedFilter.value
    );
});

// Function to capitalize status names
function capitalize(str) {
    return str ? str.charAt(0).toUpperCase() + str.slice(1) : "";
}

// Function to determine the color class for status
function statusColor(status) {
    const lowerStatus = status.toLowerCase();
    switch (lowerStatus) {
        case "pending":
            return "text-yellow-400";
        case "approved":
            return "text-green-500";
        case "rejected":
            return "text-red-500";
        case "cancelled":
            return "text-gray-500";
        case "archived":
            return "text-gray-400";
        default:
            return "";
    }
}

// Check if user can manage adoption (edit/delete)
function canManageAdoption(adoption) {
    if (!authUser) return false;

    const isOwner = authUser.id === adoption.user_id;
    const isAdminOrShelter = authUser.roles?.some(
        (role) => role.name === "Administrator" || role.name === "Shelter"
    );

    // If user is admin or shelter, they can manage any request
    if (isAdminOrShelter) return true;

    // If user is owner, they can only manage if status is pending
    if (isOwner) {
        return adoption.status.toLowerCase() === "pending";
    }

    return false;
}

// Prevent deleting approved adoptions
function deleteAdoption(id, status) {
    if (status.toLowerCase() === "approved") {
        alert("You cannot delete approved adoption requests.");
        return;
    }

    if (confirm("Are you sure you want to delete this adoption request?")) {
        deleting.value[id] = true; // Mark this adoption as being deleted

        form.delete(`/adopt/${id}`, {
            preserveScroll: false,
            onSuccess: () => {
                deleting.value[id] = false; // Reset deletion state
                window.location.reload(); // Reload after success
            },
            onError: () => {
                deleting.value[id] = false; // Reset deletion state if error occurs
            },
        });
    }
}
</script>
