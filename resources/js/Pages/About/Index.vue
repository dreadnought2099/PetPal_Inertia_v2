<template>
  <AppLayout>
    <div class="min-h-[70vh] flex flex-col items-center text-center justify-center space-y-8 sm:space-y-12 px-4 sm:px-6 md:px-16">
      <div
        v-for="(title, key) in sectionTitles"
        :key="key"
        class="max-w-3xl mx-auto px-4 sm:px-8"
      >
        <h1 class="text-2xl sm:text-3xl md:text-5xl font-bold text-dark leading-tight sm:leading-snug" v-html="title"></h1>

        <div class="mt-6 sm:mt-8 space-y-4 sm:space-y-6 text-base sm:text-lg text-gray-700 text-left md:text-center">
          <p
            v-for="(content, index) in aboutData[key]?.content"
            :key="index"
            class="leading-relaxed sm:leading-loose tracking-wide"
          >
            {{ content }}
          </p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';

defineProps({
  aboutData: Object
});

const sectionTitles = {
  about: 'What is <span class="text-primary">PetPal?</span> ',
  problem: '<span class="text-primary">Problems</span> Encountered'
};
</script>
  