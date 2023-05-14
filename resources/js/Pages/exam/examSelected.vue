<template>
	<Navbar
		:name="props.auth.user.name"
		:ziggy="props.ziggy"
		:examSelected="examSelected"
	/>
	<div class="px-10">
		<!-- <div class="bg-green-500 mt-5 py-2 px-10 font-semibold text-white rounded">Ujian Siap</div> -->
	</div>
	<div class="px-10 py-5 flex justify-between">
		<div class="w-10/12 pr-10">
			<div class="px-5 py-5 bg-gray-100 rounded shadow">
				<div class="flex w-full">
					<div
						class="h-36 w-36 bg-cover rounded"
						style="background-image: url('/image/rasberry.jpg')"
					></div>
					<div class="px-5 w-10/12">
						<div class="flex items-center w-full">
							<div class="max-w-64">
								<p
									class="text-lg font-semibold textlimit mr-4 flex-auto w-auto"
								>
									{{ examData.exam }}
								</p>
							</div>

							<div>
								<CreateOutline
									class="w-4 text-red-600 hover:text-gray-700 cursor-pointer"
									@click="examNameChange = !examNameChange"
								/>
							</div>
						</div>
						<div class="flex items-center">
							<p class="text-sm font-semibold pt-1 select-none">
								{{ props.exam.uni_code }}
							</p>
							<CopyOutline
								class="w-4 mx-2 cursor-pointer text-red-600 hover:text-gray-900 active:scale-75"
								@click="copyText(props.exam.uni_code)"
							/>
						</div>

						<div class="pt-5 flex">
							<div class="">
								<div class="flex">
									<BookOutline class="w-4 text-red-600" />
									<small class="px-2"
										>{{
											props.historyCount
										}}
										Dikerjakan</small
									>
								</div>
								<div class="flex">
									<BookOutline class="w-4 text-red-600" />
									<small
										class="px-2"
										v-show="
											examData.lesson.lesson == 'Lainnya'
										"
										>{{ examData.lesson.lesson }} ({{
											examData.other
										}})</small
									>
									<small
										class="px-2"
										v-show="
											examData.lesson.lesson != 'Lainnya'
										"
										>{{ examData.lesson.lesson }}</small
									>
								</div>
							</div>
							<div class="">
								<div class="flex">
									<BookOutline class="w-4 text-red-600" />
									<small class="px-2">asdsadas</small>
								</div>
								<div class="flex">
									<BookOutline class="w-4 text-red-600" />
									<small class="px-2">asdsadas</small>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="grid grid-cols-2 flex justify-between mt-4 gap-3">
					<Link
						:href="route('questionEdit', examData.id)"
						as="button"
						class="shadow justify-center bg-red-600 text-white py-1 mt-2 px-2 rounded font-semibold hover:bg-gray-700 focus:outline-none tracking-widest active:bg-red-600 focus:shadow-outline-gray transition ease-in-out duration-150 inline-flex block mb-1"
					>
						<p>Edit Soal</p>
					</Link>
					<button
						class="shadow justify-center bg-gray-700 text-white py-1 mt-2 px-2 rounded font-semibold hover:bg-red-600 focus:outline-none tracking-widest active:bg-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 inline-flex block mb-1"
					>
						<p>Delete</p>
					</button>
				</div>
			</div>
			<div class="flex justify-center gap-5 my-10">
				<div class="flex items-center gap-2">
					<div class="bg-red-600 w-8 h-3"></div>
					<p>Berhasil</p>
				</div>
				<div class="flex items-center gap-2">
					<div class="bg-gray-700 w-8 h-3"></div>
					<p>Gagal</p>
				</div>
			</div>
			<div class="flex justify-center">
				<Chart
					type="pie"
					:data="chartData"
					:options="chartOptions"
					class="w-3/12"
				/>
				<Chart
					type="pie"
					:data="chartData"
					:options="chartOptions"
					class="w-3/12"
				/>
			</div>

			<Question
				v-for="(question, index) in props.exam.question"
				:question="question"
				:key="index"
				:index="index"
			/>
		</div>

		<div class="text-center w-3/12">
			<Problem :problem="problem" />
			<div>
				<p class="font-bold text-lg">Pelajaran</p>
				<form>
					<div class="h-44 overflow-y-scroll my-4">
						<div
							class="flex items-center hover:bg-gray-100 cursor-pointer px-3 rounded"
							v-for="(lesson, index) in props.lesson"
						>
							<input
								type="radio"
								:id="lesson.id"
								name="lesson"
								class="w-4 cursor-pointer"
								:value="lesson.id"
								v-model="selected"
							/>
							<label
								class="px-2 w-full py-2 cursor-pointer text-left select-none"
								:for="lesson.id"
								>{{ lesson.lesson }}</label
							>
						</div>
					</div>
					<div
						class="flex items-center hover:bg-gray-100 cursor-pointer px-3 rounded"
					>
						<input
							type="radio"
							:id="props.lessonOther.id"
							name="lesson"
							class="w-4 cursor-pointer"
							:value="props.lessonOther.id"
							v-model="selected"
						/>
						<label
							class="px-2 w-full py-2 cursor-pointer text-left select-none"
							:for="props.lessonOther.id"
							>{{ props.lessonOther.lesson }}</label
						>
						<input
							type=""
							name=""
							class="w-36 border-b-2 border-gray-700 active:border-0 focus:border-0 rounded"
							@click="examlessonChange = !examlessonChange"
							v-model="other"
							readonly
						/>
					</div>
				</form>
			</div>
			<div class="mt-3">
				<p class="font-bold text-lg">Durasi ujian</p>
				<div
					class="flex flex-wrap py-2"
					v-for="(duration, index) in durations"
					:key="index"
				>
					<input
						class="radio radio-error w-1/12"
						type="radio"
						name="duration"
						:value="duration"
						v-model="durationTime"
					/>
					<p class="w-11/12">{{ duration }} Menit</p>
				</div>
			</div>
			<div class="mt-3">
				<p class="font-bold text-lg">Jam mulai ujian</p>
				<input
					class="checkbox"
					type="checkbox"
					id="start"
					:checked="startExamCheckBox"
					v-model="startExamCheckBox"
				/>
				<label class="px-2" for="start">Pakai Jam Mulai?</label>
				<div
					class="flex justify-center py-2"
					v-if="examData.time.start != null"
				>
					<input
						class=""
						type="datetime-local"
						:value="timeStart"
						@change="dateTime"
					/>
				</div>
			</div>
			<div class="mt-3">
				<p class="font-bold text-lg">Kunci ujian</p>
				<div>
					<input
						class="input border border-gray-300 text-center h-[2rem]"
						name=""
						v-model="key"
						readonly
						@click="keyModal = !keyModal"
					/>
				</div>
			</div>
			<div>
				<p class="text-lg font-bold py-5">Rekomendasi</p>
				<ImageLeft
					v-for="(a, index) in recommendations"
					:key="index"
					:exam="a"
				/>
			</div>
		</div>
	</div>
	<ModalExamChange
		:show="examNameChange"
		@changeInput="changeExam"
		@closeModal="closeModal"
		:pending="examNamePending"
		:input="examName"
		:title="'Ubah Nama Ujian'"
	/>
	<ModalExamChange
		:show="examlessonChange"
		@changeInput="selectedLesson"
		@closeModal="closeModal"
		:pending="selectedLessonPending"
		:input="other"
		:title="'Pelajaran Lainnya'"
	/>
	<ModalExamChange
		:show="keyModal"
		@changeInput="keySave"
		@closeModal="closeModal"
		:pending="keyPending"
		:input="key"
		:title="'Masukkan Kunci'"
	/>
	<Transition>
		<Notification
			:show="notification"
			:sign="'checklist'"
			:text="text"
			@notification="notificationStatus"
		/>
	</Transition>
	<Transition>
		<Notification
			:show="notificationExamNameChange"
			:sign="notificationExamNameChangeSign"
			:text="notificationExamNameChangeText"
			@notification="notificationStatus"
		/>
	</Transition>
</template>
<script setup>
import Navbar from "@/Components/navbar.vue";
import Problem from "@/Components/myExam/problem.vue";
import ImageLeft from "@/Components/card/imageLeft.vue";
import ModalExamChange from "@/Components/modal/modalExamChange.vue";
import Notification from "@/Components/notification/index.vue";
import Question from "@/Components/question/question.vue";
import { Link, router } from "@inertiajs/inertia-vue3";
import { BookOutline, CopyOutline, CreateOutline } from "@vicons/ionicons5";
import { ref, watch, onMounted } from "vue";
import Chart from "primevue/chart";
import { ExclamationMark } from "@vicons/tabler";
const props = defineProps({
	auth: Object,
	lesson: Object,
	ziggy: Object,
	exam: Object,
	recommendations: Object,
	historyCount: Number,
	lessonOther: Object,
	problem: Object,
});

const key = ref(props.exam.key);
const keyModal = ref(false);
const keyPending = ref(false);
function keySave(val) {
	keyPending.value = true;
	key.value = val;
	axios
		.post(route("examKey", props.exam.id), {
			val: val,
		})
		.then((output) => {
			key.value = output.data.key;
			console.log(output.data)
			keyPending.value = false;
			keyModal.value = false;
		})
		.catch(() => {
			keyPending.value = false;
			keyModal.value = false;
		});
}
const problem = ref([...props.problem]);

onMounted(() => {
	chartData.value = setChartData();
});
const examNameChange = ref(false);
const chartData = ref();
const chartOptions = ref({
	plugins: {
		legend: {
			labels: {
				usePointStyle: true,
			},
		},
	},
});

const setChartData = () => {
	return {
		datasets: [
			{
				data: [540, 325],
				backgroundColor: ["rgb(220 38 38)", "rgb(31 41 55)"],
				hoverBackgroundColor: ["rgb(239 68 68)", "rgb(17 24 39)"],
			},
		],
	};
};

const notification = ref(false);
const text = ref("Text Disalin");
const copyText = (val) => {
	navigator.clipboard.writeText(val);
	notification.value = true;
};

const examSelected = ref("Ujian saya");
const notificationStatus = () => {
	notification.value = false;
	notificationExamNameChange.value = false;
};
watch(notification, () => {
	if (
		notification.value == true ||
		notificationExamNameChange.value == true
	) {
		setTimeout(() => {
			notification.value = false;
			notificationExamNameChange.value = false;
		}, 2000);
	}
});

const notificationExamNameChange = ref(false);
const notificationExamNameChangeText = ref("");
const notificationExamNameChangeSign = ref("");
const examData = ref();
examData.value = props.exam;
const examNamePending = ref(false);
const examName = ref("");
watch(notificationExamNameChange, () => {
	if (
		notification.value == true ||
		notificationExamNameChange.value == true
	) {
		setTimeout(() => {
			notification.value = false;
			notificationExamNameChange.value = false;
		}, 2000);
	}
});
const changeExam = (val) => {
	examName.value = val;
	examNamePending.value = true;
	axios
		.get(route("changeExamName", props.exam.id), {
			params: {
				id: props.exam.id,
				examName: examName.value,
			},
		})
		.then((result) => {
			examNamePending.value = false;
			examNameChange.value = false;
			examData.value = null;
			examData.value = result.data;
			notificationExamNameChangeSign.value = "checklist";
			notificationExamNameChangeText.value = "Nama Ujian Berhasil Diubah";
			notificationExamNameChange.value = true;
		})
		.catch((result) => {
			examNamePending.value = false;
			examNameChange.value = false;
			notificationExamNameChangeSign.value = "error";
			notificationExamNameChangeText.value = "Nama Ujian gagal Diubah";
			notificationExamNameChange.value = true;
		});
};

const other = ref(examData.value.other);
const selectedLessonPending = ref(false);
const closeModal = () => {
	examNameChange.value = false;
	examlessonChange.value = false;
	keyModal.value = false;
};
const examlessonChange = ref(false);
const selected = ref(props.exam.lesson_id);

watch(selected, () => {
	selected.value = selected.value;
	selectedLesson();
});

const selectedLesson = (val) => {
	other.value = val;
	if (
		(typeof other.value === "object" || other.value === undefined) == false
	) {
		selected.value = props.lessonOther.id;
		selectedLessonPending.value = true;
		axios
			.get(route("changeExamLesson", props.exam.id), {
				params: {
					id: selected.value,
					other: other.value,
				},
			})
			.then((result) => {
				problem.value = [];
				problem.value.push(...result.data.problem);
				selectedLessonPending.value = false;
				examlessonChange.value = false;
				examData.value = null;
				examData.value = result.data.now;
				notificationExamNameChangeSign.value = "checklist";
				notificationExamNameChangeText.value =
					"Pelajaran Berhasil Diubah";
				notificationExamNameChange.value = true;
			})
			.catch((result) => {
				examNamePending.value = false;
				examlessonChange.value = false;
				notificationExamNameChangeSign.value = "error";
				notificationExamNameChangeText.value = "Pelajaran gagal Diubah";
				notificationExamNameChange.value = true;
			});
	} else {
		axios
			.get(route("changeExamLesson", props.exam.id), {
				params: {
					id: selected.value,
					other: null,
				},
			})
			.then((result) => {
				problem.value = [];
				problem.value.push(...result.data.problem);
				selectedLessonPending.value = false;
				examlessonChange.value = false;
				examData.value = null;
				examData.value = result.data.now;
				notificationExamNameChangeSign.value = "checklist";
				notificationExamNameChangeText.value =
					"Pelajaran Berhasil Diubah";
				notificationExamNameChange.value = true;
			})
			.catch((result) => {
				examNamePending.value = false;
				examlessonChange.value = false;
				notificationExamNameChangeSign.value = "error";
				notificationExamNameChangeText.value = "Pelajaran gagal Diubah";
				notificationExamNameChange.value = true;
			});
	}
};
const timeStart = ref();
const date = ref();
// console.log(examData.value.time.startTime);
watch(
	examData,
	() => {
		if (examData.value.time.start != null) {
			if (examData.value.time.start_time.start != null) {
				date.value = new Date(examData.value.time.start_time.start);
				const mounth = ref(date.value.getMonth());
				const minutes = ref(date.value.getMinutes());
				const datee = ref(date.value.getDate());
				const hours = ref(date.value.getHours());
				console.log(datee.value);
				const lengthMonth = ref(
					(mounth.value = mounth.value.toString().length)
				);
				const lengthMinutes = ref(
					(minutes.value = minutes.value.toString().length)
				);
				const lengthDate = ref(
					(datee.value = datee.value.toString().length)
				);
				const lengthHours = ref(
					(hours.value = hours.value.toString().length)
				);
				const sum = ref(date.value.getMonth() + 1);

				timeStart.value = `${date.value.getFullYear()}-${
					lengthMonth.value == 1
						? "0" + sum.value
						: date.value.getMonth() + 1
				}-${
					lengthDate.value == 1
						? "0" + date.value.getDate()
						: date.value.getDate()
				}T${
					lengthHours.value == 1
						? "0" + date.value.getHours()
						: date.value.getHours()
				}:${
					lengthMinutes.value == 1
						? "0" + date.value.getMinutes()
						: date.value.getMinutes()
				}`;
			} else {
				date.value = null;
			}
		} else {
			date.value = null;
		}
		console.log(timeStart.value);
	},
	{ immediate: true }
);
const dateTime = (e) => {
	setTimeout(function () {
		axios
			.post(route("timeStart", props.exam.id), {
				val: e.target.value,
			})
			.then((output) => {
				examData.value = output.data.exam;
				problem.value = [];
				problem.value.push(...output.data.problem);
			});
	}, 300);
};

const durations = ref([15, 30, 45, 60, 90, 120]);
let durationTime = ref(props.exam.time.duration);
watch(durationTime, () => {
	axios
		.post(route("timeDuration", examData.value.id), {
			val: durationTime.value,
		})
		.then((output) => {
			examData.value = output.data.exam;
			problem.value = [];
			problem.value.push(...output.data.problem);
		});
});
const startExamCheckBox = ref(examData.value.time.start != null ? true : false);
watch(startExamCheckBox, () => {
	axios
		.post(route("startExamCheckBox", examData.value.id), {
			val: startExamCheckBox.value,
		})
		.then((output) => {
			examData.value = output.data.exam;
			problem.value = [];
			problem.value.push(...output.data.problem);
		});
});
// timeStart.value =
</script>
<style>
.v-enter-active,
.v-leave-active {
	transition: opacity 0.3s ease;
}

.v-enter-from,
.v-leave-to {
	opacity: 0;
}
</style>
