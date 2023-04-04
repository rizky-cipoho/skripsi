<template>
    <nav class="bg-white flex justify-between select-none">
        <div class="flex">
            <div class="py-3 pl-10 w-fit mr-5">
                <ApplicationLogo class="font-light cursor-pointer text-3xl" />
            </div>
            <div class="flex">
                <Link :href="route('dashboard')" as="small" class="text-gray-700 py-5 font-bold text-sm px-5 cursor-pointer hover:border-b-2 hover:border-red-200 transition duration-200 ease-in-out"
                :class="{'border-b-2': props.ziggy.routes.dashboard.uri == location, 'border-red-600': props.ziggy.routes.dashboard.uri == location }"
                    >Beranda</Link
                >
                <Link :href="route('myExam')" as="small" class="text-gray-700 py-5 font-bold text-sm px-5 cursor-pointer hover:border-b-2 hover:border-red-200 transition duration-200 ease-in-out"
                :class="{'border-b-2': props.ziggy.routes.myExam.uri == location || props.examSelected == 'Ujian saya', 'border-red-600': props.ziggy.routes.myExam.uri == location || props.examSelected == 'Ujian saya'}"
                    >Ujian Saya</Link
                >
                <Link href="/dashboard" as="small" class="text-gray-700 py-5 font-bold text-sm px-5 cursor-pointer hover:border-b-2 hover:border-red-200 transition duration-200 ease-in-out"
                    >Peringkat</Link
                >
                <Link href="/dashboard" as="small" class="text-gray-700 py-5 font-bold text-sm px-5 cursor-pointer hover:border-b-2 hover:border-red-200 transition duration-200 ease-in-out"
                    >Aktifitas</Link
                >
                <Link href="/dashboard" as="small" class="text-gray-700 py-5 font-bold text-sm px-5 cursor-pointer hover:border-b-2 hover:border-red-200 transition duration-200 ease-in-out"
                    >Kelas</Link
                >
            </div>
        </div>
        <div class="">
            <div
                class="flex items-center cursor-pointer select-none hover:bg-gray-50 px-4 py-1.5"
                @click="active = !active"
            >
                <img src="/image/download.jpg" class="rounded-full w-10 h-10" />
                <small class="py-4 font-semibold text-sm pl-4"
                    >{{ props.name }}</small>
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
                class="absolute bg-white w-fit w-2/12 right-0 shadow-lg rounded-b"
                :class="{ hidden: active == false }"
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
    </nav>
</template>
<script setup>
import ApplicationLogo from "./ApplicationLogo.vue";
import { ChevronDown, ChevronUp } from "@vicons/ionicons5";
import { ref, watch } from "vue";
import { Link, router } from "@inertiajs/inertia-vue3";
const props = defineProps({
    name: String,
    ziggy: Object,
    examSelected: String
})
const location = ref("")
const active = ref(false)
const locationNow = ()=>{
    return new Promise(function(resolve){
        resolve(props.ziggy.location);
    });
}
locationNow().then((val)=>{
    return location.value = val;
})
.then((val)=>{
    return val.split("/");
})
.then((val)=>{
    return val.slice(3,val.length)
})
.then((val)=>{

    return val.join("/")
})
.then((val)=>{
    location.value = val
});
</script>
