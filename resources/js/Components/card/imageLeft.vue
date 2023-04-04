<template>
	<div class="flex hover:bg-gray-100 cursor-pointer rounded py-2 px-2">
		<div class="w-4/12 h-28 bg-cover bg-center rounded" style="background-image: url('/image/rasberry.jpg')" />
		<div class=" w-8/12 px-3 py-1 text-left text-sm">
			<p class="textlimit">{{ props.exam.exam }}</p>
			<small>5x dikerjakan</small>
			<br>
			<small>Jenjang Sekolah Dasar</small>
		</div>
	</div>
</template>
<script type="text/javascript" setup>
	const props = defineProps({
		exam: Object
	})
</script>