<template>
  <div>
    <div class="min-h-screen bg-gray-100">
      <Navbar  />
      <!-- Page Heading -->
      <header class="bg-white shadow" v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>
      
      <!-- Page Content -->
      <main>
        <slot name="body" @submitt="submitt" />
      </main>
      <footer class="text-center pb-10">
       
      </footer>
    </div>
  </div>
</template>
<script setup>
import Navbar from "@/Components/navbar.vue";
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/inertia-vue3';

</script>