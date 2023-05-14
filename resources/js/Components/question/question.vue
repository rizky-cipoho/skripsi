<template>
	<div class="bg-gray-100 my-5 rounded shadow p-5">
		<div class="flex justify-between items-center mb-5">
			<p class="font-bold text-lg">Soal {{ props.index + 1 }}</p>

			<Chart
				type="pie"
				:data="chartData"
				:options="chartOptions"
				class="w-1/12"
			/>
			<p>Poin. 10</p>
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
				<CloseCircleSharp class="w-6" v-if="choice.keys == null" />
				<CheckmarkCircle class="w-6 text-red-600" v-if="choice.keys != null" />
				<div>
					<p v-if="choice.choice != null">{{ choice.choice }}</p>
				<img
					class="rounded p-2 border border-gray-400"
					v-if="choice.attachment != null"
					:src="
						choice.attachment.path +
						choice.attachment.filename
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
});

// console.log(props.question);

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
</script>
