<template>
	<div
		class="h-screen w-full fixed"
		style="z-index: 999999999"
		v-show="disableScreen"
	></div>
	<div class="md:px-32 md:py-24 max-md:px-16 max-md:py-20">
		<div
			v-for="(question, index) in data.history.question"
			:key="index"
			v-show="index == selected"
		>
			<div class="border-b-4 border-gray-700 p-5 mb-10">
				<div class="flex justify-between">
					<div class="text-xl font-semibold">Soal {{ index + 1 }}</div>
					<div>{{ countdown }}</div>
				</div>
				<br />
				<div
					v-for="(questionData, indexData) in question.question_data"
					:key="indexData"
				>
					<p v-if="questionData.question_data.type == 'paragraph'">
						{{ questionData.question_data.data }}
					</p>
					<div v-else class="flex justify-center">
						<div class="border p-3 rounded">
							<img
								:src="
									questionData.question_data.question_attachment.path +
									questionData.question_data.question_attachment.filename
								"
							/>
						</div>
					</div>
				</div>
				<div class="mt-5 flex justify-between">
					<button
						class="flex items-center cursor-pointer text-red-600 hover:scale-105 duration-150 select-none"
						@click="selected--"
						:disabled="selected == 0"
						:class="{
							'opacity-50 cursor-not-allowed': selected == 0,
						}"
					>
						<ArrowBackSharp class="w-5 mr-2" />
						<p>Sebelumnya</p>
					</button>
					<button
						class="flex items-center cursor-pointer text-red-600 hover:scale-105 duration-150 select-none"
						:class="{
							hidden: selected == props.data.history.question.length - 1,
						}"
						@click="selected++"
					>
						<p>Selanjutnya</p>
						<ArrowForward class="w-5 ml-2" />
					</button>
					<button
						class="flex items-center cursor-pointer text-red-600 hover:scale-105 duration-150"
						:class="{
							hidden: selected != props.data.history.question.length - 1,
						}"
						@click="showOver = !showOver"
					>
						<p>Selesai</p>
					</button>
				</div>
			</div>
			<div class="px-10">
				<label v-for="(choice, indexChoice) in question.choice">
					<div
						class="border-y-2 border-gray-300 p-5 flex items-center mb-5 cursor-pointer hover:bg-gray-100 select-none"
						:key="indexChoice"
						:for="`radio${indexChoice}`"
					>
						<input
							type="radio"
							class="radio radio-error"
							:name="`radio${index}`"
							@change="answer(choice.id, question.id)"
							:checked="
								question.answer != null
									? question.answer.choice_id == choice.id
									: false
							"
						/>
						<div class="p-5">
							<p v-if="choice.choice.choice != null">
								{{ choice.choice.choice }}
							</p>
							<div
								v-if="choice.choice.choice_attachment != null"
								class="flex justify-center"
							>
								<div class="border p-3 rounded">
									<img
										:src="
											choice.choice.attachment.path +
											choice.choice.attachment.filename
										"
										class="hover:scale-125"
									/>
								</div>
							</div>
						</div>
					</div>
				</label>
			</div>
		</div>
	</div>
	<div
		class="border border-gray-300 fixed right-0 top-[20%] md:w-20 max-md:w-14"
	>
		<div class="h-[15rem] px-2 py-3 overflow-auto asd">
			<div
				class="border h-fit text-center w-full py-2 mb-2 cursor-pointer hover:bg-red-600 hover:text-white select-none"
				v-for="(question, i) in data.history.question"
				:id="i"
				:class="[
					{ 'bg-gray-700 text-white': question.answer != null },
					{
						'bg-amber-400 text-black':
							question.pin == null ? false : i == selected ? false : true,
					},
					{ 'bg-red-600 text-white': i == selected },
				]"
				@click="selectedMethod"
			>
				{{ i + 1 }}
			</div>
		</div>
		<button
			class="border flex justify-center py-3 bg-white hover:bg-red-600 hover:text-white cursor-pointer select-none w-full"
			@click="selected--"
			:disabled="selected == 0"
			:class="{ 'opacity-50 cursor-not-allowed': selected == 0 }"
		>
			<ArrowBackCircle class="w-5 ml-2" style="margin: 0px" />
		</button>
		<button
			class="border flex justify-center py-3 bg-white hover:bg-red-600 hover:text-white cursor-pointer select-none w-full"
			@click="selected++"
			:class="{
				hidden: selected == props.data.history.question.length - 1,
			}"
		>
			<ArrowForwardCircle class="w-5 ml-2" style="margin: 0px" />
		</button>
		<button
			class="border flex justify-center py-3 bg-white hover:bg-red-600 hover:text-white cursor-pointer select-none w-full"
			@click="showOver = !showOver"
			:class="{
				hidden: selected != props.data.history.question.length - 1,
			}"
		>
			Selesai
		</button>
		<div
			class="border flex justify-center items-center py-3 bg-white hover:bg-amber-400 hover:text-white cursor-pointer select-none w-full"
			@click="pin"
			:class="{
				'bg-amber-400': data.history.question[selected].pin != null,
			}"
		>
			<Pin v-show="data.history.question[selected].pin == null" class="w-5" />
			<Pinned
				v-show="data.history.question[selected].pin != null"
				class="w-5"
			/>
		</div>
	</div>
	<ModalBoolFinish
		:data="data"
		:overForce="overForce"
		:show="showOver"
		@accept="over"
		@decline="decline"
	/>
</template>
<script type="text/javascript" setup>
import {
	ArrowForward,
	ArrowBackSharp,
	ArrowBackCircle,
	ArrowForwardCircle,
} from "@vicons/ionicons5";
import { Pin, Pinned } from "@vicons/tabler";
// import { ref, watch } from 'vue'
import { router } from "@inertiajs/inertia-vue3";
// import Notification from "@/Components/notification/index.vue";
import ModalBoolFinish from "@/Components/modal/modalBoolFinish.vue";
import { ref, watch, computed } from "vue";

const props = defineProps({
	data: Object,
});
const disableScreen = ref(false);
// console.log(props.data.exam.detected);
const selected = ref(0);
const data = ref(props.data);

function selectedMethod(e) {
	selected.value = parseInt(e.target.id);
}
const constantMinutes = ref(props.data.endToken - Date.now());
const count = ref(parseInt(constantMinutes.value / 1000));
const countdown = ref("");
const minutes = ref(parseInt(count.value / 60));
const second = ref(count.value - minutes.value * 60);
const repeatSet = computed(() => {
	return props.data.endToken - Date.now();
});
const setting = setInterval(function () {
	constantMinutes.value = repeatSet;
	if (second.value == 0) {
		minutes.value = minutes.value - 1;
		second.value = 59;
	}
	countdown.value = `${
		String(minutes.value).length == 1 ? "0" + minutes.value : minutes.value
	}:${String(second.value).length == 1 ? "0" + second.value : second.value}`;
	// console.log(countdown.value);

	second.value--;
	if (second.value <= 0 && minutes.value <= 0) {
		clearInterval(setting);
		over();
	}
}, 1000);
const answer = (choice, question) => {
	axios
		.post(route("getDataTokenChoice", [props.data.exam_id, props.data.token]), {
			choice: choice,
			historyQuestionId: question,
		})
		.then((output) => {
			data.value = output.data;
			console.log(data.value);
		});
};
const over = () => {
	disableScreen.value = true;
	window.location.href = route("tokenOver", [
		props.data.exam_id,
		props.data.token,
	]);
};
const showOver = ref(false);
const decline = () => {
	showOver.value = false;
};
const overForce = ref(true);
watch(
	data,
	function () {
		const arr = ref([]);
		for (let index in data.value.history.question) {
			if (data.value.history.question[index].answer != null) {
				arr.value.push("true");
			} else {
				arr.value.push("false");
			}
		}
		overForce.value = arr.value.includes("false");
	},
	{ immediate: true }
);
function pin() {
	const pin = data.value.history.question[selected.value];
	axios
		.post(route("tokenPin", [props.data.exam_id, props.data.token]), {
			val: pin,
		})
		.then((output) => {
			data.value = output.data;
		});
}
const fail = ref(0);
window.addEventListener("blur", function () {
		fail.value++
	});
watch(fail, () => {
	if (fail.value == 3) {
		over();
	}
});
</script>
<style>
.asd::-webkit-scrollbar {
	width: 3px;
}
.asd::-webkit-scrollbar-track {
	background: white;
}
.asd::-webkit-scrollbar-thumb {
	background: #888;
}
.asd::-webkit-scrollbar-thumb:hover {
	background: #555;
}
</style>
