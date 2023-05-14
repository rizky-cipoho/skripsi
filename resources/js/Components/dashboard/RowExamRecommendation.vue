<template>
	<div class="px-10">
		<div class="">
			<h1 class="text-2xl">Rekomendasi Ujian {{ props.favorite }}</h1>
			<hr
				class="w-20 bg-red-600"
				style="height: 2px; border-width: 0; color: red"
			/>
			<div class="flex grid gap-3 md:grid-cols-6 max-md:grid-cols-2 my-4">
				<Link
					v-for="(recomendation, index) in props.recommendationsData"
					:href="route('examInfo', recomendation.id)"
				>
					<cardRecomendation
						:image="recomendation.attachment.image"
						:title="recomendation.exam"
						:author="recomendation.name"
						:key="index"
						class="cursor-pointer"
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
	recommendationsData: Object,
	favorite: String,
});
console.log(props.recommendationsData);
</script>
