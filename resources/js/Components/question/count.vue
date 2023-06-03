<template>
	<div class="md:w-3/12 pl-10 py-3 rounded" style="">
		<Problem :problem="props.problem" :count="'count'" />
		<div class="flex items-center">
			<p>pilihan</p>
			<div v-for="i in 5" class="p-2">
				<button
					class="px-2 py-1 border border-gray-400 rounded hover:bg-gray-700 hover:text-white font-semibold"
					:class="{
						'bg-red-600 text-white border-none':
							props.exam.choice == i + 1,
					}"
					:disabled="props.exam.choice == i + 1"
					@click="triggerModal(i + 1)"
					v-text="i + 1"
				></button>
			</div>
		</div>
		<div class="flex grid grid-cols-2 gap-2">
			<button
				class="btn w-full bg-red-600 border-none mb-2"
				@click="addQuestion"
			>
				Tambah Soal
			</button>
			<button
				class="btn w-full bg-red-600 border-none hover:bg-gray-700"
				@click="removeQuestion"
				:disabled="remove"
			>
				Hapus Soal
			</button>
		</div>
		<div>
			<button
				class="btn w-full bg-red-600 border-none hover:bg-gray-700 mb-3"
				@click="save"
				:disabled="savePending"
			>
				Save
			</button>
		</div>
		<div
			class="overflow-auto overflow-x-hidden max-md:max-h-[11rem] px-3"
			:class="[
				{ 'md:max-h-[7rem]': 0 < props.problem.length },
				{ 'md:max-h-[18rem]': 0 == props.problem.length },
			]"
		>
			<div class="flex grid md:grid-cols-4 max-md:grid-cols-5 gap-3">
				<button
					class="btn btn-outline active:border-gray-600 border border-gray-300"
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
		<Modal
			:show="modal"
			:choice="choice"
			@accept="lengthChoice"
			@decline="decline"
			style="z-index: 99999"
		/>
	</div>
</template>
<script type="text/javascript" setup>
import Problem from "@/Components/myExam/problem.vue";
import Modal from "@/Components/modal/modalBool.vue";
import { ref, watch, toRef } from "vue";
const props = defineProps({
	questions: Object,
	problem: Object,
	selected: Number,
	exam: Object,
	savePending: Boolean
});
const modal = ref(false);
let choice = ref(props.exam.choice);
function triggerModal(val) {
	choice.value = val;
	modal.value = true;
}
const emit = defineEmits([
	"addQuestion",
	"removeQuestion",
	"selected",
	"questionPush",
	"examPush",
	"save",
]);
let remove = ref(false);

const questionsToRef = toRef(props, "questions");
watch(
	questionsToRef,
	() => {
		if (questionsToRef.value.length == 1) {
			remove.value = true;
		} else {
			remove.value = false;
		}
	},
	{ immediate: true }
);

const addQuestion = () => {
	emit("save")
	emit("addQuestion")
};
const removeQuestion = () => {
	emit("removeQuestion")
};
const questionPush = () => emit("questionPush");
const selectedQuestion = (index, id) => emit("selected", { index, id });
function lengthChoice(val) {
	axios
		.post(route("choiceLength", props.exam.id), {
			length: val,
			questions: props.questions[props.selected].id,
		})
		.then((output) => {
			emit("questionPush", output.data);
			emit("examPush", output.data.exam);
		});
	modal.value = false;
}
function decline() {
	modal.value = false;
}
function save(){
	emit("save");
}
</script>
