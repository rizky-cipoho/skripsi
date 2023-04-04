<template>
	<div class="px-10">
		<div class="">
			<h1 class="text-2xl ">{{ props.seconds.lesson }}</h1>
			<hr class="w-7 bg-red-600" style="height:2px;border-width:0;color:red">
			<div class="flex grid gap-3 md:grid-cols-6 max-md:grid-cols-2 my-4">
				<cardRecomendation
					v-for="(second, index) in props.seconds.data"
					:image="second.attachment.image"
					:title="second.exam"
					:author="second.user.name"
					:key="index"
				/>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript" setup>
import { ref, watch, computed } from "vue";
import cardRecomendation from "@/Components/dashboard/cardRecomendation.vue";
const props = defineProps({
	seconds: Object,
});

</script>
