<template>
	<p
			class="text-lg font-semibold text-center"
			:class="[
				{ 'text-red-600 pb-2': 0 < props.problem.length },
				{ 'text-gray-700': props.problem.length == 0 },
			]"
		>
			{{ props.problem.length == 0 ? "Data Lengkap" : "Data Belum Lengkap" }}
		</p>
	<div
		class="rounded overflow-auto"
		:class="{ 'max-h-[9rem] mb-1': 0 < props.problem.length }"
	>
		
		<div class="text-left">
			<div
				class="my-1 py-1 hover:bg-gray-100 px-2 cursor-pointer"
				v-for="list in props.problem"
			>
				{{ list }}
			</div>
		</div>
	</div>
</template>
<script type="text/javascript" setup>
	const props = defineProps({
		problem: Object
	})
// console.log(props.problem)

</script>