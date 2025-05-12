<template>
    <footer class="bg-green-100 shadow-inner mt-8 sm:mt-12">
      <div class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 py-4 sm:py-8 md:py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 md:gap-8">
          <div class="text-center sm:text-left">
            <Link
              href="/"
              title="PetPal"
              class="text-xl sm:text-2xl md:text-3xl font-bold text-primary hover-underline-hyperlink hover:scale-110 transition-all duration-300 ease-in-out cursor-pointer inline-block"
              >PetPal</Link
            >
            <p class="mt-1 sm:mt-2 text-[10px] sm:text-xs md:text-sm text-gray-600">Find. Love. Adopt.</p>
          </div>
  
          <div class="text-center sm:text-left">
            <h3 class="text-xs sm:text-sm md:text-base font-semibold text-gray-700 mb-1 sm:mb-2 md:mb-3">Discover</h3>
            <ul class="space-y-0.5 sm:space-y-1 md:space-y-2">
              <li>
                <Link
                  href="/about"
                  title="About us"
                  class="text-[10px] sm:text-xs md:text-sm text-primary hover-underline-hyperlink transition cursor-pointer inline-block"
                  >About</Link
                >
              </li>
              <li>
                <Link
                  href="/contact"
                  title="Contact us"
                  class="text-[10px] sm:text-xs md:text-sm text-primary hover-underline-hyperlink transition cursor-pointer inline-block"
                  >Contact</Link
                >
              </li>
            </ul>
          </div>
  
          <div class="text-center sm:text-left">
            <h3 class="text-sm sm:text-md font-semibold text-gray-700 mb-2 sm:mb-3">Connect</h3>
            <ul class="space-y-1 sm:space-y-2">
              <li>
                <a
                  href="https://www.facebook.com/RMAGALLANEZ.18"
                  target="_blank"
                  class="flex items-center justify-center sm:justify-start space-x-2 text-gray-700 hover:text-primary hover:scale-105 transition-all duration-300"
                  >
                  <img src="/icon/facebook.svg" class="w-5 h-5 sm:w-6 sm:h-6" alt="Facebook" />
                  <span class="text-xs sm:text-sm hover-underline-hyperlink">Facebook</span>
                </a>
              </li>
  
              <li>
                <a
                  href="https://www.instagram.com/skerm_art/"
                  target="_blank"
                  class="flex items-center justify-center sm:justify-start space-x-2 text-gray-700 hover:text-primary hover:scale-105 transition-all duration-300"
                  >
                  <img src="/icon/instagram.svg" class="w-5 h-5 sm:w-6 sm:h-6" alt="Instagram" />
                  <span class="text-xs sm:text-sm hover-underline-hyperlink">Instagram</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
  
        <div class="mt-8 sm:mt-10 pt-4 sm:pt-6">
          <p class="text-center text-xs sm:text-sm text-gray-500">
            &copy; <span>{{ currentYear }}</span>
            <Link href="/" title="PetPal" class="text-gray-500 hover-underline-hyperlink mx-1">PetPal.</Link>All Rights Reserved.
          </p>
        </div>
      </div>
    </footer>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  import { computed } from 'vue';

  const currentYear = computed(() => new Date().getFullYear());
  </script>
  
  <style scoped>
  /* Add any scoped styles if needed */
  </style>
  