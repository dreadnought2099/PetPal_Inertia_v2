<template>
    <AppLayout>
        <div
            class="container mx-auto max-w-5xl bg-white mt-4 border border-primary rounded-lg shadow-md overflow-y-auto h-[80vh] px-4 sm:px-8"
        >
            <h2
                class="flex gap-1 sticky top-0 py-2 px-4 text-lg sm:text-xl md:text-2xl font-bold bg-white justify-center z-10"
            >
                Add a <span class="text-primary">Pet</span>
            </h2>

            <form
                @submit.prevent="submit"
                enctype="multipart/form-data"
                class="pt-6 pb-8 space-y-4 sm:space-y-6"
            >
                <!-- Text Inputs -->
                <div
                    v-for="field in textFields"
                    :key="field.name"
                    class="relative"
                >
                    <input
                        :type="field.type"
                        :name="field.name"
                        v-model="form[field.name]"
                        :placeholder="field.label"
                        class="peer py-4 sm:py-4.5 md:py-5 px-5 sm:px-6 md:px-7 text-sm sm:text-base md:text-lg w-full placeholder-transparent rounded-md text-gray-700 ring-1 ring-gray-400 focus:ring-2 focus:ring-primary outline-none"
                        required
                    />
                    <label
                        class="absolute left-3 sm:left-4 -top-2.5 sm:-top-3 text-xs sm:text-sm md:text-base text-gray-600 bg-white px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2.5 sm:peer-placeholder-shown:top-3 peer-focus:-top-2.5 sm:peer-focus:-top-3 peer-focus:text-primary peer-focus:text-xs sm:peer-focus:text-sm peer-focus:px-2"
                    >
                        {{ field.label }}
                    </label>
                    <p
                        v-if="form.errors[field.name]"
                        class="text-red-500 text-xs sm:text-sm mt-1"
                    >
                        {{ form.errors[field.name] }}
                    </p>
                </div>

                <!-- Description -->
                <div class="relative">
                    <textarea
                        name="description"
                        rows="4"
                        v-model="form.description"
                        @focus="descFocused = true"
                        @blur="descFocused = false"
                        class="py-2 sm:py-2.5 md:py-3 w-full rounded-md text-gray-700 ring-1 px-3 sm:px-4 ring-gray-400 focus:ring-2 focus:ring-primary outline-none text-xs sm:text-sm md:text-base"
                    ></textarea>
                    <label
                        class="absolute left-3 sm:left-4 bg-white px-1 transition-all duration-200 text-xs sm:text-sm md:text-base text-gray-600"
                        :class="
                            descFocused || form.description
                                ? 'text-primary -top-2.5 sm:-top-3 text-xs sm:text-sm px-2'
                                : 'top-2.5 sm:top-3 text-base text-gray-500'
                        "
                    >
                        Description
                    </label>
                    <p
                        v-if="form.errors.description"
                        class="text-red-500 text-xs sm:text-sm mt-1"
                    >
                        {{ form.errors.description }}
                    </p>
                </div>

                <!-- File Upload -->
                <div class="relative">
                    <input
                        type="file"
                        name="profile"
                        accept="image/*"
                        @change="(e) => (form.profile = e.target.files[0])"
                        class="peer py-2 sm:py-2.5 md:py-3 w-full placeholder-transparent rounded-md text-gray-700 ring-1 px-3 sm:px-4 ring-gray-400 focus:ring-2 focus:ring-primary outline-none text-xs sm:text-sm md:text-base"
                    />
                    <label
                        class="absolute left-3 sm:left-4 -top-2.5 sm:-top-3 text-xs sm:text-sm md:text-base text-gray-600 bg-white px-1 transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-placeholder-shown:top-2.5 sm:peer-placeholder-shown:top-3 peer-focus:-top-2.5 sm:peer-focus:-top-3 peer-focus:text-primary peer-focus:text-xs sm:peer-focus:text-sm peer-focus:px-2"
                    >
                        Pet Profile (Upload Image)
                    </label>
                    <p
                        v-if="form.errors.profile"
                        class="text-red-500 text-xs sm:text-sm mt-1"
                    >
                        {{ form.errors.profile }}
                    </p>
                </div>

                <!-- Select Inputs -->
                <div
                    v-for="select in selectFields"
                    :key="select.name"
                    class="relative"
                >
                    <select
                        :name="select.name"
                        v-model="form[select.name]"
                        class="peer py-2 sm:py-2.5 md:py-3 w-full rounded-md text-gray-700 ring-1 px-3 sm:px-4 ring-gray-400 focus:ring-2 focus:ring-primary outline-none text-xs sm:text-sm md:text-base appearance-none bg-white"
                        required
                    >
                        <option
                            v-for="(label, value) in select.options"
                            :key="value"
                            :value="select.isString ? value : Number(value)"
                        >
                            {{ label }}
                        </option>
                    </select>
                    <label
                        class="absolute left-3 sm:left-4 -top-2.5 sm:-top-3 text-xs sm:text-sm md:text-base text-gray-600 bg-white px-1 transition-all"
                        :class="{
                            'text-gray-500 top-2.5 sm:top-3':
                                form[select.name] === '' ||
                                form[select.name] === null,
                            'text-primary -top-2.5 sm:-top-3':
                                form[select.name] !== '' &&
                                form[select.name] !== null,
                        }"
                    >
                        {{ select.label }}
                    </label>
                    <div
                        class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none"
                    >
                        <svg
                            class="w-4 h-4 text-gray-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </div>
                    <p
                        v-if="form.errors[select.name]"
                        class="text-red-500 text-xs sm:text-sm mt-1"
                    >
                        {{ form.errors[select.name] }}
                    </p>
                </div>

                <!-- Spayed/Neutered -->
                <div class="relative">
                    <span
                        class="block text-xs sm:text-sm md:text-base text-gray-700 font-medium mb-2"
                    >
                        Spayed/Neutered
                    </span>
                    <div class="flex space-x-4">
                        <label class="flex items-center space-x-2">
                            <input
                                type="radio"
                                name="spayed_neutered"
                                value="1"
                                v-model="form.spayed_neutered"
                                required
                                class="w-4 h-4 sm:w-5 sm:h-5 text-primary focus:ring-primary"
                            />
                            <span class="text-xs sm:text-sm md:text-base"
                                >Yes</span
                            >
                        </label>
                        <label class="flex items-center space-x-2">
                            <input
                                type="radio"
                                name="spayed_neutered"
                                value="0"
                                v-model="form.spayed_neutered"
                                required
                                class="w-4 h-4 sm:w-5 sm:h-5 text-primary focus:ring-primary"
                            />
                            <span class="text-xs sm:text-sm md:text-base"
                                >No</span
                            >
                        </label>
                    </div>
                    <p
                        v-if="form.errors.spayed_neutered"
                        class="text-red-500 text-xs sm:text-sm mt-1"
                    >
                        {{ form.errors.spayed_neutered }}
                    </p>
                </div>

                <!-- Buttons -->
                <div
                    class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4"
                >
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="border border-primary bg-primary text-white font-bold py-2 sm:py-2.5 px-4 sm:px-6 rounded-lg transition hover:scale-105 hover:bg-white hover:text-primary hover:border-primary duration-300 cursor-pointer text-xs sm:text-sm md:text-base"
                    >
                        Add
                    </button>
                    <button
                        type="button"
                        @click="goBack"
                        class="border border-dark hover:border-primary bg-white text-dark font-bold py-2 sm:py-2.5 px-4 sm:px-6 rounded-lg transition hover:scale-105 hover:text-primary duration-300 text-xs sm:text-sm md:text-base"
                    >
                        Back
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";

const descFocused = ref(false);

const textFields = [
    { name: "name", label: "Pet's Name", type: "text" },
    { name: "age", label: "Age (in years)", type: "number" },
    { name: "breed", label: "Breed", type: "text" },
    {
        name: "allergies",
        label: 'Allergies (N/A if None)',
        type: "text",
    },
];

const selectFields = [
    {
        name: "sex",
        label: "Sex",
        options: { M: "Male", F: "Female" },
        isString: true,
    },
    { name: "species", label: "Species", options: { 0: "Dog", 1: "Cat" } },
    {
        name: "vaccination",
        label: "Vaccination",
        options: { 0: "None", 1: "Partially", 2: "Fully" },
    },
];

const form = useForm({
    name: "",
    age: "",
    breed: "",
    allergies: "",
    description: "",
    profile: null,
    sex: "",
    species: null,
    vaccination: null,
    spayed_neutered: "",
});

function submit() {
    form.species = Number(form.species);
    form.vaccination = Number(form.vaccination);
    form.spayed_neutered = Number(form.spayed_neutered);
    form.post("/pets", {
        forceFormData: true,
        onSuccess: () => {
            form.reset("profile");
        },
        onError: (errors) => {
            // Error will be shown via flash message
        },
    });
}

function goBack() {
    window.history.back();
}
</script>
