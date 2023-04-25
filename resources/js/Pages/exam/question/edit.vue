<template>
	<div
		class="h-screen w-full fixed"
		v-show="pendingSave"
		style="z-index: 99999"
	></div>
	<Navbar :name="props.auth.user.name" :ziggy="props.ziggy" />
	<div class="py-10 px-5">
		<p class="ml-5 text-2xl font-semibold">Edit Soal</p>
		<div class="rounded px-5 py-5 flex w-full">
			<div class="w-11/12 px-5">
				<Editor
					:exam="exam"
					:questions="question"
					:selected="selected"
					:idQuestion="idQuestion"
					@pendingSave="pending"
					@removeQuestion="removeQuestionApplyData"
					:saveQuestionCount="saveQuestionCount"
					:removeQuestion="removeQuestion"
					:selectedTrigger="selectedTrigger"
				/>
				<Choice />
			</div>
			<Count
				:questions="question"
				@addQuestion="addQuestion"
				@saveQuestion="saveQuestion"
				@selected="selectedQuestion"
				@removeQuestion="removeQuestionData"
				:selected="selected"
			/>
		</div>
		<Notification :show="pendingSave" :sign="'pending'" :text="'Pending'" />
	</div>
</template>

<script type="text/javascript" setup>
import Notification from "@/Components/notification/index.vue";
import Navbar from "@/Components/navbar.vue";
import Editor from "@/Components/question/edit.vue";
import Choice from "@/Components/choice/edit.vue";
import Count from "@/Components/question/count.vue";
import { ref, watch } from "vue";

const props = defineProps({
	auth: Object,
	ziggy: Object,
	exam: Object,
	questions: Object,
	questionData: Object,
	questionId: Object,
});
const question = ref([...props.questions]);
// console.log(props.questionId);
const selected = ref(0);
const saveQuestionCount = ref(0);
const idQuestion = ref(props.questionId);
const pendingSave = ref(false);
const selectedTrigger = ref(0);
const addQuestion = () => {
	pending();
	axios.post(route("addQuestion", props.exam.id)).then((output) => {
		pending();
		question.value = [];
		question.value.push(...output.data);
		// console.log(question.value);
	});
};
const removeQuestion = ref(0);
const selectedQuestion = (index, id) => {
	// console.log(index);

	selected.value = index.index;
	idQuestion.value = index.id;
};
const pending = () => {
	pendingSave.value = !pendingSave.value;
	// console.log(pendingSave.value);
};

const saveQuestion = () => {
	saveQuestionCount.value = saveQuestionCount.value + 1;
	// console.log(saveQuestionCount.value);
};
const removeQuestionData = () => {
	removeQuestion.value = removeQuestion.value + 1;
	// console.log(saveQuestionCount.value);
};
const removeQuestionApplyData = (output) => {
	question.value = [];
	question.value.push(...output);

	selected.value = 0;
	idQuestion.value = question.value[0].id;
	selectedTrigger.value = selectedTrigger.value + 1
};
</script>
