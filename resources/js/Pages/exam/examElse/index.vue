<template>
	<div class="pb-32">
		<Navbar :user="props.auth.user" :ziggy="props.ziggy" />
		<div class="px-10 pt-10">
			<div
				class="alert alert-error shadow-lg mb-5"
				v-if="
					props.notification != undefined || 0 < props.problem.length
				"
			>
				<div>
					<svg
						xmlns="http://www.w3.org/2000/svg"
						class="stroke-current flex-shrink-0 h-6 w-6"
						fill="none"
						viewBox="0 0 24 24"
					>
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							stroke-width="2"
							d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
						/>
					</svg>
					<span>{{
						props.notification != undefined
							? "Data Ujian ini Belum Lengkap"
							: 0 < props.problem.length
							? "Data Ujian ini Belum Lengkap"
							: ""
					}}</span>
				</div>
			</div>
			<div
				class="alert alert-error shadow-lg mb-5"
				v-if="
					props.exam.remove == 'remove'
				"
			>
				<div>
					<svg
						xmlns="http://www.w3.org/2000/svg"
						class="stroke-current flex-shrink-0 h-6 w-6"
						fill="none"
						viewBox="0 0 24 24"
					>
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							stroke-width="2"
							d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
						/>
					</svg>
					<span>
							Ujian ini Telah di Hapus
					</span>
				</div>
			</div>

			<h1 class="text-2xl pb-5 font-semibold px-10 text-center">
				Informasi Ujian
			</h1>
			<div class="md:flex md:justify-center">
				<div class="md:w-6/12">
					<div class="md:shadow md:border md:border-gray-10 rounded h-fit">
						<div class="md:flex md:grid md:grid-cols-3 w-full">
							<div
								class="flex justify-center items-center md:border-r max-md:border-b-2"
							>
								<div class="py-10 text-center">
									<div class="flex justify-center">
										<DocumentTextOutline
											class="w-6 mr-1 text-red-600"
										/>
										<p class="font-semibold text-2xl">
											{{ props.history.length }}
										</p>
									</div>
									<small>Ujian dikerjakan</small>
								</div>
							</div>
							<div
								class="flex justify-center items-center md:border-r max-md:border-b-2"
							>
								<div class="py-10 px-10 text-center">
									<div class="flex justify-center">
										<BookmarksSharp
											class="w-6 mr-1 text-red-600"
										/>
									</div>
									<p
										class="font-semibold text-2xl"
										v-if="
											props.exam.lesson.lesson ==
											'Lainnya'
										"
									>
										{{ props.exam.lesson.lesson }} ({{
											props.exam.other
										}})
									</p>
									<p class="font-semibold text-2xl" v-else>
										{{ props.exam.lesson.lesson }}
									</p>
									<small>Matapelajaran</small>
								</div>
							</div>
							<div class="flex justify-center items-center">
								<div class="py-10 text-center">
									<div class="flex justify-center">
										<DocumentsSharp
											class="w-6 mr-1 text-red-600"
										/>
										<p class="font-semibold text-2xl">
											{{ props.exam.question.length }}
										</p>
									</div>
									<small>Jumlah Soal</small>
								</div>
							</div>
						</div>
					</div>
					<button
						class="bg-red-600 text-white py-3 mt-4 px-2 rounded font-semibold hover:bg-gray-700 focus:outline-none tracking-widest active:bg-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 mb-1 w-full text-center max-md:hidden"

						:class="[
							{
								'opacity-50 cursor-not-allowed':
									!examReadyResult,
							},
						]"
						as="button"
						@click="sessionRedirect"
						:disabled="!examReadyResult"
					>
						Kerjakan Ujian
					</button>
					<!-- </a> -->
					<div
						class="overflow-y-auto border mt-5 max-md:hidden"
						v-if="props.history.length != 0"
					>
						<table class="table table-zebra w-full">
							<tr
								v-for="(history, index) in props.history"
								:class="{ hidden: index >= 10 }"
							>
								<th>
									<span	
										class="px-3"
	
										:class="[
											{ 'bg-yellow-300': index == 0 },
											{ 'bg-zinc-300': index == 1 },
											{ 'bg-amber-600': index == 2 },
										]"
										>{{ parseInt(index) + 1 }}</span	
									>
								</th>
								<td>
									<div
										class="w-16 h-16 bg-cover rounded-full"
										style="
											background-image: url('/image/rasberry.jpg');
										"
									></div>
								</td>
								<td>{{ history.data.user.name }}</td>
								<td>Point. {{ history.point }}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="md:w-4/12 px-10 max-md:py-5">
					<p class="pt-1 font-semibold text-xl">Judul Ujian</p>
					<p>
						{{ props.exam.exam }}
					</p>
					<div class="border-b-2 pb-3 mb-3 border-red-400"></div>
					<div>
						<p class="pt-1 font-semibold">Kode Ujian</p>
						<small>
							{{ props.exam.uni_code }}
						</small>
					</div>
					<div class="border-b-2 pb-3 mb-3 border-red-400"></div>
					<div>
						<p class="pt-1 font-semibold">Deskripsi Ujian</p>
						<small
							:class="{
								'text-gray-400': props.exam.description == null,
							}"
						>
							{{
								props.exam.description != null
									? props.exam.description
									: "Tidak ada deskripsi"
							}}
						</small>
					</div>
					<div class="border-b-2 pb-3 mb-3 border-red-400"></div>

					<div>
						<p class="pt-1 font-semibold">Tingkat Ujian</p>
						<small>
							{{ props.exam.tier }}
						</small>
					</div>

					<div v-if="props.exam.time.start_time != null">
						<div v-if="props.exam.time.start_time.start != null">
							<div
								class="border-b-2 pb-3 mb-3 border-red-400"
							></div>
							<p class="pt-1 font-semibold">
								Tanggal dan Waktu Mulai Ujian
							</p>
							<small class="">
								{{
									yearDB +
									"-" +
									monthDB +
									"-" +
									dateDB +
									" " +
									hourDB +
									":" +
									minutesDB
								}}
							</small>
							<div
								class="border-b-2 pb-3 mb-3 border-red-400"
							></div>
							<p class="pt-1 font-semibold">Status Ujian</p>
							<small class="">
								{{
									examReadyResult
										? "Ujian Sedang Berlangsung"
										: nowIsOver
										? "Ujian Sudah Terlewati"
										: "Belum dimulai"
								}}
							</small>
						</div>
					</div>
					<div class="border-b-2 pb-3 mb-3 border-red-400"></div>
					<p class="pt-1 font-semibold">Durasi Waktu Ujian</p>
					<small class="">
						{{ props.exam.time.duration }} Menit
					</small>
					<div class="border-b-2 pb-3 mb-3 border-red-400"></div>
					<p class="pt-1 font-semibold">Pembuat Ujian</p>

					<small class="">
						{{ props.exam.user.name }}
					</small>
					<div class="border-b-2 pb-3 border-red-400"></div>
					<p class="pt-1 font-semibold">Informasi Tambahan</p>
					<small
						>Waktu Ujian Ditentukan Pembuat:
						{{
							props.exam.time.start != null ? "Ya" : "Tidak"
						}}</small
					><br />
					<small
						>Ujian Butuh Kunci:
						{{ props.exam.key ? "Ya" : "Tidak" }}</small
					>
				</div>
				<button
						class="bg-red-600 text-white py-3 mt-4 px-2 rounded font-semibold hover:bg-gray-700 focus:outline-none tracking-widest active:bg-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 mb-1 w-full text-center md:hidden"

						:class="[
							{
								'opacity-50 cursor-not-allowed':
									!examReadyResult,
							},
						]"
						as="button"
						@click="sessionRedirect"
						:disabled="!examReadyResult"
					>
						Kerjakan Ujian
					</button>
					<div
						class="overflow-y-auto border mt-5 md:hidden"
						v-if="props.history.length != 0"
					>
						<table class="table table-zebra w-full">
							<tr
								v-for="(history, index) in props.history"
								:class="{ hidden: index >= 10 }"
							>
								<th>
									<span	
										class="px-3"
	
										:class="[
											{ 'bg-yellow-300': index == 0 },
											{ 'bg-zinc-300': index == 1 },
											{ 'bg-amber-600': index == 2 },
										]"
										>{{ parseInt(index) + 1 }}</span	
									>
								</th>
								<td>
									<div
										class="w-16 h-16 bg-cover rounded-full"
										style="
											background-image: url('/image/rasberry.jpg');
										"
									></div>
								</td>
								<td>{{ history.data.user.name }}</td>
								<td>Point. {{ history.point }}</td>
							</tr>
						</table>
					</div>
			</div>
		</div>
	</div>
	<ModalExamChange
		:show="keyModal"
		@changeInput="keyValidate"
		@closeModal="closeModal"
		:pending="keyPending"
		:input="key"
		:title="'Masukkan Kunci'"
	/>
	<ModalRules :show="rules" @closeModal="closeModal" class="z-30" />
	<Transition>
		<Notification
			:show="notification"
			:sign="'error'"
			:text="'Kunci Salah'"
		/>
	</Transition>
</template>

<script type="text/javascript" setup>
import Navbar from "@/Components/navbar.vue";
import ModalExamChange from "@/Components/modal/modalExamElseKey.vue";
import ModalRules from "@/Components/modal/modalRules.vue";
import Notification from "@/Components/notification/index.vue";
import { ref, watch } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import {
	BookmarksSharp,
	DocumentTextOutline,
	DocumentsSharp,
} from "@vicons/ionicons5";

const props = defineProps({
	ziggy: Object,
	auth: Object,
	exam: Object,
	problem: Object,
	history: Object,
	notification: String,
});
console.log(props.exam.remove);
const key = ref("");
const keyModal = ref(false);
const keyPending = ref(false);
const notification = ref(false);
function sessionRedirect() {
	if (props.exam.key) {
		keyModal.value = true;
	} else {
		window.location.href = route("createSession", props.exam.id);
	}
}
function keyValidate(val) {
	key.value = val;
	keyPending.value = true;
	if (key.value == props.exam.key) {
		window.location.href = route("createSession", props.exam.id);
	} else {
		keyPending.value = false;
		keyModal.value = false;
		notification.value = true;
	}
}
const closeModal = () => {
	keyModal.value = false;
	rules.value = false
};
watch(notification, () => {
	if (notification.value == true) {
		setTimeout(() => {
			notification.value = false;
		}, 2000);
	}
});
const examReady = ref(true);
const examReadyResult = ref(props.exam.remove != 'remove' ? true : false);
const yearDB = ref("");
const monthDB = ref("");
const dateDB = ref("");
const hourDB = ref("");
const minutesDB = ref("");
const nowIsOver = ref("");
if (props.exam.time.start != null) {
	if (props.exam.time.start_time.start != null) {
		examReady.value = false;

		const dateInDB = ref(
			new Date(props.exam.time.start_time.start).getTime()
		);
		const dateStart = ref(new Date(props.exam.time.start_time.start));
		yearDB.value = dateStart.value.getFullYear();
		monthDB.value = dateStart.value.getMonth();
		dateDB.value = dateStart.value.getDate();
		hourDB.value = dateStart.value.getHours();
		minutesDB.value = dateStart.value.getMinutes();

		const dateNow = ref(new Date().getTime());

		const dateInDBMilis = new Date(
			props.exam.time.start_time.start
		).getTime();
		const durationsMilis = props.exam.time.duration * 60000 + dateInDBMilis;
		const overMilis = ref(new Date(durationsMilis).getTime());

		let looping = setInterval(function () {
			if (props.exam.time.start_time.start < dateNow.value) {
				examReady.value = true;
			}
			nowIsOver.value = overMilis.value < dateNow.value;

			if (overMilis.value < dateNow.value) {
				examReady.value = false;
			}
			examReadyResult.value = ready();
			if (overMilis.value < dateNow.value) {
				clearInterval(looping);
			}
		}, 1000);
	}
}
watch(
	examReadyResult,
	() => {
		if (props.problem.length != 0) {
			examReadyResult.value = false;
		}
	},
	{ immediate: true }
);
const ready = () => {
	return props.problem.length == 0 && examReady.value == true;
};
const rules = ref(props.exam.remove != 'remove' ? true : false);
</script>
