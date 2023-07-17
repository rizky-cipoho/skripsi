<template>
	<div
		class="max-md:h-screen max-md:w-full max-md:bg-white max-md:fixed max-md:top-0 max-md:left-0 max-md:overflow-y-auto max-md:px-20 max-md:p-10 md:w-3/12 md:hidden"
		:class="{ 'max-md:hidden': local == false }"
	>
		<div class="flex justify-left py-10 w-full">
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
	<div class="bg-white text-black">
		<Navbar :user="props.auth.user" :ziggy="props.ziggy" />
		<div class="flex justify-between py-10">
			<div class="flex justify-center w-full md:w-10/12">
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
		<div
			class="fixed bg-red-600 bottom-[7%] right-[4%] rounded-full p-3 hover:bg-gray-700 text-white hover:-rotate-90 transition duration-300 cursor-pointer md:hidden"
			@click="local = !local"
			style="z-index: 9999"
		>
			<MoveSharp class="w-5" v-show="local == false" />
			<CloseOutline class="w-5" v-show="local == true" />
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
import { MoveSharp, CloseOutline } from "@vicons/ionicons5";

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
const local = ref(false);
</script>
