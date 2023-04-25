<template>
	<div class="w-3/12 px-3 py-3 rounded" style="">
		<button
			class="btn w-full mb-2 border-none bg-gradient-to-r from-red-600 to-gray-700 hover:from-gray-700"
			@click="saveQuestion"
		>
			Save
		</button>
		<div class="flex grid grid-cols-2 gap-2 mb-5">
			<button
				class="btn w-full bg-red-600 border-none"
				@click="addQuestion"
			>
				Tambah Soal
			</button>
			<button
				class="btn w-full bg-gray-700 border-none hover:bg-red-600"
				@click="removeQuestion"
				:disabled="remove"
			>
				Hapus Soal
			</button>
		</div>
		<div class="overflow-scroll overflow-x-hidden max-h-[30rem] px-3">
			<div class="flex grid grid-cols-4 gap-3">
				<button
					class="btn btn-outline active:border-gray-600"
					v-for="(data, index) in props.questions"
					:key="index"
					:class="
						props.selected == index ? 'bg-red-600 text-white' : ''
					"
					@click="selectedQuestion(index, data.id)"
				>
					{{ index + 1 }}
				</button>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript" setup>
import { ref, watch, toRef } from "vue";
const props = defineProps({
	questions: Object,
	selected: Number,
});
const emit = defineEmits([
	"addQuestion",
	"removeQuestion",
	"selected",
	"saveQuestion",
]);
// console.log(1 == false);

const questionsToRef = toRef(props, "questions");
watch(questionsToRef, ()=>{
	if (questionsToRef.value.length == 1) {
		remove.value = true;
	}else{
		remove.value = false;
	}
})


const remove = ref(false);

const addQuestion = () => emit("addQuestion");
const removeQuestion = () => emit("removeQuestion");
const saveQuestion = () => emit("saveQuestion");
const selectedQuestion = (index, id) => emit("selected", { index, id });
</script>
