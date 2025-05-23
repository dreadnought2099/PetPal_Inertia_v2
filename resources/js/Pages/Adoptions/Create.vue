<script setup>
import { useForm, Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import AppLayout from "../../Layouts/AppLayout.vue";
import FlashMessage from "../../Components/FlashMessage.vue";

const props = defineProps({
    selectedPet: Object,
    pets: Object,
    user: Object,
    flash: Object,
    errors: Object,
});

const form = useForm({
    pet_id: props.selectedPet?.id || "",
    last_name: "",
    first_name: "",
    middle_name: "",
    address: "",
    contact_number: "",
    dob: "",
    valid_id: null,
    valid_id_back: null,
    previous_experience: "",
    other_pets: "",
    financial_preparedness: "",
});

const maxDob = computed(() => {
    const today = new Date();
    today.setFullYear(today.getFullYear() - 18);
    return today.toISOString().split("T")[0];
});

function submit() {
    form.post("/adopt/request", {
        onSuccess: () => {
            form.reset();
        },
        preserveScroll: true,
        forceFormData: true,
    });
}
</script>

<template>
    <AppLayout>
        <Head title="Apply for Adoption" />

        <div
            class="container mx-auto max-w-5xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh]"
        >
            <h1
                class="flex gap-1 sticky top-0 py-2 px-4 text-2xl font-bold bg-white justify-center z-[1]"
            >
                Apply for
                <span class="text-primary"> Adoption</span>
            </h1>

            <FlashMessage
                v-if="flash.success"
                type="success"
                :message="flash.success"
            />

            <FlashMessage
                v-if="flash.error"
                type="error"
                :message="flash.error"
            />

            <!-- Form Errors -->
            <div
                v-if="Object.keys(errors).length"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative m-4"
                role="alert"
            >
                <ul>
                    <li v-for="(error, field) in errors" :key="field">
                        {{ error }}
                    </li>
                </ul>
            </div>

            <form
                @submit.prevent="submit"
                class="rounded-lg px-8 pt-6 pb-8 mb-4 space-y-6 z-9"
                enctype="multipart/form-data"
            >
                <!-- Select Pet -->
                <div class="p-3 bg-gray-100 rounded-md">
                    <label class="font-semibold">Select Pet</label>
                    <select
                        v-model="form.pet_id"
                        class="w-full border p-2 rounded mt-2"
                        required
                    >
                        <option value="" disabled>Choose a pet</option>
                        <template v-if="pets?.data">
                            <option
                                v-for="pet in pets.data"
                                :key="pet.id"
                                :value="pet.id"
                            >
                                {{ pet.name }} - {{ pet.breed }}
                                ({{ pet.status }})
                            </option>
                        </template>
                    </select>
                </div>

                <!-- Personal Information -->
                <div class="relative bg-inherit">
                    <input
                        type="text"
                        v-model="form.last_name"
                        class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="Last Name"
                        required
                    />
                    <label
                        class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                        >Last Name</label
                    >
                </div>

                <div class="relative bg-inherit">
                    <input
                        type="text"
                        v-model="form.first_name"
                        class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="First Name"
                        required
                    />
                    <label
                        class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                        >First Name</label
                    >
                </div>

                <div class="relative bg-inherit">
                    <input
                        type="text"
                        v-model="form.middle_name"
                        class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="Middle Name"
                    />
                    <label
                        class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                        >Middle Name (optional)</label
                    >
                </div>

                <div class="relative bg-inherit">
                    <input
                        type="date"
                        v-model="form.dob"
                        :max="maxDob"
                        class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="Date of Birth"
                        required
                    />
                    <label
                        class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                        >Date of Birth</label
                    >
                </div>

                <div class="relative bg-inherit">
                    <textarea
                        v-model="form.address"
                        class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="Address"
                        required
                    ></textarea>
                    <label
                        class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                        >Address</label
                    >
                </div>

                <div class="relative bg-inherit">
                    <input
                        type="tel"
                        v-model="form.contact_number"
                        class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        placeholder="Contact Number"
                        required
                    />
                    <label
                        class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                        >Contact Number</label
                    >
                </div>

                <!-- Valid ID Upload -->
                <div class="relative bg-inherit">
                    <input
                        type="file"
                        @input="form.valid_id = $event.target.files[0]"
                        accept="image/*,.pdf"
                        class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        required
                    />
                    <label
                        class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                        >Valid ID (JPEG, PNG, JPG, PDF)</label
                    >
                </div>

                <div class="relative bg-inherit">
                    <input
                        type="file"
                        @input="form.valid_id_back = $event.target.files[0]"
                        accept="image/*,.pdf"
                        class="peer py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-4 ring-gray-400 focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                        required
                    />
                    <label
                        class="absolute cursor-text left-0 -top-3 text-sm text-gray-600 bg-white mx-1 px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2 peer-focus:-top-3 peer-focus:text-primary peer-focus:text-sm peer-focus:bg-white peer-focus:px-2 peer-focus:rounded-md"
                        >Back Valid ID (JPEG, PNG, JPG, PDF)</label
                    >
                </div>

                <!-- Adoption Questions -->
                <div class="space-y-4">
                    <fieldset class="mb-4">
                        <legend class="font-semibold">
                            Do you have previous pet ownership experience?
                        </legend>
                        <label
                            ><input
                                type="radio"
                                v-model="form.previous_experience"
                                value="yes"
                                required
                            />
                            Yes</label
                        >
                        <label class="ml-4"
                            ><input
                                type="radio"
                                v-model="form.previous_experience"
                                value="no"
                                required
                            />
                            No</label
                        >
                    </fieldset>

                    <fieldset class="mb-4">
                        <legend class="font-semibold">
                            Do you have other pets at home?
                        </legend>
                        <label
                            ><input
                                type="radio"
                                v-model="form.other_pets"
                                value="yes"
                                required
                            />
                            Yes</label
                        >
                        <label class="ml-4"
                            ><input
                                type="radio"
                                v-model="form.other_pets"
                                value="no"
                                required
                            />
                            No</label
                        >
                    </fieldset>

                    <fieldset class="mb-4">
                        <legend class="font-semibold">
                            Are you financially prepared for pet care?
                        </legend>
                        <label
                            ><input
                                type="radio"
                                v-model="form.financial_preparedness"
                                value="yes"
                                required
                            />
                            Yes</label
                        >
                        <label class="ml-4"
                            ><input
                                type="radio"
                                v-model="form.financial_preparedness"
                                value="no"
                                required
                            />
                            No</label
                        >
                    </fieldset>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col md:flex-row gap-4">
                    <button
                        type="submit"
                        class="border hover:border-primary bg-primary hover:bg-white hover:text-primary text-white font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 cursor-pointer"
                    >
                        Apply Now
                    </button>
                    <Link
                        :href="props.user ? '/pets' : '/home'"
                        class="border hover:border-primary bg-white hover:bg-white hover:text-primary text-dark font-bold py-2 px-4 rounded-lg transition hover:scale-105 hover:opacity-80 duration-300 text-center"
                    >
                        Back
                    </Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
