<template>
    <Head title="Pending Adoption Requests" />

    <AppLayout>
        <div
            class="container mx-auto max-w-4xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh] sm:h-[85vh] lg:h-[90vh] p-4 sm:p-6 space-y-10"
        >
            <h1
                class="flex flex-wrap items-center justify-center text-lg sm:text-xl md:text-2xl font-bold bg-white sticky top-0 py-2 sm:py-3 px-2 sm:px-4 text-center"
            >
                Pending Adoption <span class="text-primary ml-1">Requests</span>
            </h1>
            <p
                class="text-gray-400 text-sm sm:text-base sticky top-12 px-2 sm:px-4 text-center sm:text-left"
            >
                Below are the pending requests for pet adoption
            </p>

            <template v-if="pendingAdoptions.length > 0">
                <div
                    v-for="adoption in pendingAdoptions"
                    :key="adoption.id"
                    class="flex items-center justify-between gap-4 px-4 py-4 border-b border-gray-200 w-full"
                >
                    <!-- Adoption Details -->
                    <div
                        class="flex flex-col sm:flex-row gap-2 sm:gap-4 flex-1"
                    >
                        <small class="text-gray-700 text-sm sm:text-base">
                            <span class="text-primary font-medium">
                                {{ adoption.first_name }}
                                {{ adoption.middle_name }}
                                {{ adoption.last_name }}
                            </span>
                            requested an adoption for
                            <span class="text-primary font-medium">
                                {{ adoption.pet?.name || "Unknown Pet" }}
                            </span>
                        </small>
                    </div>

                    <!-- View Details Button -->
                    <button
                        @click="openModal(adoption)"
                        class="sm:w-auto bg-primary text-white text-xs sm:text-sm font-semibold rounded-md border transition duration-300 hover:bg-white hover:text-primary hover:border-primary px-4 py-2 cursor-pointer text-center"
                    >
                        View Details
                    </button>
                </div>
            </template>

            <div v-else class="text-center py-6">
                <p class="px-6 py-3 text-gray-400 text-center">
                    No pending adoption requests at the moment...
                </p>
            </div>

            <!-- Modal -->
            <!-- Modal -->
            <div
                v-if="showModal"
                class="modal fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-black bg-opacity-75 overflow-y-auto"
                @click.self="closeModal"
            >
                <div
                    class="relative flex flex-col max-h-[90vh] overflow-y-auto w-full max-w-sm sm:max-w-lg md:max-w-2xl bg-white border border-primary rounded-xl shadow-md p-4 sm:p-6"
                >
                    <button
                        @click="closeModal"
                        title="Click to close the modal"
                        class="absolute top-2 right-3 text-red-500 text-3xl sm:text-4xl hover:text-gray-400 hover:cursor-pointer"
                    >
                        &times;
                    </button>

                    <h2
                        class="text-lg sm:text-xl mb-4 text-center py-2 sm:py-4"
                    >
                        Adoption Request
                        <span class="text-primary">Details</span>
                    </h2>

                    <p class="text-sm sm:text-base text-gray-700">
                        Adopter's Name:
                        <span class="text-primary">
                            {{ selectedAdoption?.first_name }}
                            {{ selectedAdoption?.middle_name }}
                            {{ selectedAdoption?.last_name }}
                        </span>
                    </p>
                    <p class="text-sm sm:text-base text-gray-700">
                        Pet Name:
                        <span class="text-primary">{{
                            selectedAdoption?.pet?.name || "Unknown Pet"
                        }}</span>
                    </p>
                    <p class="text-sm sm:text-base text-gray-700">
                        Address:
                        <span class="text-primary truncate">{{
                            selectedAdoption?.address
                        }}</span>
                    </p>
                    <p class="text-sm sm:text-base text-gray-700">
                        Contact Number:
                        <span class="text-primary truncate">{{
                            selectedAdoption?.contact_number
                        }}</span>
                    </p>
                    <p class="text-sm sm:text-base text-gray-700">
                        Date of Birth:
                        <span class="text-primary">{{
                            formatDate(selectedAdoption?.dob)
                        }}</span>
                    </p>
                    <p class="text-sm sm:text-base text-gray-700">
                        Previous Pet Experience:
                        <span class="text-primary">
                            {{
                                capitalize(
                                    selectedAdoption?.previous_experience
                                ) || "N/A"
                            }}
                        </span>
                    </p>
                    <p class="text-sm sm:text-base text-gray-700">
                        Other pet at home:
                        <span class="text-primary">
                            {{
                                capitalize(selectedAdoption?.other_pets) ||
                                "N/A"
                            }}
                        </span>
                    </p>
                    <p class="text-sm sm:text-base text-gray-700">
                        Financial Preparedness:
                        <span class="text-primary">
                            {{
                                capitalize(
                                    selectedAdoption?.financial_preparedness
                                ) || "N/A"
                            }}
                        </span>
                    </p>
                    <p class="text-sm sm:text-base text-gray-700">
                        Valid ID:
                        <a
                            :href="
                                '/storage/adoption/valid_ids/front/' +
                                selectedAdoption?.valid_id
                            "
                            target="_blank"
                            class="text-primary px-2 hover-underline-hyperlink hover:scale-125 transition-all ease-in-out duration-300"
                        >
                            View Front
                        </a>
                        <a
                            :href="
                                '/storage/adoption/valid_ids/back/' +
                                selectedAdoption?.valid_id_back
                            "
                            target="_blank"
                            class="text-primary hover-underline-hyperlink hover:scale-125 transition-all ease-in-out duration-300"
                        >
                            View Back
                        </a>
                    </p>

                    <div class="flex justify-end gap-3 mt-4">
                        <button
                            @click="handleApprove"
                            class="bg-primary text-white px-3 py-2 sm:px-4 sm:py-2 rounded hover:bg-white hover:text-primary transition-all ease-in-out duration-300 border border-primary cursor-pointer"
                        >
                            Approve
                        </button>
                        <button
                            @click="handleReject"
                            class="bg-red-500 text-white px-3 py-2 sm:px-4 sm:py-2 rounded hover:bg-white hover:text-red-500 border border-red-500 transition-all ease-in-out cursor-pointer"
                        >
                            Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, computed } from "vue";
import { usePage, useForm, Head } from "@inertiajs/vue3";
import dayjs from "dayjs";

// Get adoptions from page props
const adoptions = usePage().props.adoptions || [];
const pendingAdoptions = computed(() =>
    adoptions.filter((a) => a.status === "pending")
);

// State
const showModal = ref(false);
const selectedAdoption = ref(null);
const form = useForm();

// Modal handlers
function openModal(adoption) {
    selectedAdoption.value = adoption;
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    selectedAdoption.value = null;
}

// Utilities
function capitalize(str) {
    if (!str) return "";
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function formatDate(date) {
    return dayjs(date).format("MMMM DD, YYYY");
}

// Plain URL form handlers
function handleApprove() {
    if (!selectedAdoption.value?.id) return;
    form.patch(`/adopt/${selectedAdoption.value.id}/approve`, {
        onSuccess: () => {
            closeModal(); // Close the modal after approval
            location.reload(); // Reload the page to reflect updated status
        },
    });
}

function handleReject() {
    form.patch(`/adopt/${selectedAdoption.value.id}/reject`, {
        onSuccess: () => {
            // Remove the rejected item from local array
            const index = adoptions.findIndex(
                (a) => a.id === selectedAdoption.value.id
            );
            if (index !== -1) {
                adoptions.splice(index, 1);
            }

            closeModal(); // Close modal
        },
    });
}
</script>

<style scoped>
.modal {
    background-color: rgba(0, 0, 0, 0.4);
}
</style>
