<template>
    <Head title="Adoption Log" />

    <AppLayout>
        <div
            class="container mx-auto max-w-5xl bg-white mt-6 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh] p-6"
        >
            <div class="relative w-full">
                <h2
                    class="flex gap-[5px] sticky top-0 py-3 px-6 text-2xl font-semibold bg-white z-10 justify-center"
                >
                    Adoption <span class="text-primary">Log</span>
                </h2>

                <!-- Dropdown Filter -->
                <div class="mb-4 text-center flex justify-start">
                    <label for="statusFilter" class="mr-2 text-lg font-semibold"
                        >Filter by Status:</label
                    >
                    <select
                        v-model="selectedFilter"
                        id="statusFilter"
                        class="px-2 py-2 border rounded"
                    >
                        <option value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="declined">Declined</option>
                    </select>
                </div>

                <div
                    v-if="filteredAdoptions.length === 0"
                    class="text-center py-8"
                >
                    <p class="text-gray-500 text-lg">
                        No adoption requests found for the selected status.
                    </p>
                </div>

                <div v-else class="overflow-x-auto">
                    <table
                        class="table-auto w-full border-collapse border border-gray-300"
                    >
                        <thead>
                            <tr class="bg-gray-100">
                                <th
                                    class="border p-3 text-left text-sm font-semibold"
                                >
                                    ID
                                </th>
                                <th
                                    class="border p-3 text-left text-sm font-semibold"
                                >
                                    Adopter
                                </th>
                                <th
                                    class="border p-3 text-left text-sm font-semibold"
                                >
                                    Pet
                                </th>
                                <th
                                    class="border p-3 text-left text-sm font-semibold"
                                >
                                    Status
                                </th>
                                <th
                                    class="border p-3 text-center text-sm font-semibold"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="adoption in filteredAdoptions"
                                :key="adoption.id"
                                class="hover:bg-gray-50 transition"
                            >
                                <td class="border p-3 text-sm">
                                    {{ adoption.id }}
                                </td>
                                <td class="border p-3 text-sm">
                                    {{ adoption.first_name }}
                                    {{ adoption.last_name }}
                                </td>
                                <td class="border p-3 text-sm">
                                    {{ adoption.pet?.name || "Unknown Pet" }}
                                </td>
                                <td
                                    class="border p-3 text-sm"
                                    :class="statusColor(adoption.status)"
                                >
                                    {{ capitalize(adoption.status) }}
                                </td>
                                <td class="border p-3 text-center">
                                    <template
                                        v-if="canManageAdoption(adoption)"
                                    >
                                        <Link
                                            :href="`/adopt/${adoption.id}/edit`"
                                            class="bg-primary text-white py-2 px-4 mx-4 rounded-md hover:bg-white hover:text-primary border border-primary transition"
                                        >
                                            Edit
                                        </Link>
                                        <form
                                            @submit.prevent="
                                                deleteAdoption(
                                                    adoption.id,
                                                    adoption.status
                                                )
                                            "
                                            class="inline"
                                        >
                                            <button
                                                type="submit"
                                                class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-white hover:text-red-500 border border-red-500 transition"
                                                :disabled="form.processing"
                                            >
                                                <span v-if="form.processing"
                                                    >Deleting...</span
                                                >
                                                <span v-else>Delete</span>
                                            </button>
                                        </form>
                                    </template>
                                    <span v-else class="text-gray-500"
                                        >No actions available</span
                                    >
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
const selectedFilter = ref("pending"); // Default filter set to pending

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
    return lowerStatus === "pending"
        ? "text-yellow-400"
        : lowerStatus === "approved"
        ? "text-green-500"
        : lowerStatus === "declined"
        ? "text-red-500"
        : "";
}

// Check if user can manage adoption (edit/delete)
function canManageAdoption(adoption) {
    if (!authUser) return false;
    const isOwner = authUser.id === adoption.user_id;
    const isAdminOrShelter = authUser.roles?.some(
        (role) => role.name === "Administrator" || role.name === "Shelter"
    );
    return isOwner || isAdminOrShelter;
}

// Prevent deleting approved adoptions
function deleteAdoption(id, status) {
    if (status.toLowerCase() === "approved") {
        alert("You cannot delete approved adoption requests.");
        return;
    }

    if (confirm("Are you sure you want to delete this adoption request?")) {
        form.delete(`/adopt/${id}`, { preserveScroll: true });
    }
}
</script>
