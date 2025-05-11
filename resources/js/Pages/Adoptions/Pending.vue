<template>
    <Head title="Pending Adoption Requests" />

    <AppLayout>
        <div
            class="container mx-auto max-w-4xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-auto h-[80vh] p-6 space-y-10"
        >
            <h1
                class="flex items-center justify-center text-xl font-bold bg-white sticky top-0 py-3 px-4"
            >
                Pending Adoption <span class="text-primary ml-1">Requests</span>
            </h1>
            <p class="text-gray-400 sticky top-12 px-4">
                Below are the pending requests for pet adoption
            </p>

            <template v-if="pendingAdoptions.length > 0">
                <div
                    v-for="adoption in pendingAdoptions"
                    :key="adoption.id"
                    class="flex items-center justify-between gap-4 px-4 py-4 border-b border-gray-200 w-full"
                >
                    <p class="text-gray-700">
                        <span class="text-base text-primary font-medium">
                            {{ adoption.first_name }}
                            {{ adoption.middle_name }} {{ adoption.last_name }}
                        </span>
                        requested an adoption for
                        <span class="text-base text-primary font-medium">
                            {{ adoption.pet?.name || "Unknown Pet" }}
                        </span>
                    </p>
                    <button
                        @click="openModal(adoption)"
                        class="bg-primary text-white text-sm font-semibold rounded-md hover:bg-white hover:text-primary border hover:border-primary transition duration-300 px-4 py-2 cursor-pointer"
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
            <div
                v-if="showModal"
                class="modal fixed inset-0 flex items-center justify-center z-50 p-6 bg-black bg-opacity-75"
                @click.self="closeModal"
            >
                <div
                    class="flex flex-col relative shadow-md border border-primary bg-white rounded-xl max-w-lg w-full p-6"
                >
                    <button
                        @click="closeModal"
                        title="Click to close the modal"
                        class="absolute right-3 top-2 text-red-500 text-4xl hover:text-gray-400 hover:cursor-pointer"
                    >
                        &times;
                    </button>

                    <h2 class="text-xl mb-4 text-center py-6">
                        Adoption Request
                        <span class="text-primary">Details</span>
                    </h2>

                    <p class="text-gray-700">
                        Adopter's Name:
                        <span class="text-primary">
                            {{ selectedAdoption?.first_name }}
                            {{ selectedAdoption?.middle_name }}
                            {{ selectedAdoption?.last_name }}
                        </span>
                    </p>
                    <p class="text-gray-700">
                        Pet Name:
                        <span class="text-primary">{{
                            selectedAdoption?.pet?.name || "Unknown Pet"
                        }}</span>
                    </p>
                    <p class="text-gray-700">
                        Address:
                        <span class="text-primary">{{
                            selectedAdoption?.address
                        }}</span>
                    </p>
                    <p class="text-gray-700">
                        Contact Number:
                        <span class="text-primary">{{
                            selectedAdoption?.contact_number
                        }}</span>
                    </p>
                    <p class="text-gray-700">
                        Date of Birth:
                        <span class="text-primary">{{
                            formatDate(selectedAdoption?.dob)
                        }}</span>
                    </p>
                    <p class="text-gray-700">
                        Previous Pet Experience:
                        <span class="text-primary">
                            {{
                                capitalize(
                                    selectedAdoption?.previous_experience
                                ) || "N/A"
                            }}
                        </span>
                    </p>
                    <p class="text-gray-700">
                        Other pet at home:
                        <span class="text-primary">
                            {{
                                capitalize(selectedAdoption?.other_pets) ||
                                "N/A"
                            }}
                        </span>
                    </p>
                    <p class="text-gray-700">
                        Financial Preparedness:
                        <span class="text-primary">
                            {{
                                capitalize(
                                    selectedAdoption?.financial_preparedness
                                ) || "N/A"
                            }}
                        </span>
                    </p>
                    <p class="text-gray-700">
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

                    <div class="flex justify-end gap-4 mt-4">
                        <button
                            @click="handleApprove"
                            class="bg-primary text-white px-4 py-2 rounded hover:bg-white hover:text-primary transition-all ease-in-out duration-300 border border-primary cursor-pointer"
                        >
                            Approve
                        </button>
                        <button
                            @click="handleReject"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-white hover:text-red-500 border border-red-500 transition-all ease-in-out cursor-pointer"
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
