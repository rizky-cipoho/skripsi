<template>
	<div
		id="editorjs"
		class="border border-gray-300 rounded-lg py-10 max-md:px-5"
	></div>
	<!-- <button @click="check">check</button> -->
</template>
<script type="text/javascript" setup>
import EditorJS from "@editorjs/editorjs";
import Header from "@editorjs/header";
import Checklist from "@editorjs/checklist";
import ImageTool from "@editorjs/image";
import { ref, watch, toRef } from "vue";
import { router } from "@inertiajs/inertia-vue3";
import SimpleImage from "@/Composables/blockToolsImage.js";
import AlignmentTuneTool from "editorjs-text-alignment-blocktune";

const emit = defineEmits([
	"pendingSave",
	"removeQuestionApplyData",
	"questionsEmit",
	"pendingNoEffect",
	"questionDataSave",
	"save",
]);
function pending() {
	emit("pendingSave");
}
function pendingNoEffect() {
	emit("pendingNoEffect");
}

const props = defineProps({
	exam: Object,
	selected: Number,
	questions: Object,
	idQuestion: String,
	saveQuestionCount: Number,
	removeQuestion: Number,
	selectedTrigger: Number,
	questionTrigger: Number,
});

const dataPropsQuestions = ref([...props.questions]);
let data = ref([
	{
		question_id: "",
		blocks: [],
	},
]);

const csrfToken = document
	.querySelector('meta[name="csrf-token"]')
	.getAttribute("content");

const editor = new EditorJS({
	holder: "editorjs",
	tools: {
		paragraph: {
			inlineToolbar: false,
		},
		image: {
			class: ImageTool,
			config: {
				uploader: {
					uploadByFile(file) {
						let formData = new FormData();
						formData.append("image", file);
						return axios
							.post(
								route("addQuestionImage", [
									props.exam.id,
									dataPropsQuestions.value[props.selected]
										.question_data[0].question_id,
								]),
								formData,
								{
									headers: {
										"Content-Type": "multipart/form-data",
									},
								}
							)
							.then((output) => {
								emit("save");
								emit("questionsEmit", output.data.get);
								data.value[0].question_id = "";
								data.value[0].blocks = [];
								return output.data.question;
							})
							.then((output) => {
								dataSet();
								const imageData = {
									success: 1,
									file: {
										url: output.data.file.file.url,
									},
									withBackground: true,
								};
								return imageData;
							});
					},
				},
			},
		},
	},
	// autofocus: true,
	data: {
		...data.value[props.selected],
	},
	placeholder: "Tulis Soal",
	// onChange: (api, event) => {
	// 	data.value[0].question_id = "";
	// 	data.value[0].blocks = [];
	// 	autoSave();
	// },
});
const selected = toRef(props, "selected");
const selectedDuplicate = ref(props.selected);
const idQuestionDuplicate = ref(props.idQuestion);
watch(selected, () => {
	pending();
	axios
		.get(route("questionDataUpdate", props.exam.id))
		.then((outputText) => {
			emit("questionsEmit", outputText.data);
			selectedDuplicate.value = props.selected;
			idQuestionDuplicate.value = props.idQuestion;
			pending();
		});
	dataSet();
});
let questionsD = toRef(props, "questions");
watch(questionsD, () => {
	dataPropsQuestions.value = [];
	dataPropsQuestions.value.push(...props.questions);
});

function autoSave() {
	pendingNoEffect();
	editor.isReady.then(() => {
		editor.save().then((outputData) => {
			axios
				.post(route("questionUpdate", props.exam.id), {
					idQuestion: dataPropsQuestions.value[props.selected].id,
					data: outputData,
				})
				.then((output) => {
					emit("questionsEmit", output.data);
					// console.log(output.data)
					pendingNoEffect();
				})
				.catch((error) => {
					pendingNoEffect();
				});
		});
	});
}
const removeQuestionCountToRef = toRef(props, "removeQuestion");
watch(removeQuestionCountToRef, () => {
	editor.isReady.then(() => {
		pending();
		editor.save().then((outputData) => {
			axios
				.post(route("removeQuestion", props.exam.id), {
					idQuestion: dataPropsQuestions.value[props.selected].id,
					data: outputData,
				})
				.then((output) => {
					emit("removeQuestionApplyData");
					emit("questionsEmit", output.data);
					return output;
				})
				.then((output) => {
					data.value[0].question_id = "";
					data.value[0].blocks = [];
					return output;
				})
				.then((output) => {
					dataSet();
					pending();
				})
				.catch((error) => {
					pending();
				});
		});
	});
});
const check = () => {
	editor.isReady.then(() => {
		editor.save().then((outputData) => {});
	});
};
function saveData() {
	dataSet();

	editor.isReady.then(() => {
		editor.save().then((outputData) => {
			editor.render(data.value[0]);
		});
	});
}
function dataSet() {
	if (
		dataPropsQuestions.value[props.selected].question_data[0] != undefined
	) {
		data.value[0].question_id = "";
		data.value[0].blocks = [];
		data.value[0].question_id =
			dataPropsQuestions.value[
				props.selected
			].question_data[0].question_id;
		for (let dataText in dataPropsQuestions.value[props.selected]
			.question_data) {
			if (
				dataPropsQuestions.value[props.selected].question_data[dataText]
					.type == "image"
			) {
				data.value[0].blocks.push({
					type: dataPropsQuestions.value[props.selected]
						.question_data[dataText].type,
					id: dataPropsQuestions.value[props.selected].question_data[
						dataText
					].id,
					data: {
						file: {
							url:
								dataPropsQuestions.value[props.selected]
									.question_data[dataText].question_attachment
									.path +
								dataPropsQuestions.value[props.selected]
									.question_data[dataText].question_attachment
									.filename,
						},
						withBackground:
							dataPropsQuestions.value[props.selected]
								.question_data[dataText].question_attachment
								.withBackground,
					},
				});
			} else {
				data.value[0].blocks.push({
					type: dataPropsQuestions.value[props.selected]
						.question_data[dataText].type,
					id: dataPropsQuestions.value[props.selected].question_data[
						dataText
					].id,
					data: {
						text: dataPropsQuestions.value[props.selected]
							.question_data[dataText].data,
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
	editor.isReady.then(() => {
		editor.render(data.value[0]);
	});
}
dataSet();

const saveTrigger = toRef(props, "questionTrigger");
watch(saveTrigger, () => {
	editor.isReady.then(() => {
		editor.save().then((outputData) => {
			emit("questionDataSave", {
				data: outputData,
				idQuestion: dataPropsQuestions.value[props.selected].id,
			});
		});
	});
});
</script>
<style>
.ce-popover__item[data-item-name="withBorder"],
.ce-popover__item[data-item-name="stretched"] {
	display: none;
}
.image-tool__caption {
	display: none;
}
.image-tool--withBackground .image-tool__image {
	background: white !important;
}
</style>
