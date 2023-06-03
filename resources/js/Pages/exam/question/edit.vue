<template>
	<div
		class="h-screen w-full fixed"
		v-show="pendingSave"
		style="z-index: 99999"
	></div>
	<Navbar :user="props.auth.user" :ziggy="props.ziggy" />
	<div class="md:py-10 md:px-5">
		<Link
			class="flex items-center cursor-pointer hover:text-gray-700 w-fit text-red-600 max-md:py-3"
			as="button"
			:href="route('examSelected', props.exam.id)"
		>
			<ChevronLeft class="w-9" />
			<p>Kembali</p>
		</Link>
		<!-- <p class="ml-5 text-2xl font-semibold">Edit Soal</p> -->
		<div class="rounded md:px-5 py-5 md:flex w-full">
			<div class="w-full px-5">
				<Editor
					:exam="exam"
					:questions="question"
					:selected="selected"
					:idQuestion="idQuestion"
					:questionTrigger="questionTrigger"
					@pendingSave="pending"
					@save="save"
					@removeQuestionApplyData="removeQuestionApplyData"
					@questionDataSave="questionDataSave"
					:saveQuestionCount="saveQuestionCount"
					:removeQuestion="removeQuestion"
					:selectedTrigger="selectedTrigger"
					@questionsEmit="questionPush"
					@pendingNoEffect="pendingNoEffectEmits"
					class="max-md:mb-20"
				/>
				<Choice
					:questions="question"
					:selected="selected"
					:exam="exam"
					:choiceTrigger="choiceTrigger"
					@pending="pending"
					@save="save"
					@problemEmit="problemEmit"
					@pendingNoEffect="pendingNoEffectEmits"
					@choiceDataSave="choiceDataSave"
					@questionsEmit="questionPush"
					:class="{ 'max-md:hidden': !choiceMobile }"
					class="max-md:fixed max-md:top-0 max-md:bg-white max-md:h-screen max-md:w-full max-md:pr-10 max-md:transition max-md:duration-150"
					style="z-index: 9997"
				/>
			</div>
			<div class="md:w-3/12"></div>
			<Count
				:questions="question"
				:exam="exam"
				:problem="problem"
				@addQuestion="addQuestion"
				@examPush="examPush"
				@save="save"
				@questionPush="questionPush"
				@selected="selectedQuestion"
				@removeQuestion="questionDeleteShow"
				:savePending="savePending"
				:selected="selected"
				:class="{ 'max-md:hidden': !countMobile }"
				class="max-md:fixed max-md:top-0 max-md:left-0 max-md:w-full max-md:bg-white max-md:h-screen max-md:bg-opacity-90 max-md:py-10 max-md:transition max-md:duration-150 fixed right-0 px-5"
				style="z-index: 9997"
			/>
		</div>
		<button
			class="bg-red-600 w-16 h-16 rounded-full text-white font-black fixed bottom-[17%] right-[5%] hover:bg-gray-700 transition duration-150 active:scale-75 md:hidden"
			style="z-index: 9998"
			@click="choiceMobile = !choiceMobile"
			:class="{ 'max-md:hidden': countMobile }"
		>
			{{ !choiceMobile ? "ABC" : "Tutup" }}
		</button>
		<button
			class="bg-red-600 w-16 h-16 rounded-full text-white font-black fixed bottom-[5%] right-[5%] hover:bg-gray-700 transition duration-150 active:scale-75 md:hidden"
			style="z-index: 9998"
			@click="countMobile = !countMobile"
			:class="{ 'max-md:hidden': choiceMobile }"
		>
			{{ !countMobile ? "No." : "Tutup" }}
		</button>
		<Notification
			:show="pendingSave"
			:sign="'pending'"
			:text="'Pending'"
			style="z-index: 9999"
		/>
		<Notification
			:show="pendingNoEffect"
			:sign="'pending'"
			:text="'Pending'"
			style="z-index: 9999"
		/>
		<modalBoolNext :show="nextSelectionTrigger" @nextSelection="nextSelection" @decline="decline" :temporatySelected="temporatySelected" style="z-index: 9999" />
		<ModalBoolDelete :show="questionDelete" @next="removeQuestionData" @decline="declineQuestionDelete" style="z-index: 99999" />
	</div>
</template>

<script type="text/javascript" setup>
import Notification from "@/Components/notification/index.vue";
import Navbar from "@/Components/navbar.vue";
import modalBoolNext from "@/Components/modal/modalBoolNext.vue";
import ModalBoolDelete from "@/Components/modal/modalBoolDelete.vue";
import Editor from "@/Components/question/edit.vue";
import Choice from "@/Components/choice/edit.vue";
import { ChevronLeft } from "@vicons/tabler";
import Count from "@/Components/question/count.vue";
import { Head, Link, router } from "@inertiajs/inertia-vue3";
import { ref, watch } from "vue";

const props = defineProps({
	auth: Object,
	ziggy: Object,
	exam: Object,
	questions: Object,
	questionData: Object,
	questionId: Object,
});

let exam = ref(props.exam);
let question = ref([...props.questions.question]);
let problem = ref([...props.questions.problem]);
const selected = ref(0);
const choiceMobile = ref(false);
const countMobile = ref(false);
const saveQuestionCount = ref(0);
const idQuestion = ref(props.questionId);
const pendingSave = ref(false);
const pendingNoEffect = ref(false);
const selectedTrigger = ref(0);
const nextSelectionTrigger = ref(false);
const temporatySelected = ref();
const questionDelete = ref(false);
const addQuestion = () => {
	pending();
	axios.post(route("addQuestion", props.exam.id)).then((output) => {
		pending();
		questionPush(output.data);
		examPush(output.data.exam);
	});
};
watch(choiceMobile, function(){
	if (choiceMobile.value == false) {
		save();
	}
})
const removeQuestion = ref(0);
const selectedQuestion = (index, id) => {
	temporatySelected.value = index
	nextSelectionTrigger.value = true
};
function nextSelection(index, id){
	nextSelectionTrigger.value = false
	selected.value = index.index;
	idQuestion.value = index.id;
}
function decline(index, id){
	nextSelectionTrigger.value = false
}
// const selectedPromise = new Promise
const pending = () => {
	pendingSave.value = !pendingSave.value;
};
const pendingNoEffectEmits = () => {
	pendingNoEffect.value = !pendingNoEffect.value;
};
const saveQuestion = () => {
	saveQuestionCount.value = saveQuestionCount.value + 1;
};
function declineQuestionDelete(){
	questionDelete.value = false
}
function questionDeleteShow(){
	questionDelete.value = true
}
const removeQuestionData = () => {
	save();
	removeQuestion.value = removeQuestion.value + 1;
};
const removeQuestionApplyData = (output) => {
	selected.value = 0;
	idQuestion.value = question.value[0].id;
	selectedTrigger.value = selectedTrigger.value + 1;
	questionDelete.value = false
};
const questionPush = (val) => {
	question.value = [];
	question.value.push(...val.question);
	problem.value = [];
	problem.value.push(...val.problem);
};
const problemPush = (val) => {
	problem.value = [];
	problem.value.push(...val.problem);
};
const examPush = (val) => {
	exam.value = val;
};
const problemEmit = (val) => {
	problem.value = [];
	problem.value.push(...val.problem);
};
const savePending = ref(false);
const questionTrigger = ref(0);
const choiceTrigger = ref(0);
const questionResult = ref([]);
const choicePending = ref(false);
const questionPending = ref(false);

const questionDataSave = (val) => {
	questionResult.value.push(val);
	questionPending.value = false;
};
const choiceDataSave = (val) => {
	questionResult.value.push(val);
	choicePending.value = false;
};
watch(questionResult, () => {
	console.log(questionResult.value);
	pending();
	setTimeout(() => {
		axios
			.post(route("saveAll", props.exam.id), {
				val: questionResult.value,
			})
			.then((output) => {
				// emit("questionsEmit", output.data);
				questionPush(output.data);
				pending();
			})
			.catch((error) => {
				pending();
			});
	}, 500);
});

const save = () => {
	choicePending.value = true;
	questionPending.value = true;
	questionTrigger.value++;
	choiceTrigger.value++;
	setTimeout(() => {
		// console.log(questionResult.value);
	}, 500);
	questionResult.value = [];
};
// watch(selected, ()=>{
// 	save();
// })
</script>
