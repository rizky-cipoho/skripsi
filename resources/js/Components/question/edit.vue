<template>
	<div
		id="editorjs"
		class="h-fit border border-gray-300 shadow-md rounded-xl py-10"
	></div>
</template>
<script type="text/javascript" setup>
import EditorJS from "@editorjs/editorjs";
import Header from "@editorjs/header";
import Checklist from "@editorjs/checklist";
import ImageTool from "@editorjs/image";
import { ref, watch, toRef } from "vue";
import { router } from "@inertiajs/inertia-vue3";

const emit = defineEmits(["pendingSave", "removeQuestion"]);
function pending() {
	emit("pendingSave");
}
const props = defineProps({
	exam: Object,
	selected: Number,
	questions: Object,
	idQuestion: String,
	saveQuestionCount: Number,
	removeQuestion: Number,
	selectedTrigger: Number,
});
console.log(props.questions);

const dataPropsQuestions = ref([...props.questions]);
let data = ref([
	{
		question_id: "",
		blocks: [],
	},
]);
// console.log(dataPropsQuestions.value[props.selected].question_data[0])
if (dataPropsQuestions.value[props.selected].question_data[0] != undefined) {
	data.value[0].question_id = "";
	data.value[0].blocks = [];
	data.value[0].question_id =
		dataPropsQuestions.value[props.selected].question_data[0].question_id;
	for (let dataText in dataPropsQuestions.value[props.selected].question_data) {
		if (
			dataPropsQuestions.value[props.selected].question_data[dataText].type ==
			"image"
		) {
			data.value[0].blocks.push({
				type: dataPropsQuestions.value[props.selected].question_data[dataText]
					.type,
				id: dataPropsQuestions.value[props.selected].question_data[dataText].id,
				data: {
					file: {
						url:
							dataPropsQuestions.value[props.selected].question_data[dataText]
								.question_attachment.path +
							dataPropsQuestions.value[props.selected].question_data[dataText]
								.question_attachment.filename,
					},
				},
			});
		} else {
			data.value[0].blocks.push({
				type: dataPropsQuestions.value[props.selected].question_data[dataText]
					.type,
				id: dataPropsQuestions.value[props.selected].question_data[dataText].id,
				data: {
					text: dataPropsQuestions.value[props.selected].question_data[dataText]
						.data,
				},
			});
		}
	}
} else {
	data.value[0].blocks.push({
		id: "",
		type: "paragraph",
		data: {
			text: "",
		},
	});
}

const csrfToken = document
	.querySelector('meta[name="csrf-token"]')
	.getAttribute("content");
const editor = new EditorJS({
	holder: "editorjs",
	tools: {
		header: Header,
		image: {
			class: ImageTool,
			config: {
				additionalRequestHeaders: {
					"X-CSRF-TOKEN": document
						.querySelector('meta[name="csrf-token"]')
						.getAttribute("content"),
					field: "PATCH",
				},
				endpoints: {
					byFile: route("addQuestionImage", [
						props.exam.id,
						dataPropsQuestions.value[props.selected].question_data[0]
							.question_id,
					]),
				},
			},
		},
		checklist: {
			class: Checklist,
			inlineToolbar: true,
		},
	},
	// autofocus: true,
	data: {
		...data.value[props.selected],
	},
	placeholder: "Tulis Soal",
});
const selected = toRef(props, "selected");
const selectedDuplicate = ref(props.selected);
const idQuestionDuplicate = ref(props.idQuestion);
watch(selected, () => {
	editor.isReady.then(() => {
		console.log("isReady");
		// console.log(idQuestionDuplicate.value);
		pending();
		editor
			.save()
			.then((outputData) => {
				console.log(outputData);
				axios
					.post(route("questionUpdate", props.exam.id), {
						data: outputData,
						count: selectedDuplicate.value,
						idQuestion: idQuestionDuplicate.value,
					})
					.then((outputText) => {
						dataPropsQuestions.value = [];
						dataPropsQuestions.value.push(...outputText.data);
						selectedDuplicate.value = props.selected;
						idQuestionDuplicate.value = props.idQuestion;
						pending();
					});
			})
			.catch((error) => {
				selectedDuplicate.value = props.selected;
				idQuestionDuplicate.value = props.idQuestion;
				console.log("Saving failed: ", error);
			});
		editor.render(data.value[0]);
	});
	// console.log(dataPropsQuestions.value[props.selected].question_data);
	if (dataPropsQuestions.value[props.selected].question_data[0] != undefined) {
		data.value[0].question_id = "";
		data.value[0].blocks = [];
		data.value[0].question_id =
			dataPropsQuestions.value[props.selected].question_data[0].question_id;
		for (let dataText in dataPropsQuestions.value[props.selected]
			.question_data) {
			if (
				dataPropsQuestions.value[props.selected].question_data[dataText].type ==
				"image"
			) {
				data.value[0].blocks.push({
					type: dataPropsQuestions.value[props.selected].question_data[dataText]
						.type,
					id: dataPropsQuestions.value[props.selected].question_data[dataText]
						.id,
					data: {
						file: {
							url:
								dataPropsQuestions.value[props.selected].question_data[dataText]
									.question_attachment.path +
								dataPropsQuestions.value[props.selected].question_data[dataText]
									.question_attachment.filename,
						},
					},
				});
			} else {
				data.value[0].blocks.push({
					type: dataPropsQuestions.value[props.selected].question_data[dataText]
						.type,
					id: dataPropsQuestions.value[props.selected].question_data[dataText]
						.id,
					data: {
						text: dataPropsQuestions.value[props.selected].question_data[
							dataText
						].data,
					},
				});
			}
		}
	} else {
		data.value[0].blocks.push({
			id: "",
			type: "paragraph",
			data: {
				text: "",
			},
		});
	}
	// console.log(data.value[0]);
});
let questionsD = toRef(props, "questions");
watch(questionsD, () => {
	// console.log(questionsD.value);
	dataPropsQuestions.value = [];
	dataPropsQuestions.value.push(...props.questions);
	// console.log(dataPropsQuestions.value);
});
const saveQuestionCountToRef = toRef(props, "saveQuestionCount");
watch(saveQuestionCountToRef, () => {
	editor.isReady.then(() => {
		pending();
		editor.save().then((outputData) => {
			axios
				.post(route("questionUpdate", props.exam.id), {
					idQuestion: props.idQuestion,
					data: outputData,
				})
				.then((output) => {
					pending();
				})
				.catch((error) => {
					pending();
				});
		});
	});
});
const removeQuestionCountToRef = toRef(props, "removeQuestion");
watch(removeQuestionCountToRef, () => {
	editor.isReady.then(() => {
		pending();
		editor.save().then((outputData) => {
			axios
				.post(route("removeQuestion", props.exam.id), {
					idQuestion: props.idQuestion,
					data: outputData,
				})
				.then((output) => {
					emit("removeQuestion", output.data);
					dataPropsQuestions.value = [];
					data.value[0].question_id = null;
					data.value[0].blocks = [];
					dataPropsQuestions.value.push(...output.data);
					data.value[0].question_id =
			dataPropsQuestions.value[props.selected].question_data[0].question_id;
		for (let dataText in dataPropsQuestions.value[props.selected]
			.question_data) {
			if (
				dataPropsQuestions.value[props.selected].question_data[dataText].type ==
				"image"
			) {
				data.value[0].blocks.push({
					type: dataPropsQuestions.value[props.selected].question_data[dataText]
						.type,
					id: dataPropsQuestions.value[props.selected].question_data[dataText]
						.id,
					data: {
						file: {
							url:
								dataPropsQuestions.value[props.selected].question_data[dataText]
									.question_attachment.path +
								dataPropsQuestions.value[props.selected].question_data[dataText]
									.question_attachment.filename,
						},
					},
				});
			} else {
				data.value[0].blocks.push({
					type: dataPropsQuestions.value[props.selected].question_data[dataText]
						.type,
					id: dataPropsQuestions.value[props.selected].question_data[dataText]
						.id,
					data: {
						text: dataPropsQuestions.value[props.selected].question_data[
							dataText
						].data,
					},
				});
			}
		}
		editor.render(data.value[0]);

		console.log(data.value)
					pending();
				})
				.catch((error) => {
					pending();
				});
		});
	});
	
});
</script>
