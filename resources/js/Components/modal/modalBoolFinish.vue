<template>
    <TransitionRoot appear :show="props.show" as="template">
        <Dialog as="div" @click="decline" class="relative z-10">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black bg-opacity-25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div
                    class="flex min-h-full items-center justify-center p-4 text-center"
                >
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <div :class="{ 'mt-3': props.overForce == false }">
                                <p class="text-center">
                                    {{ props.overForce ? 'Semua soal belum di jawab' : 'Waktu masih tersisa, ingin menyelesaikan ujian?' }} 
                                    
                                </p>
                                <div class="flex justify-center" v-show="props.overForce == false">
                                    <div class="px-2">
                                        <button
                                            class="shadow rounded text-white bg-red-600 px-2 py-1 mt-3 active:bg-gray-900 hover:bg-gray-600 transition duration-150 ease-in-out select-none"
                                            @click="decline"
                                        >
                                            Tidak jadi
                                        </button>
                                    </div>
                                    <div class="px-2">
                                        <button
                                            class="shadow rounded text-white bg-red-600 px-2 py-1 mt-3 active:bg-gray-900 hover:bg-gray-600 transition duration-150 ease-in-out select-none"
                                            @click="accept"
                                        >
                                            {{ coundown == false ? 'Selesai' : duration <= 0 ? 'Selesai' : duration }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
<script type="text/javascript" setup>
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";
import { ref, watch, toRef } from "vue";

const props = defineProps({
    show: Boolean,
    overForce: Boolean,
    data: Object
});
const emit = defineEmits(["accept", "decline"]);
function accept() {
    emit("accept");
}
function decline() {
    coundown.value = false
    emit("decline");
}
const search = ref("");
// const emit = defineEmits(["closeModal"]);
// const decline = (val) => {
//     emit("closeModal", search);
// };
const show = toRef(props, "show");
const coundown = ref(false);
const duration = ref(5);
watch(show, ()=>{
    const arr = ref([])
    if (!props.overForce) {
        for(let index in props.data.history.question){
        const val = props.data.history.question[index].pin == null ? 'benar' : 'salah' 
        arr.value.push(val);
    }
    const arrBool = arr.value.includes('salah');
    if (arrBool == true) {
        coundown.value = true
    }
    }
})

watch(coundown, ()=>{
    if (coundown.value == true) {
        interval();
    }
})
const interval = ()=>{
    return setInterval(function(){
       duration.value--;
       if (duration.value == 0) {
        clearInterval(interval);
       } 
    },1000);
}
</script>
