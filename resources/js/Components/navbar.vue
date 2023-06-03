<template>
  <div
    class="h-screen w-full fixed z-20"
    @click="closeMenu"
    v-show="active"
  ></div>

  <nav class="bg-white flex justify-between select-none">
    <div class="flex">
      <div class="py-3 pl-10 w-fit mr-5">
        <ApplicationLogo class="font-light cursor-pointer text-3xl" />
      </div>
      <div class="flex max-md:hidden">
        <Link
          :href="route('dashboard')"
          as="small"
          class="text-gray-700 py-5 font-bold text-sm px-5 cursor-pointer hover:border-b-2 hover:border-red-200 transition duration-200 ease-in-out"
          :class="{
            'border-b-2': props.ziggy.routes.dashboard.uri == location,
            'border-red-600': props.ziggy.routes.dashboard.uri == location,
          }"
          >Beranda</Link
        >
        <Link
          :href="route('myExam')"
          as="small"
          class="text-gray-700 py-5 font-bold text-sm px-5 cursor-pointer hover:border-b-2 hover:border-red-200 transition duration-200 ease-in-out"
          :class="{
            'border-b-2':
              props.ziggy.routes.myExam.uri == location ||
              props.examSelected == 'Ujian saya',
            'border-red-600':
              props.ziggy.routes.myExam.uri == location ||
              props.examSelected == 'Ujian saya',
          }"
          >Ujian Saya</Link
        >
        <Link
          :href="route('rankCurrent')"
          as="small"
          class="text-gray-700 py-5 font-bold text-sm px-5 cursor-pointer hover:border-b-2 hover:border-red-200 transition duration-200 ease-in-out"
          :class="{
            'border-b-2': props.ziggy.routes.rankCurrent.uri == location,
            'border-red-600': props.ziggy.routes.rankCurrent.uri == location,
          }"
          >Peringkat</Link
        >
        <Link
          :href="route('history')"
          as="small"
          class="text-gray-700 py-5 font-bold text-sm px-5 cursor-pointer hover:border-b-2 hover:border-red-200 transition duration-200 ease-in-out"
          :class="{
            'border-b-2': props.ziggy.routes.history.uri == location,
            'border-red-600': props.ziggy.routes.history.uri == location,
          }"
          >Aktifitas</Link
        >
      </div>
    </div>
    <div class="max-md:hidden">
      <div
        class="flex items-center cursor-pointer select-none hover:bg-gray-50 px-4 py-1.5"
        @click="active = !active"
      >
        <!-- <img :src="`${props.user.attachment.path}${props.user.attachment.filename}`" class="rounded-full w-10 h-10" /> -->
        <div class="rounded-full w-10 h-10 bg-cover bg-center" :style="`background-image:url('${props.user.attachment.path}${props.user.attachment.filename}')`"></div>
        <small class="py-4 font-semibold text-sm pl-4">{{ props.user.name }}</small>
        <small
          class="py-4 font-semibold text-sm pr-4 pl-2"
          :class="{ hidden: active === false }"
          ><ChevronUp class="w-4 h-4"
        /></small>
        <small
          class="py-4 font-semibold text-sm pr-4 pl-2"
          :class="{ hidden: active === true }"
          ><ChevronDown class="w-4 h-4"
        /></small>
      </div>
      <div
        class="absolute bg-white w-fit w-2/12 right-0 shadow-lg rounded-b z-30"
        :class="{ hidden: active == false }"
      >
        <Link
          as="div"
          class="py-2 hover:bg-gray-50 px-5 cursor-pointer"
          :href="route('setting')"
          >Pengaturan</Link
        >
        <Link
          as="div"
          method="post"
          class="py-2 hover:bg-gray-50 px-5 cursor-pointer"
          :href="route('logout')"
          >Keluar</Link
        >
      </div>
    </div>
    <div class="md:hidden flex items-center ">
      <MenuOutline
        class="w-7 active:-rotate-45 transition duration-150 cursor-pointer mx-10"
        v-show="md == false"
        @click="md = true"
      />
      <CloseOutline
        class="w-7 active:-rotate-45 transition duration-150 cursor-pointer mx-6"
        v-show="md == true"
        @click="md = false"
      />
    </div>
  </nav>
  <div class="bg-white absolute h-screen w-full md:hidden" style="z-index: 99999" v-show="md == true">
    <div class="px-5">
      <div
        class=" my-5 border-b-2 bg-white w-full right-0"
      >
        <div class="py-3 my-3 hover:bg-gray-50 px-5 cursor-pointer flex">
          <!-- <img :src="`${props.user.attachment.path}${props.user.attachment.filename}`" class="rounded-full w-10 h-10" /> -->
          <div class="rounded-full w-10 h-10 bg-cover bg-center" :style="`background-image:url('${props.user.attachment.path}${props.user.attachment.filename}')`"></div>
        <small class="py-4 font-semibold text-sm pl-4 font-bold">{{ props.user.name }}</small>
        </div>
                <Link
          as="div"
          class="py-3 my-3 hover:bg-gray-50 px-5 cursor-pointer"
          :href="route('setting')"
          >Pengaturan</Link
        >
        <Link
          as="div"
          method="post"
          class="py-3 my-3 hover:bg-gray-50 px-5 cursor-pointer"
          :href="route('logout')"
          >Keluar</Link>
      </div>
      <div>
        <Link
          as="div"
          class="py-3 my-3 hover:bg-gray-50 px-5 cursor-pointer"
          :href="route('dashboard')"
          :class="{
            'border-b-2': props.ziggy.routes.dashboard.uri == location,
            'border-red-600': props.ziggy.routes.dashboard.uri == location,
          }"
          >Beranda</Link>
          <Link
          as="div"
          class="py-3 my-3 hover:bg-gray-50 px-5 cursor-pointer"
          :href="route('myExam')"
          :class="{
            'border-b-2':
              props.ziggy.routes.myExam.uri == location ||
              props.examSelected == 'Ujian saya',
            'border-red-600':
              props.ziggy.routes.myExam.uri == location ||
              props.examSelected == 'Ujian saya',
          }"
          >Ujian Saya</Link>
          <Link
          as="div"
          class="py-3 my-3 hover:bg-gray-50 px-5 cursor-pointer"
          :href="route('rankCurrent')"
:class="{
            'border-b-2': props.ziggy.routes.rankCurrent.uri == location,
            'border-red-600': props.ziggy.routes.rankCurrent.uri == location,
          }"
          >Peringkat</Link>
          <Link
          as="div"
          class="py-3 my-3 hover:bg-gray-50 px-5 cursor-pointer"
          :href="route('history')"
          :class="{
            'border-b-2': props.ziggy.routes.history.uri == location,
            'border-red-600': props.ziggy.routes.history.uri == location,
          }"
          >Aktifitas</Link>
      </div>
    </div>
  </div>
</template>
<script setup>
import ApplicationLogo from "./ApplicationLogo.vue";
import { ChevronDown, ChevronUp, MenuOutline, CloseOutline } from "@vicons/ionicons5";
import { ref, watch } from "vue";
import { Link, router } from "@inertiajs/inertia-vue3";
const props = defineProps({
  user: Object,
  ziggy: Object,
  examSelected: String,
});
// console.log(props.user);
const location = ref("");
const active = ref(false);
const locationNow = () => {
  return new Promise(function (resolve) {
    resolve(props.ziggy.location);
  });
};
locationNow()
  .then((val) => {
    return (location.value = val);
  })
  .then((val) => {
    return val.split("/");
  })
  .then((val) => {
    return val.slice(3, val.length);
  })
  .then((val) => {
    return val.join("/");
  })
  .then((val) => {
    location.value = val;
  });
const closeMenu = () => {
  active.value = false;
};
let md = ref(false);
</script>
