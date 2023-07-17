<template>
	<div class="px-10 rounded">
		<div class="">
			<h1 class="text-2xl">{{ props.seconds.lesson }} Untuk Tingkat {{props.tier}}</h1>
			<hr
				class="w-7 bg-red-600"
				style="height: 2px; border-width: 0; color: red"
			/>
			<div class="flex grid gap-3 md:grid-cols-6 max-md:grid-cols-2 my-4">
				<Link
					v-for="(second, index) in props.seconds.data"
					:href="route('examInfo', second.id)"
					class="bg-gray-300"
				>
					<cardRecomendation
						:image="
							second.attachment.path + second.attachment.filename
						"
						:title="second.exam"
						:author="second.user.name"
						:key="index"
					/>
				</Link>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript" setup>
import { ref, watch, computed } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/inertia-vue3";
import cardRecomendation from "@/Components/dashboard/cardRecomendation.vue";
const props = defineProps({
	seconds: Object,
	tier: String,
});
</script>
