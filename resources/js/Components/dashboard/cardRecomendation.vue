<template>
	<div class="rounded-lg bg-white">
		<div :style="{ 'background-image': 'url('+ card.image +')' }" class=" w-full h-[130px] bg-cover rounded-t-lg bg-center"></div>
		<div class="px-2 py-2">
			<p class="text-base pt-2 font-semibold textlimit ">{{ card.title }}</p>
			<small>{{ card.author }}</small>
		</div>
	</div>
</template>

<script type="text/javascript" setup>
	let card = defineProps({
		image: String,
		title: String,
		author: String
	})
</script>
