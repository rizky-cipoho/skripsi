<template>
	<div class="py-10 max-md:overflow-auto">
		<div class="flex flex-wrap">
			<div
				class="mb-5 px-3 w-6/12"
				v-for="(choice, index) in props.questions[props.selected]
					.choice"
				:key="index"
			>
				<div
					class="flex flex-wrap max-md:justify-center gap-2 w-full items-center mb-2"
				>
					<input
						type="radio"
						name="a"
						class="radio radio-error"
						:id="choice.id"
						v-on:change="key"
						:checked="choice.keys != null"
					/>
					<textarea
						type="text"
						class="md:w-11/12 max-md:w-full textarea textarea-bordered"
						placeholder="Masukkan nilai pilihan"
						v-debounce:300.once="submitKeyup"
						rows="3"
						style="resize: none"
						:id="choice.id"
						:title="choice.question_id"
						v-model.once="choice.choice"
					/>
				</div>
				<div class="flex">
					<div class="md:px-5">
						<input
							type="file"
							accept="image/png, image/gif, image/jpeg"
							class="file-input file-input-ghost w-full max-w-xs"
							@change="addImage"
							:id="choice.id"
						/>
					</div>
					<div class="px-1">
						<button
							v-if="choice.choice_attachment != null"
							class="md:btn max-md:text-white max-md:rounded max-md:hover:bg-gray-700 max-md:px-1 bg-red-600 border-none max-md:transition max-md:duration-150 max-md:active:scale-75"
							:id="choice.id"
							@click="removeImage"
						>
							Hapus Gambar
						</button>
					</div>
				</div>
				<div class="flex justify-center">
					<img
						:src="`${choice.attachment.path}${choice.attachment.filename}`"
						:id="choice.id"
						class="mt-1 p-3 border border-gray-300 rounded"
						v-if="choice.choice_attachment != null"
					/>
					<img
						src=""
						:id="choice.id"
						class="h-36 w-36 mt-5 p-3 border border-gray-300 rounded hidden"
						v-else
					/>
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript" setup>
import { ref, watch, toRef, reactive } from "vue";
// import { axiosComposable } from "@/Composables/axios.vue";

const props = defineProps({
	questions: Object,
	selected: Number,
	exam: Object,
	choiceMobile: Boolean,
});
console.log(props.questions);
const emit = defineEmits(["pending", "questionsEmit", "pendingNoEffect"]);
// const choice = ref("")
function submitKeyup(val, a) {
		emit("pendingNoEffect");
		axios
			.post(route("updateChoice", props.exam.id), {
				choice: val,
				id: a.target.id,
				question_id: a.target.title,
			})
			.then((output) => {
				emit("questionsEmit", output.data);
			})
			.then(() => {
				emit("pendingNoEffect");
			});
}
const choice = ref([]);
function addChoice() {
	emit("pending");
	axios
		.post(route("addChoice", props.exam.id), {
			params: props.questions[props.selected].id,
		})
		.then((output) => {
			emit("questionsEmit", output.data);
			emit("pending");
		});
}
function removeChoice(e) {
	emit("pending");
	axios
		.post(route("removeChoice", props.exam.id), {
			id: e.target.id,
		})
		.then((output) => {
			emit("questionsEmit", output.data);
			emit("pending");
		});
}
function addImage(e) {
	emit("pending");

	const url = URL.createObjectURL(e.target.files[0]);
	let formData = new FormData();

	document.querySelector(`img[id~="${e.target.id}"]`).src = url;
	document
		.querySelector(`img[id~="${e.target.id}"]`)
		.classList.remove("hidden");

	formData.append("file", e.target.files[0]);
	formData.append("id", e.target.id);
	axios
		.post(route("choiceAttachment", props.exam.id), formData, {
			headers: {
				"Content-Type": "multipart/form-data",
			},
		})
		.then((output) => {
			emit("questionsEmit", output.data);
			emit("pending");
		});
}
function key(e) {
	emit("pendingNoEffect");

	axios
		.post(route("keyChoice", props.exam.id), {
			id: e.target.id,
		})
		.then((output) => {
			emit("questionsEmit", output.data);
			emit("pendingNoEffect");
		});
}
function removeImage(e) {
	emit("pending");
	axios
		.post(route("removeImage", props.exam.id), {
			id: e.target.id,
		})
		.then((output) => {
			emit("questionsEmit", output.data);
			emit("pending");
		});
}
</script>
`
