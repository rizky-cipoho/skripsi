<template>
	<Navbar :user="props.auth.user" :ziggy="props.ziggy" />
	<div class="flex justify-between py-10">
		<div class="flex justify-center max-md:full md:w-10/12">
			<div>
				<Rank :data="data" />
			</div>
		</div>
		<div class="flex justify-left py-10 w-2/12 max-md:hidden">
			<div class="overflow-y-auto">
				<div
					class="py-2 flex items-center select-none cursor-pointer hover:text-red-600"
					@click="selected = 'generale'"
					:class="[
						{
							'text-red-600 font-black text-lg':
								selected == 'generale',
						},
					]"
				>
					<ChevronForward class="w-4 mr-1" />Umum
				</div>
				<div
					class="py-2 flex items-center select-none cursor-pointer hover:text-red-600"
					v-for="(lesson, index) in props.lesson"
					@click="selected = index"
					:class="[
						{
							'text-red-600 font-black text-lg':
								selected == index,
						},
					]"
				>
					<ChevronForward class="w-4 mr-1" />{{ lesson.lesson }}
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript" setup>
import Navbar from "@/Components/navbar.vue";
import Rank from "@/Components/rank/index.vue";
import { Link, router } from "@inertiajs/inertia-vue3";
import { ref, watch } from "vue";
import { ChevronForward } from "@vicons/ionicons5";
import TreeSelect from "primevue/treeselect";

const props = defineProps({
	auth: Object,
	ziggy: Object,
	lesson: Object,
	generale: Object,
	locale: Object,
});

let selected = ref("generale");
let data = ref([...props.generale]);
watch(selected, () => {
	if (selected.value != "generale") {
		data.value = [];
		data.value = [...props.locale[selected.value].result];
	} else {
		data.value = [];
		data.value = [...props.generale];
	}
});
// console.log(props.session);
</script>
