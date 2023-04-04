<template>
    <div>
        <Navbar :name="props.authNow.name" :ziggy="props.ziggy" />
        <div class="pt-10 md:mb-16 px-10 text-gray-700 md:flex md:flex-warp">
            <div
                class="shadow px-5 py-5 bg-white md:flex md:flex-warp md:w-9/12 rounded"
            >
                <div class="md:w-6/12 flex max-md:justify-center items-center px-5 max-md:py-5">
                    <img src="/image/loli.png" class="rounded-full w-24 h-24" />
                    <div class="ml-3 pl-3">
                        <p class="leading-8 text-sm">
                            {{ props.authNow.name }}
                        </p>
                        <p class="leading-none text-lg font-semibold">
                            Poin. {{ props.authNow.point.point }}
                        </p>
                    </div>
                </div>
                <div
                    class="md:w-4/12 flex items-center justify-center max-md:border-t md:border-l px-5 max-md:py-5"
                >
                    <div class="text-center">
                        <div class="flex justify-center">
                            <DocumentTextOutline
                                class="w-6 text-red-600 mr-1"
                            />
                            <p class="text-3xl">{{ props.resultExam }}</p>
                        </div>
                        <small class="text-sm">Ujian yang Dikerjakan</small>
                    </div>
                </div>
                <div
                    class="md:w-4/12 flex items-center justify-center max-md:border-t md:border-l px-5 max-md:py-5"
                >
                    <div class="text-center">
                        <div class="flex justify-center">
                            <BookmarksSharp class="w-6 mr-1 text-red-600" />
                        </div>
                        <p class="text-2xl">{{ props.favorite }}</p>
                        <small class="text-sm">Pelajan yang Disukai</small>
                    </div>
                </div>
            </div>
            <div class="md:px-5 flex flex-warp md:w-3/12 rounded max-md:py-5">
                <div
                    class="w-full h-auto bg-white shadow flex items-center justify-center text-center max-md:py-5"
                >
                    <div>
                        <p class="text-xl font-semibold text-center">
                            Kode Ujian
                        </p>
                        <input
                            type=""
                            name=""
                            class="shadow border border-gray-300 rounded focus:border-gray-500 focus:outline-none py-1 px-2 text-sm block"
                            @click="openModal"
                            v-model="search"
                            readonly
                        />

                        <button
                            class="bg-red-600 text-white py-1 mt-2 px-2 rounded font-semibold hover:bg-gray-700 focus:outline-none tracking-widest active:bg-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 inline-flex block mb-1"
                            @click="searchAxios"
                            :disabled="pending"
                        >
                            {{ pending ? "Pending" : "Masuk" }}
                        </button>
                        <small class="leading-1 text-red-600 block">{{
                            message
                        }}</small>
                    </div>
                </div>
            </div>
        </div>
        <RowExamRecommendation :recommendationsData="recommendationsData" :favorite="props.favorite" />
        <RowExam v-for="seconds in props.seconds" :seconds="seconds" />
        <Modal :show="isOpen" @closeModal="closeModal" />
        <ModalLink :show="isOpenLink" :search="search" :link="link" @closeModal="closeModal" />
        <ModalAlert :show="isOpenAlert" :search="search" :message="message" @closeModal="closeModal" />
    </div>
    <button @click="check">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
        veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
        velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
        occaecat cupidatat non proident, sunt in culpa qui officia deserunt
        mollit anim id est laborum.
    </button>
</template>

<script setup>
import Navbar from "@/Components/navbar.vue";
import { Head, Link, useForm, router } from "@inertiajs/inertia-vue3";
import { ref, watch, computed } from "vue";

import { BookmarksSharp, DocumentTextOutline } from "@vicons/ionicons5";
import Modal from "@/Components/modal/modal.vue";
import ModalLink from "@/Components/modal/modalLink.vue";
import ModalAlert from "@/Components/modal/modalAlert.vue";
import RowExam from "@/Components/dashboard/rowExam.vue";
import RowExamRecommendation from "@/Components/dashboard/RowExamRecommendation.vue";

const props = defineProps({
    point: Number,
    resultExam: Number,
    favorite: String,
    authNow: Object,
    recommendations: Object,
    seconds: Object,
    ziggy: Object,
});
let form = useForm({
    exam: null,
});
const message = ref("");
const isOpen = ref(false);
const isOpenLink = ref(false);
const isOpenAlert = ref(false);
const search = ref("");

// let aa = window.addEventListener("blur", function(){
//   console.log("bener blur");
// });
// document.fullScreenElement != null
let open = ref(false);
const input = ref({
    search: "",
});

let cardData = ref([]);
const recommendationsData = ref([])
const dataReverse = ()=>{
    return new Promise(result =>{
        result(recommendationsData.value.push(...props.recommendations));
    });
}
watch(
    recommendationsData.value,
    () => {

        recommendationsData.value = [];
        const reverse = dataReverse();
        reverse.then((arr)=>recommendationsData.value.slice(1,4).reverse())
    },
    {
        immediate: true,
    }
);
const closeModal = (val)=> {
    if (val != null) {
    search.value = val.value;

    }
    isOpen.value = false;
    isOpenAlert.value = false;
    isOpenLink.value = false;
}
function openModal() {
    isOpen.value = true;
}
const pending = ref(false);
const link = ref("/dashboard/exam/");
const searchAxios = function () {
    pending.value = true;
    link.value = "/dashboard/exam/";
    axios
        .get("/dashboard/search", {
            params: {
                dataSearch: search.value,
            },
        })
        .then(function (val) {
            if (val.data.message == undefined) {
                pending.value = false;
                link.value = link.value+val.data.id;
                isOpenLink.value = true;
            } else {
                pending.value = false;
                isOpenAlert.value = true;
                message.value = val.data.message;
            }
        });
};

const check = computed(() => {
    console.log(props.auth.user.name);
});
</script>
