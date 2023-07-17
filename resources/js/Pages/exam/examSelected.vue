<template>
	<div class="bg-white text-black">
		<Navbar
		:user="props.auth.user"
		:ziggy="props.ziggy"
		:examSelected="examSelected"
	/>
	<div class="px-10">
		<!-- <div class="bg-green-500 mt-5 py-2 px-10 font-semibold text-white rounded">Ujian Siap</div> -->
	</div>
	<div
		class="fixed bg-red-600 bottom-[5%] right-[5%] rounded-full p-3 hover:bg-gray-700 text-white hover:-rotate-90 transition duration-300 cursor-pointer md:hidden "
		@click="setting = !setting"
		style="z-index: 9999"
	>
		<SettingsOutline class="w-5" v-show="setting == false" />
		<CloseOutline class="w-5" v-show="setting == true" />
	</div>

	<div class="px-10 py-5 flex justify-between md:mt-5">
		<div class="md:w-10/12 md:pr-10 max-md:w-full">
			<div class="px-5 py-5 bg-gray-100 rounded shadow">
				<div class="md:flex w-full">
					<div>
						<div class="flex justify-center">
							<div
								class="h-36 w-36 bg-cover rounded bg-center hover:bg"
								:style="`background-image: url('${examData.attachment.path}${examData.attachment.filename}')`"
							></div>
						</div>
						<button
							class="shadow justify-center bg-red-600 hover:bg-gray-700 text-white mt-2 rounded font-semibold focus:outline-none tracking-widest active:bg-red-600 focus:shadow-outline-gray transition ease-in-out duration-250 inline-flex block mb-1 w-full mt-4 cursor-pointer"
						>
							<input
								type="file"
								name=""
								class="cursor-pointer opacity-0 pb-2 pt-1 w-36 bg-black absolute bg-white"
								accept="image/png, image/gif, image/jpeg"
								data-theme="light"
								@change="imageExam"
							/>
							<p class="py-2">Ganti Gambar</p>
						</button>
					</div>

					<div class="md:px-5 md:w-10/12 max-md:w-full">
						<div class="flex items-center w-full">
							<div class="max-w-64">
								<p
									class="text-lg font-semibold textlimit mr-4 flex-auto w-auto max-md:my-3"
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
									<Pencil class="w-4 text-red-600" />
									<small class="px-2" v-if="props.historyCount != 0"
										>{{ props.historyCount }}x Dikerjakan</small
									>
									<small class="px-2" v-else>Belum pernah di Dikerjakan</small>
								</div>
								<div class="flex">
									<BookOutline class="w-4 text-red-600" />
									<small
										class="px-2"
										v-show="examData.lesson.lesson == 'Lainnya'"
										>{{ examData.lesson.lesson }} ({{ examData.other }})</small
									>
									<small
										class="px-2"
										v-show="examData.lesson.lesson != 'Lainnya'"
										>{{ examData.lesson.lesson }}</small
									>
								</div>
								<div class="flex">
									<Analytics class="w-4 text-red-600" />
									<small class="px-2">Untuk {{ examData.tier }} </small>
								</div>
							</div>
							<div class="">
								<div class="flex">
									<KeyOutline class="w-4 text-red-600" />
									<small class="px-2">{{
										examData.key == null ? "Tidak Pakai Kunci" : "Pakai Kunci"
									}}</small>
								</div>
								<div class="flex">
									<TimeOutline class="w-4 text-red-600" />
									<small class="px-2">{{ examData.time.duration }} Menit</small>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div>
					<div class="flex justify-center px-5 py-2">
						<div
							class="p-5 text-center text-sm"
							v-if="examData.description != null"
						>
							{{ examData.description }}
						</div>
						<div class="p-5 text-center text-sm text-gray-400" v-else>
							Deskripsi Kosong
						</div>
						<div class="py-5">
							<CreateOutline
								class="w-4 text-red-600 hover:text-gray-700 cursor-pointer"
								@click="examDescriptionChange = !examDescriptionChange"
							/>
						</div>
					</div>
				</div>
				<div>
					<Link
						:href="route('myExamHistory', examData.id)"
						as="button"
						class="shadow justify-center bg-red-600 hover:bg-gray-700 text-white py-1 mt-2 px-2 rounded font-semibold focus:outline-none tracking-widest active:bg-red-600 focus:shadow-outline-gray transition ease-in-out duration-250 inline-flex block mb-1 w-full mt-4"
					>
						<p>Riwayat Ujian</p>
					</Link>
				</div>
				<div class="grid grid-cols-2 flex justify-between gap-3">
					<Link
						:href="route('questionEdit', examData.id)"
						as="button"
						class="shadow justify-center bg-red-600 text-white py-1 mt-2 px-2 rounded font-semibold hover:bg-gray-700 focus:outline-none tracking-widest active:bg-red-600 focus:shadow-outline-gray transition ease-in-out duration-150 inline-flex block mb-1"
					>
						<p>Edit Soal</p>
					</Link>
					<button
						class="shadow justify-center text-white py-1 mt-2 px-2 rounded font-semibold bg-red-600 hover:bg-gray-700 focus:outline-none tracking-widest active:bg-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 inline-flex block mb-1"
						@click="modalRemove = true"
					>
						<p>Delete</p>
					</button>
				</div>
			</div>
			<div>
				<div>
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
						<div class="w-3/12">
							<Chart
							type="pie"
							:data="chartData"
							:options="chartOptions"
							class=""
						/>
						</div>
					</div>
				</div>
			</div>

			<Question
				v-for="(question, index) in props.exam.question"
				:question="question"
				:session="props.session"
				:key="index"
				:index="index"
			/>
		</div>
		<div class="max-md:h-screen max-md:w-full max-md:bg-white max-md:fixed max-md:top-0 max-md:left-0 max-md:overflow-y-scroll max-md:p-20 md:w-3/12" 
		:class="{ 'max-md:hidden':setting == false }"
		>
			<div class="text-center md:w-full">
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
									class="w-6 cursor-pointer radio radio-error bg-white"
									:value="lesson.id"
									v-model="selected"
									data-theme="light"
									@change="selectedLesson"
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
								class="w-6 cursor-pointer radio radio-error bg-white"
								:value="props.lessonOther.id"
								@change="selectedLesson"
								v-model="selected"
								data-theme="light"
							/>
							<label
								class="px-2 w-full py-2 cursor-pointer text-left select-none"
								:for="props.lessonOther.id"
								>{{ props.lessonOther.lesson }}</label
							>
							<input
								type=""
								name=""
								class="w-36 border border-gray-300 active:border-0 focus:border-0 rounded bg-white"
								@click="examlessonChange = !examlessonChange"
								v-model="other"
								readonly
								data-theme="light"
							/>
						</div>
					</form>
				</div>
				<div class="mt-3">
					<p class="font-bold text-lg">Diperuntukkan Untuk</p>
					<div
						class="flex flex-wrap py-2"
						v-for="(tier, index) in tierExam"
						:key="index"
					>
						<input
							class="radio radio-error w-6 bg-white"
							type="radio"
							name="tier"
							:value="tier"
		data-theme="light"

							v-model="tierModel"
						/>
						<p class="w-10/12">Tingkat {{ tier }}</p>
					</div>
				</div>
				<div class="mt-3">
					<p class="font-bold text-lg">Durasi ujian</p>
					<div
						class="flex flex-wrap py-2 items-center"
						v-for="(duration, index) in durations"
						:key="index"
					>
						<input
							class="radio radio-error w-6 bg-white"
							type="radio"
							name="duration"
							:value="duration"
							data-theme="light"
							v-model="durationTime"
						/>
						<label class="w-10/12">{{ duration }} Menit</label>
					</div>
				</div>

				<div class="mt-3">
					<p class="font-bold text-lg">KKM Ujian</p>
					<div class="flex flex-wrap py-2 justify-center">
						<p class="w-11/12">
							Nilai.
							<input
								:value="minimumModel"
								@input="inputNumberAbs"
								v-debounce:300="submitMinimum"
								data-theme="light"
								class="w-10 border border-gray-300 rounded text-center bg-white"
								name=""
							/>
						</p>
						<small class="text-center text-red-600" v-show="minimumAlert"
							>error: angkah tidah boleh lebih dari 100</small
						>
					</div>
				</div>
				<div class="mt-3">
					<p class="font-bold text-lg">Waktu mulai ujian</p>
					<input
						class="checkbox bg-white"
						type="checkbox"
						id="start"
						data-theme="light"
						:checked="startExamCheckBox"
						v-model="startExamCheckBox"
					/>
					<label class="px-2" for="start">Gunakan</label>
					<div
						class="flex justify-center py-2"
						v-if="examData.time.start != null"
					>
						<input
							class="bg-white input input-bordered text-center"
							type="datetime-local"
							:value="timeStart"
							data-theme="light"
							@change="dateTime"
						/>
					</div>
				</div>
				<div class="mt-3">
					<p class="font-bold text-lg">Kunci ujian</p>
					<div>
						<input
							class="input border border-gray-300 text-center h-[2rem] bg-white"
							name=""
							data-theme="light"
							v-model="key"
							readonly
							@click="keyModal = !keyModal"
						/>
					</div>
				</div>
			</div>
		</div>
	</div>
	<ModalExamChange
		:show="examNameChange"
		@changeInput="changeExam"
		@closeModal="closeModal"
		:pending="examNamePending"
		data-theme="light"
		:input="examName"
		:title="'Ubah Nama Ujian'"
	/>
	<ModalExamChange
		:show="examDescriptionChange"
		@changeInput="changeDeskription"
		@closeModal="closeModal"
		:pending="examDescriptionPending"
		:input="examDescription"
		data-theme="light"
		:title="'Ubah Deskripsi Ujian'"
	/>
	<ModalExamChange
		:show="examlessonChange"
		@changeInput="selectedLesson"
		@closeModal="closeModal"
		:pending="selectedLessonPending"
		data-theme="light"

		:input="other"
		:title="'Pelajaran Lainnya'"
	/>
	<ModalRemove
		:show="modalRemove"
		@accept="acceptRemove"
		data-theme="light"

		@decline="closeModal"
	/>
	<ModalExamChange
		:show="keyModal"
		@changeInput="keySave"
		data-theme="light"

		@closeModal="closeModal"
		:pending="keyPending"
		:input="key"
		:title="'Masukkan Kunci'"
	/>
	<Transition>
		<Notification
			:show="notification"
			:sign="'checklist'"
		data-theme="light"

			:text="text"
			@notification="notificationStatus"
		/>
	</Transition>
	<Transition>
		<Notification :show="pending" :sign="'pending'" :text="Pending" />
	</Transition>
	<Transition>
		<Notification
			:show="notificationExamNameChange"
			:sign="notificationExamNameChangeSign"
			:text="notificationExamNameChangeText"
			@notification="notificationStatus"
		/>
	</Transition>
	</div>
</template>
<script setup>
import Navbar from "@/Components/navbar.vue";
import Problem from "@/Components/myExam/problem.vue";
import ImageLeft from "@/Components/card/imageLeft.vue";
import ModalExamChange from "@/Components/modal/modalExamChange.vue";
import ModalRemove from "@/Components/modal/modalRemove.vue";
import Notification from "@/Components/notification/index.vue";
import Question from "@/Components/question/question.vue";
import { Link, router } from "@inertiajs/inertia-vue3";
import {
	BookOutline,
	CopyOutline,
	CreateOutline,
	TimeOutline,
	KeyOutline,
	Pencil,
	Analytics,
	AddCircleOutline,
	SettingsOutline,
	CloseOutline,
} from "@vicons/ionicons5";
import { ref, watch, onMounted, onUpdated } from "vue";
import Chart from 'primevue/chart';
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
	session: Object,
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
			keyPending.value = false;
			keyModal.value = false;
		})
		.catch(() => {
			keyPending.value = false;
			keyModal.value = false;
		});
}
const problem = ref([...props.problem]);

// onMounted(() => {
// 	chartData.value = setChartData();
// });
const examNameChange = ref(false);
const chartData = ref();

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
	if (notification.value == true || notificationExamNameChange.value == true) {
		setTimeout(() => {
			notification.value = false;
			notificationExamNameChange.value = false;
		}, 2000);
	}
});
const pending = ref(false);
watch(pending, () => {
	if (
		notification.value == true ||
		notificationExamNameChange.value == true ||
		pending.value == true
	) {
		setTimeout(() => {
			notification.value = false;
			notificationExamNameChange.value = false;
			pending.value = false;
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
	if (notification.value == true || notificationExamNameChange.value == true) {
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
	examDescriptionChange.value = false;
	modalRemove.value = false;
};
const examlessonChange = ref(false);
const selected = ref(props.exam.lesson_id);

watch(selected, () => {
	selected.value = selected.value;
});

const selectedLesson = (val) => {
	if ((typeof val === "object" || val === undefined) == false) {
		other.value = val;
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
				notificationExamNameChangeText.value = "Pelajaran Berhasil Diubah";
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
				other.value = "";
				examData.value = null;
				examData.value = result.data.now;
				notificationExamNameChangeSign.value = "checklist";
				notificationExamNameChangeText.value = "Pelajaran Berhasil Diubah";
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
const otherLesson = () => {};
const timeStart = ref();
const date = ref();
watch(examData, () => {}, { immediate: true });
function timeStartFunction() {
	if (examData.value.time.start != null) {
		if (examData.value.time.start_time.start != null) {
			date.value = new Date(examData.value.time.start_time.start);
			const mounth = ref(date.value.getMonth());
			const minutes = ref(date.value.getMinutes());
			const datee = ref(date.value.getDate());
			const hours = ref(date.value.getHours());

			const lengthMonth = ref((mounth.value = mounth.value.toString().length));
			const lengthMinutes = ref(
				(minutes.value = minutes.value.toString().length)
			);
			const lengthDate = ref((datee.value = datee.value.toString().length));
			const lengthHours = ref((hours.value = hours.value.toString().length));
			const sum = ref(date.value.getMonth() + 1);

			timeStart.value = `${date.value.getFullYear()}-${
				lengthMonth.value == 1 ? "0" + sum.value : date.value.getMonth() + 1
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
}
timeStartFunction();
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
				timeStartFunction();
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
const tierExam = ref(["SD", "SMP", "SMA", "Mahasiswa"]);
const tierModel = ref(examData.value.tier);
watch(tierModel, () => {
	axios
		.post(route("tier", examData.value.id), {
			val: tierModel.value,
		})
		.then((output) => {
			examData.value = output.data.exam;
			problem.value = [];
			problem.value.push(...output.data.problem);
		});
});
const examDescriptionChange = ref(false);
const examDescriptionPending = ref(false);
const examDescription = ref(examData.value.description);
const changeDeskription = (val) => {
	examDescriptionPending.value = true;
	examDescriptionPending.value = true;
	examDescription.value = val;
	axios
		.post(route("changeExamDescription", examData.value.id), {
			val: val,
		})
		.then((output) => {
			examDescriptionPending.value = false;
			examDescriptionChange.value = false;
			examData.value = output.data.exam;
			problem.value = [];
			problem.value.push(...output.data.problem);
		});
};
const imageExam = (e) => {
	pending.value = true;
	const url = URL.createObjectURL(e.target.files[0]);
	let formData = new FormData();

	formData.append("file", e.target.files[0]);
	axios
		.post(route("changeExamImage", examData.value.id), formData, {
			headers: {
				"Content-Type": "multipart/form-data",
			},
		})
		.then((output) => {
			examData.value = output.data.exam;
			problem.value = [];
			problem.value.push(...output.data.problem);
		});
};
const modalRemove = ref(false);
const acceptRemove = () => {
	window.location.assign(route("examRemove", examData.value.id));
};
const minimumModel = ref(examData.value.minimum);
const minimumAlert = ref(false);
function inputNumberAbs(e) {
	let val = e.target.value;
	val = val.replace(/^0+|[^\d.]/g, "");
	minimumModel.value = val;
}
function submitMinimum(e) {
	if (parseInt(e) <= 100 || e == "") {
		pending.value = true;
		minimumAlert.value = false;
		axios
			.post(route("minimum", examData.value.id), {
				minimum: parseInt(e),
			})
			.then((output) => {
				pending.value = false;
				examData.value = output.data.exam;
				problem.value = [];
				problem.value.push(...output.data.problem);
			});
	} else {
		minimumAlert.value = true;
	}
}

onMounted(() => {
	chartData.value = setChartData();
});
const passed = ref(0);
const fail = ref(0);
passed.value = 0;
fail.value = 0;
for (var i in props.session) {
	if (examData.value.minimum <= props.session[i].rate) {
		passed.value++;
	} else {
		fail.value++;
	}
}
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
				data: [passed.value, fail.value],
				backgroundColor: ["rgb(220 38 38)", "rgb(31 41 55)"],
				hoverBackgroundColor: ["rgb(239 68 68)", "rgb(17 24 39)"],
			},
		],
	};
};

const detected = ref(false);
watch(detected, () => {
	axios
		.post(route("detected", examData.value.id), {
			val: detected.value,
		})
		.then((output) => {
			examData.value = output.data.exam;
			problem.value = [];
			problem.value.push(...output.data.problem);
		});
});
let setting = ref(false);
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
