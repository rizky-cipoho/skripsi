<template>
    <div class="bg-white text-black">
        <Navbar :user="props.authNow" :ziggy="props.ziggy" />
        <div class="pt-10 px-10 text-gray-700 md:flex md:flex-warp">
            <div
                class="shadow px-5 py-5 bg-white md:flex md:flex-warp md:w-9/12 rounded"
            >
                <div
                    class="md:w-6/12 flex max-md:justify-center items-center px-5 max-md:py-5"
                >
                    <div
                        class="rounded-full w-24 h-24 bg-cover bg-center"
                        :style="`background-image:url('${props.authNow.attachment.path}${props.authNow.attachment.filename}')`"
                    ></div>
                    <div class="ml-3 pl-3">
                        <p class="text-sm leading-none">
                            {{ props.authNow.name }}
                        </p>
                        <small class="leading-none"> Tier. {{ tier }} </small>
                        <p class="leading-none text-lg font-semibold">
                            Poin. {{ props.point }}
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
                            class="shadow border border-gray-300 rounded focus:border-gray-500 focus:outline-none py-1 px-2 text-sm block bg-white"
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
        <div class="flex justify-center">
            <input
                class="input input-bordered w-6/12 my-5 text-center"
                placeholder="Cari"
                @click="searchClick"
                data-theme="light"
                readonly
            />
        </div>
        <div class="pb-32">
            <RowExamRecommendation
                :recommendationsData="recommendationsData"
                :favorite="props.favorite"
                :tier="tier"
                class="rounded"
            />
            <RowExam
                v-for="seconds in props.seconds"
                :seconds="seconds"
                :tier="tier"
                class="rounded"
            />
        </div>
        <Modal :show="isOpen" @closeModal="closeModal" />
        <ModalLink
            :show="isOpenLink"
            :search="search"
            :link="link"
            @closeModal="closeModal"
        />
        <ModalAlert
            :show="isOpenAlert"
            :search="search"
            :message="message"
            @closeModal="closeModal"
        />
        <ModalSearch :show="searchBool" @closeModal="closeModal" />
    </div>
</template>

<script setup>
import Navbar from "@/Components/navbar.vue";
import { Head, Link, useForm, router } from "@inertiajs/inertia-vue3";
import { ref, watch, computed } from "vue";
import { BookmarksSharp, DocumentTextOutline } from "@vicons/ionicons5";
import Modal from "@/Components/modal/modal.vue";
import ModalLink from "@/Components/modal/modalLink.vue";
import ModalAlert from "@/Components/modal/modalAlert.vue";
import ModalSearch from "@/Components/modal/modalSearch.vue";
import RowExam from "@/Components/dashboard/rowExam.vue";
import RowExamRecommendation from "@/Components/dashboard/RowExamRecommendation.vue";

const props = defineProps({
    resultExam: Number,
    favorite: String,
    authNow: Object,
    recommendations: Object,
    seconds: Object,
    ziggy: Object,
    point: Number,
});
let form = useForm({
    exam: null,
});
const message = ref("");
const isOpen = ref(false);
const isOpenLink = ref(false);
const isOpenAlert = ref(false);
const search = ref("");

let open = ref(false);
const input = ref({
    search: "",
});

let cardData = ref([]);
const recommendationsData = ref([]);
const dataReverse = () => {
    return new Promise((result) => {
        result(recommendationsData.value.push(...props.recommendations));
    });
};
watch(
    recommendationsData.value,
    () => {
        recommendationsData.value = [];
        const reverse = dataReverse();
        reverse.then((arr) => recommendationsData.value.slice(1, 4).reverse());
    },
    {
        immediate: true,
    }
);
const closeModal = (val) => {
    if (val != null) {
        search.value = val.value;
    }
    isOpen.value = false;
    isOpenAlert.value = false;
    isOpenLink.value = false;
    searchBool.value = false;
};
function openModal() {
    isOpen.value = true;
}
const pending = ref(false);
const link = ref("/dashboard/exam/else/");
const searchAxios = function () {
    pending.value = true;
    axios
        .get("/dashboard/search", {
            params: {
                dataSearch: search.value,
            },
        })
        .then(function (val) {
            if (val.data.message == undefined) {
                link.value = "/dashboard/exam/else/";
                pending.value = false;
                link.value = link.value + val.data.id;
                isOpenLink.value = true;
            } else {
                pending.value = false;
                isOpenAlert.value = true;
                message.value = val.data.message;
            }
        });
};

const searchBool = ref(false);
const rules = ref(false);
const searchClick = () => {
    searchBool.value = true;
};
let tier = ref("");
let dateTier = new Date(props.authNow.birth).getFullYear();
let now = new Date();
let nowCountSD = now.getFullYear();
let nowCountSMP = now.getFullYear() - 13;
let nowCountSMA = now.getFullYear() - 16;
let nowCountMahasiswa = now.getFullYear() - 17;
for (let i = 0; i < 13; i++) {
    nowCountSD--;
    if (nowCountSD == dateTier) {
        tier.value = "SD";
        break;
    }
}
for (let i = 0; i < 3; i++) {
    nowCountSMP--;
    if (nowCountSMP == dateTier) {
        tier.value = "SMP";
        break;
    }
}
for (let i = 0; i < 3; i++) {
    nowCountSMA--;
    if (nowCountSMA == dateTier) {
        tier.value = "SMA";
        break;
    }
}
if (tier.value.length == 0) {
    tier.value = "Mahasiswa";
}
</script>
