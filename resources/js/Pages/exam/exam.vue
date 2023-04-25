<template>
    <div>
        <Navbar :name="props.auth.user.name" :ziggy="props.ziggy" />
    </div>
    <div class="mx-16 mb-20">
        <br />
        <button
            class="bg-red-600 text-white py-1 mt-2 px-2 rounded font-semibold hover:bg-gray-700 focus:outline-none tracking-widest active:bg-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 inline-flex block mb-1"
            @click="isOpen = !isOpen"
        >
            Buat Ujian
        </button>
        <br />
        <br />
        <p class="text-xl">Ujian Saya</p>
        <br />
        <div class="flex grid md:grid-cols-4 max-md:grid-cols-2 md:gap-10 max-md:gap-5 mb-20">
            
            <MyExam v-for="(exam, index) in exams" :exam="exam" />
            
        </div>
        <ModalExam
            :show="isOpen"
            :examName="examName"
            style="z-index: 9999"
            @closeModal="closeModal"
            @add="add"
            :pending="pending"
        />
        <ModalAlert :show="messageActive" :message="message" @closeModal="closeModal" style="z-index: 9999" />
    </div>
</template>
<script setup>
import Navbar from "@/Components/navbar.vue";
import MyExam from "@/Components/myExam/index.vue";
import ModalExam from "@/Components/modal/modalExam.vue";
import ModalAlert from "@/Components/modal/modalAlert.vue";
import { router } from "@inertiajs/inertia-vue3";
import { ref, watch, computed } from "vue";

const props = defineProps({
    exams: Object,
    lessons: Object,
    ziggy: Object,
    auth: Object
});
// console.log(props.exams )
const isOpen = ref(false);
const pending = ref(false);
const messageActive = ref(false);
const message = ref("");
const examName = ref("");
const lessonNumber = ref(1);
const exams = ref([]);
exams.value.push(...props.exams);

const add = (exam, lesson) => {
    examName.value = exam;
    pending.value = true;
    axios
        .get(route("examAdd"), {
            params: {
                examName: examName.value
            },
        })
        .then(function (val) {
            pending.value = false;
            exams.value = [];
            exams.value.push(...val.data);
            isOpen.value = false;
            message.value = "Ujian Berhasil Dibuat"
            // messageActive.value = true;
            // setTimeout(()=>messageActive.value = true, 1500)
            
        })
        .catch(function (val) {
            pending.value = false;
            isOpen.value = false;
            message.value = val.response.data.message;
            messageActive.value = true;
        });
};
const closeModal = () => {
    isOpen.value = false;
    messageActive.value = false;
};
</script>
