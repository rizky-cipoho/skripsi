<template>
	<div class="bg-gray-100 my-5 rounded shadow p-5">
		<div class="flex justify-between items-center mb-5">

		<p class="font-bold text-lg">Soal 1</p>

		<Chart type="pie" :data="chartData" :options="chartOptions" class="w-1/12" />
		<p>Poin. 10</p>


		</div>
		<p>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
			eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
			ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
			aliquip ex ea commodo consequat. Duis aute irure dolor in
			reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
			pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
			culpa qui officia deserunt mollit anim id est laborum.
		</p>
		<div class="flex grid grid-cols-2 mt-5">
			<div class="flex items-center gap-3">
				<CheckmarkCircle class="w-6 text-red-600" />
				<p>sadsadsad</p>
			</div>
			<div class="flex items-center gap-3">
				<CloseCircleSharp class="w-6" />
				<p>sadsadsad</p>
			</div>
			<div class="flex items-center gap-3">
				<CloseCircleSharp class="w-6" />
				<p>sadsadsad</p>
			</div>
			<div class="flex items-center gap-3">
				<CloseCircleSharp class="w-6" />
				<p>sadsadsad</p>
			</div>	
		</div>
	</div>
</template>
<script type="text/javascript" setup>
import { CheckmarkCircle,CloseCircleSharp } from "@vicons/ionicons5";
import { ref,watch,onMounted } from "vue";
import Chart from 'primevue/chart';

onMounted(() => {
	chartData.value = setChartData();
});

const chartData = ref();
const chartOptions = ref({
	plugins: {
		legend: {
			labels: {
				usePointStyle: true
			}
		}
	}
});

const setChartData = () => {
	return {
		datasets: [
		{
			data: [540, 325],
			backgroundColor: ['rgb(220 38 38)', 'rgb(31 41 55)'],
			hoverBackgroundColor: ['rgb(239 68 68)', 'rgb(17 24 39)']
		}
		]
	};
};
</script>
