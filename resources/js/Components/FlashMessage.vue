<script setup>
import { computed, onMounted, ref } from "vue";

const props = defineProps({
    type: {
        type: String,
        default: "success",
    },
    message: {
        type: String,
        required: true,
    },
    duration: {
        type: Number,
        default: 5000,
    },
});

const show = ref(true);
const opacity = ref(1);

const alertClasses = computed(() => {
    const baseClasses =
        "p-1.5 sm:p-2 md:p-3 rounded-md shadow-lg border-l-4 transition-opacity duration-500 text-[10px] sm:text-xs md:text-sm";
    const typeClasses = {
        success: "bg-green-100 text-green-700",
        error: "bg-red-100 text-red-700",
        warning: "bg-yellow-100 text-yellow-700",
        info: "bg-yellow-100 text-yellow-700",
    };
    return `${baseClasses} ${typeClasses[props.type]}`;
});

const close = () => {
    opacity.value = 0;
    setTimeout(() => {
        show.value = false;
    }, 500);
};

onMounted(() => {
    if (props.duration > 0) {
        setTimeout(() => {
            close();
        }, props.duration);
    }
});
</script>

<template>
    <div v-if="show" :class="alertClasses" :style="{ opacity }" role="alert">
        <p class="block sm:inline">{{ message }}</p>
    </div>
</template>
