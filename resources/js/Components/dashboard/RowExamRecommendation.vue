<template>
	<div class="px-10 rounded">
		<div class="">
			<h1 class="text-2xl">Rekomendasi Ujian {{ props.favorite == "Semuanya" ? "" : props.favorite }} Untuk Tingkat {{ props.tier }}</h1>
			<hr
				class="w-20 bg-red-600"
				style="height: 2px; border-width: 0; color: red"
			/>
			<div class="flex grid gap-3 md:grid-cols-6 max-md:grid-cols-2 my-4" v-if="props.recommendationsData.length != 0">
				<Link
					v-for="(recomendation, index) in props.recommendationsData"
					:href="route('examInfo', recomendation.id)"
				>
					<cardRecomendation
						:image="recomendation.attachment.path+recomendation.attachment.filename"
						:title="recomendation.exam"
						:author="recomendation.user.name"
						:key="index"
						class="cursor-pointer"
					/>
				</Link>
			</div>
			<div class="flex justify-center items-center h-20" v-else>
				<p class="py-10 text-gray-400 text-2xl">Rekomendasi Ujian Kosong</p>
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
	tier: String,
});
</script>
