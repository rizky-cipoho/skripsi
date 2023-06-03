<template>
	<div>
		<Navbar :user="props.auth.user" :ziggy="props.ziggy" />
		<div class="flex justify-center px-40 py-5">
			<div class="alert alert-warning shadow-lg" v-if="props.alert">
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
							d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
						/>
					</svg>
					<span>{{ props.alert }}</span>
				</div>
			</div>
		</div>
		<div class="flex justify-center md:px-40 max-md:px-10 py-5">
			<div class="alert">
				<div>
					<svg
						xmlns="http://www.w3.org/2000/svg"
						fill="none"
						viewBox="0 0 24 24"
						class="stroke-current flex-shrink-0 w-6 h-6"
					>
						<path
							stroke-linecap="round"
							stroke-linejoin="round"
							stroke-width="2"
							d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
						></path>
					</svg>
					<span>Point ujian akan berubah-ubah seiring waktu</span>
				</div>
			</div>
		</div>
		<div
			class="flex justify-center md:px-40 max-md:px-10"
			:class="{ 'py-20': props.alert == false }"
		>
			<div class="">
				<div
					class="px-5 py-3 flex md:justify-center max-md:grid max-md:grid-cols-2"
				>
					<div
						class="max-md:px-20 md:px-10 text-center md:border-r border-gray-600"
					>
						<div class="flex justify-center items-center">
							<div>
								<CheckmarkCircle class="text-green-600 w-7" />
							</div>
							<p class="px-1 text-3xl">{{ trueCount }}</p>
						</div>
						<small> Benar </small>
						<hr class="md:hidden border border-gray-500 my-3" />
					</div>
					<div
						class="max-md:px-20 md:px-10 text-center md:border-r border-gray-600"
					>
						<div class="flex justify-center items-center">
							<div>
								<CloseCircle class="text-red-600 w-7" />
							</div>
							<p class="px-1 text-3xl">
								{{ question.length - trueCount }}
							</p>
						</div>
						<small> Salah </small>
						<hr class="md:hidden border border-gray-500 my-3" />
					</div>
					<div class="px-10 text-center md:border-r border-gray-600">
						<div class="flex justify-center items-center">
							<p>Nilai.</p>
							<p class="px-1 text-3xl">
								{{ props.session.rate }}
							</p>
						</div>
						<small> Perolehan Nilai. </small>
					</div>
					<div class="px-10 text-center">
						<div class="flex justify-center items-center">
							<p>Point.</p>
							<p
								class="px-1"
								:class="[
									{ 'text-lg': pointResult == 'Duplikat' },
									{ 'text-3xl': pointResult != 'Duplikat' },
								]"
							>
								{{ pointResult }}
							</p>
						</div>
						<small> Perolehan Point. </small>
					</div>
				</div>
				<div class="flex justify-center">
					<div class="md:flex items-center justify-center text-center px-5 py-5">
						<div class="flex justify-center">
							<NavigateCircleSharp class="w-7 mx-1 text-green-600" />
						</div>
						<p>Yang di pilih benar</p>
					</div>
					<div class="md:flex text-center items-center px-5 py-5">
						<div class="flex justify-center">
						<CheckmarkCircle class="w-7 mx-1 text-green-600" />
					</div>
						Kunci jawaban
					</div>
					<div class="md:flex text-center items-center px-5 py-5">
						<div class="flex justify-center">
						<NavigateCircleSharp class="w-7 mx-1 text-red-600" />
					</div>
						Yang di pilih salah
					</div>
					<div class="md:flex text-center items-center px-5 py-5">
						<div class="flex justify-center">
						<CloseCircle class="w-7 mx-1 text-red-600" />
					</div>
						Salah
					</div>
				</div>
				<div class="">
					<div
						class="my-5 p-5 bg-gray-200 rounded"
						v-for="(data, index) in question"
					>
						<div class=""></div>
						<div class="flex justify-between items-center">
							<p class="text-2xl py-2">Soal {{ index + 1 }}</p>
							<div>Point. {{ data.question.point.point }}</div>
						</div>
						<div v-for="(questionDataUnit, index) in data.question_data">
							<div v-if="questionDataUnit.question_data.type == 'paragraph'">
								<p>{{ questionDataUnit.question_data.data }}</p>
							</div>
							<div
								v-if="questionDataUnit.question_data.type == 'image'"
								class="flex justify-center"
							>
								<img
									:src="
										questionDataUnit.question_data.question_attachment.path +
										questionDataUnit.question_data.question_attachment.filename
									"
								/>
							</div>
						</div>

						<div>
							<div class="flex grid grid-cols-2">
								<div class="my-5" v-for="(choice, index) in data.choice">
									<div
										v-if="choice.choice.choice_attachment == null"
										class="flex items-center px-5"
										:class="[
											{
												'text-green-600': choice.choice.keys != null,
											},
											{
												'text-red-600': choice.choice.keys == null,
											},
										]"
									>
										<div v-if="data.answer != null">
											<div v-if="data.answer.choice_id == choice.id">
												<NavigateCircleSharp class="w-7" />
											</div>
											<div v-else-if="choice.choice.keys != null">
												<CheckmarkCircle class="w-7 text-green-600" />
											</div>
											<div v-else>
												<CloseCircle class="w-7" />
											</div>
										</div>
										<div v-else-if="choice.choice.keys != null">
											<CheckmarkCircle class="w-7 text-green-600" />
										</div>
										<div v-else>
											<CloseCircle class="w-7" />
										</div>
										<div class="pl-3">
											{{ choice.choice.choice }}
										</div>
									</div>
									<div v-else class="flex items-center px-5">
										<div v-if="data.answer != null">
											<div v-if="data.answer.choice_id == choice.id">
												<NavigateCircleSharp
													class="w-7"
													:class="[
														{
															'text-green-600': choice.choice.keys != null,
														},
														{
															'text-red-600': choice.choice.keys == null,
														},
													]"
												/>
											</div>
										</div>
										<div v-else-if="choice.choice.keys != null">
											<CheckmarkCircle class="w-7 text-green-600" />
										</div>
										<div v-else>
											<CloseCircle class="w-7 text-gray-900" />
										</div>
										<img
											:src="
												choice.choice.attachment.path +
												choice.choice.attachment.filename
											"
										/>
									</div>
								</div>

								<!-- 								<div class="flex items-center my-5">
									<CheckmarkCircle class="w-7 text-red-600" />
									<div class="pl-3">sadsa</div>
								</div> -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript" setup>
import Navbar from "@/Components/navbar.vue";
import {
	CheckmarkCircle,
	CloseCircle,
	NavigateCircleSharp,
} from "@vicons/ionicons5";
import { ref } from "vue";

const props = defineProps({
	histories: Object,
	ziggy: Object,
	auth: Object,
	session: Object,
	sessionAll: Object,
	alert: String,
});
console.log(props.sessionAll);
const questionTrue = ref([]);
const question = ref([...props.histories.history.question]);

const trueCount = ref(0);

for (let unit in props.histories.history.question) {
	if (props.histories.history.question[unit].answer == null) {
		continue;
	}
	const answer = props.histories.history.question[unit].answer.choice_id;
	for (let unitChoice in props.histories.history.question[unit].choice) {
		if (
			props.histories.history.question[unit].choice[unitChoice].choice.keys !=
			null
		) {
			if (
				answer == props.histories.history.question[unit].choice[unitChoice].id
			) {
				trueCount.value++;
			}
		}
	}
}
const pointResult = ref();
pointResult.value = point();

function point() {
	let result = 0;
	let examArr = ref([]);

	let duplicate = ref("");

	for (let i in props.sessionAll) {
		if (props.sessionAll[i].id == props.session.id) {
			duplicate.value = i;
		}
	}
	if (duplicate.value == 0) {
		let point = props.session.history.question;

		for (let i in point) {
			if (point[i].answer == null) {
				continue;
			}
			for (let j in point[i].choice) {
				if (point[i].choice[j].choice.keys != null) {
					if (point[i].answer.choice_id == point[i].choice[j].id) {
						result = result + parseInt(point[i].question.point.point);
					}
				}
			}
		}
	} else {
		result = "Duplikat";
	}
	return result;
}
</script>
