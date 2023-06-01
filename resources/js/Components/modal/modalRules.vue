<template>
    <TransitionRoot appear :show="props.show" as="template">
        <Dialog as="div" @click="deActive" class="relative z-10">
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
                            class="w-full max-w-5xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <div class="">
                                <div class="text-center py-2">
                                    <strong class="">Pemberitahuan Penting Peserta Ujian</strong>
                                </div>
                                <div>
                                    <p>Ujian ini bersifat sensitif terhadap kecurangan, jika peserta mengintip tab lain, aplikasi lain atau yang serupa dengan dua hal itu maka ujian akan langsung dinyatakan <strong>selesai</strong>.</p>
                                    <br>
                                    <div class="flex justify-center items-center">
                                        <img src="/image/screen.png" class="w-4/12 rounded border border-gray-200">
                                        <div>
                                            <ArrowForwardOutline class="w-10 mx-10" />
                                        </div>
                                        <img src="/image/screen2.png" class="w-4/12 rounded border border-gray-200">
                                    </div>
                                    <br>
                                    <div>
                                        Untuk lebih aman dalam mengerjaka ujian di harap peserta dapat menekan tombol <span class="bg-gray-200 px-1 rounded">F11</span> untuk menjalankan fullscreen, agar terhindar dari kesalahan yang tidak terduga dalam propses pegerjaan.
                                    </div>
                                    <div class="flex justify-end">
                                        <button
                                            class="shadow rounded text-white bg-red-600 px-2 py-1 mt-3 active:bg-gray-900 hover:bg-gray-600 transition duration-150 ease-in-out select-none"
                                            @click="deActive"
                                        >
                                            Tutup
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
import { ref, watch, computed } from "vue";
import { ArrowForwardOutline } from "@vicons/ionicons5";

const props = defineProps({
    show: Boolean,
});
const emit = defineEmits(["closeModal", "changeInput"]);
const deActive = (val) => {
    emit("closeModal");
};
const changeInput = () => {
    emit("changeInput", input.value);
};
</script>
