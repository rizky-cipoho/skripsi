<template>
	<div class="bg-gray-100 my-5 rounded shadow p-5">
		<div class="flex justify-between items-center mb-5">
			<p class="font-bold text-lg">Soal {{ props.index + 1 }}</p>

			<div class="md:w-1/12 max-md:w-2/12">
				<Chart
				type="pie"
				:data="chartData"
				:options="chartOptions"
				class=""
			/>
			</div>
			<p>Poin. {{ props.question.point.point }}</p>
		</div>
		<div
			v-for="(data, index) in props.question.question_data"
			:key="index"
			:class="[{ 'flex justify-center': data.type == 'image' }]"
		>
			<p v-if="data.type == 'paragraph'">{{ data.data }}</p>
			<img
				class="rounded p-2 border border-gray-400"
				v-if="data.type == 'image'"
				:src="
					data.question_attachment.path +
					data.question_attachment.filename
				"
			/>
		</div>
		<div class="flex grid grid-cols-2 mt-5">
			<div
				v-for="(choice, index) in props.question.choice"
				:key="index"
				class="flex items-center gap-3"
			>
				<div>
					<CloseCircleSharp class="w-6" v-if="choice.keys == null" />
					<CheckmarkCircle
						class="w-6 text-red-600"
						v-if="choice.keys != null"
					/>
				</div>
				<div>
					<p v-if="choice.choice != null" class="w-full">
						{{ choice.choice }}
					</p>
					<img
						class="rounded p-2 border border-gray-400"
						v-if="choice.attachment != null"
						:src="
							choice.attachment.path + choice.attachment.filename
						"
					/>
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript" setup>
import { CheckmarkCircle, CloseCircleSharp } from "@vicons/ionicons5";
import { ref, watch, onMounted } from "vue";
import Chart from "primevue/chart";

const props = defineProps({
	question: Object,
	index: Number,
	session: Object,
});
onMounted(() => {
	chartData.value = setChartData();
});

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
const passed = ref(0);
const fail = ref(0);
const user = ref([]);
const exam = ref([]);
for (let i in props.session) {
	if (user.value.includes(props.session[i].history.user_id) == false) {
		user.value.push(props.session[i].history.user_id);
		for (let k in props.session[i].history.question) {
			if (
				props.session[i].history.question[k].question_id ==
				props.question.id
			) {
				if (props.session[i].history.question[k].answer == null) {
					fail.value++;
				} else {
					for (let j in props.question.choice) {
						if (props.question.choice[j].keys != null) {
							if (
								props.session[i].history.question[k].answer !=
								null
							) {
								if (
									props.question.choice[j].keys.choice_id ==
									props.session[i].history.question[k].answer
										.choice_history.choice.id
								) {
									passed.value++;
								} else {
									fail.value++;
								}
							}
						}
					}
				}
			}
		}
	}
}

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
</script>
