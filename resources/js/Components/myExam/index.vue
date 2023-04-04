<template>
	<div class="">
		<div class="card bg-base-100 shadow-xl image-full">
			<figure><div class="bg-cover w-full h-full bg-cover bg-center" :style="{'background-image': `url(${props.exam.attachment.image})`}"></div></figure>
			<div class="card-body px-5 py-5">
				<p class=" text-xl textlimit">{{ props.exam.exam }}</p>
				<p class="">({{ props.exam.lesson.lesson }})</p>
				<small>{{ props.exam.uni_code }} </small>
				<div class="flex justify-end">
					<Link :href="route('examSelected', props.exam.id)" class="bg-red-600 text-white py-1 mt-2 px-2 rounded font-semibold hover:bg-gray-700 focus:outline-none tracking-widest active:bg-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 inline-flex block mb-1">Buka</Link>
				</div>
			</div>
		</div>
	</div>
</template>
<script setup>
import { Link,router } from "@inertiajs/inertia-vue3";
	let props = defineProps({
		exam: Object,
	});
</script>