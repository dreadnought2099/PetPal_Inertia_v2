<template>
    <div>
      <Navbar />
  
      <main class="py-6">
        <slot />
      </main>
      <Footer />
    </div>
  </template>
  
  <script setup>
  import Navbar from '../Components/Navbar.vue';
  import Footer from '../Components/Footer.vue';
  </script>
  