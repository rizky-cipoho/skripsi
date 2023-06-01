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
    <div class="flex min-h-full items-center justify-center p-4 text-center">
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
       <DialogTitle class="font-semibold">{{ props.title }}</DialogTitle>
       <div class="mt-3">
                <input
         type="text"
         name=""
         class="shadow border border-gray-200 rounded py-1 px-2 w-full"
         v-model="input"
         placeholder=""
         v-if="props.title != 'Ubah Deskripsi Ujian'"
        />
        <textarea v-else v-model="input" class="textarea textarea-bordered w-full" style=" resize: none"></textarea>
        <div class="flex justify-end">
         <button
          class="shadow rounded text-white bg-red-600 px-2 py-1 mt-3 active:bg-gray-900 hover:bg-gray-600 transition duration-150 ease-in-out select-none"
          @click="changeInput"
          :disabled="props.pending"
         >
          {{ props.pending ? "Pending" : "Ubah" }}
         </button>
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

const props = defineProps({
 show: Boolean,
 input: String,
 pending: Boolean,
 title: String
});
const input = ref(props.input);
const lesson = ref(1);
const emit = defineEmits(["closeModal", "changeInput"]);
const deActive = (val) => {
 emit("closeModal");
};
const changeInput = () => {
 emit("changeInput", input.value);
};

</script>
