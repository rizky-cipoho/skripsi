<template>
	<div class="flex justify-center transition-opacity duration-150 ease" v-if="show == true">
		<div
			class="bg-gray-300 fixed bottom-[5%] px-4 py-2 rounded-full flex items-center shadow"
		>
			
			<CheckmarkCircle
				class="w-10 text-red-600"
				v-if="props.sign == 'checklist'"
			/>
			<CloseOutline
				class="w-10 text-red-600"
				v-if="props.sign == 'error'"
			/>
			<Dots
				class="w-10 text-red-600"
				v-if="props.sign == 'pending'"
			/>
			<p class="ml-3 select-none">{{ props.text }}</p>
		</div>
	</div>
</template>
<script type="text/javascript" setup>
import { CheckmarkCircle, CloseOutline } from "@vicons/ionicons5";
import { Dots } from "@vicons/tabler";

const props = defineProps({
	text: String,
	sign: String,
	show: Boolean,
})
const emits = defineEmits(['notification'])
const status = ()=>{
	emits('notification', false)
}
</script>
