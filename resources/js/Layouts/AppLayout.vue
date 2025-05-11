<template>
    <div class="min-h-screen bg-gray-100">
        <Head>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </Head>
        <!-- Flash Messages Container -->
        <div
            id="success-message-container"
            class="absolute top-24 right-4 z-10"
        >
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
            <FlashMessage
                v-if="flash.warning"
                type="warning"
                :message="flash.warning"
            />
            <FlashMessage v-if="flash.info" type="info" :message="flash.info" />
            <FlashMessage
                v-if="Object.keys(errors).length"
                type="error"
                :message="Object.values(errors)[0]"
            />
        </div>

        <Navbar />

        <main class="py-2 sm:py-4 md:py-8 px-2 sm:px-4 md:px-8">
            <slot />
        </main>
        <Footer />
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import FlashMessage from "../Components/FlashMessage.vue";
import Navbar from "../Components/Navbar.vue";
import Footer from "../Components/Footer.vue";

const page = usePage();
const flash = computed(() => page.props.flash);
const errors = computed(() => page.props.errors);
</script>
