<template>
	<div class="flex justify-center" v-for="(person, index) in props.data">
		<div>
			<div class="flex items-center justify-centers py-5">
				<div
					class="mx-10 px-5 py-1 font-bold rounded"
					:class="[
						{ 'bg-yellow-400': index == 0 },
						{ 'bg-gray-300': index == 1 },
						{ 'bg-amber-700': index == 2 },
					]"
				>
					{{ index + 1 }}
				</div>
				<div>
					<div
						class="md:w-24 md:h-24 max-md:w-14 max-md:h-14 bg-cover rounded-full bg-center"
						:class="[
							{ 'md:w-36 md:h-36 max-md:w-24 max-md:h-24': index == 0 },
							{ 'md:w-32 md:h-32 max-md:w-20 max-md:h-20': index == 1 },
							{ 'md:w-28 md:h-28 max-md:w-16 max-md:h-16': index == 2 },
						]"
						style="background-image: url('/image/rasberry.jpg')"
					></div>
				</div>
				<div class="md:px-10 max-md:px-5">
					<p
						class="break-words md:w-48 max-md:w-full text-center font-black"
						:class="[
							{ 'md:text-2xl max-md:text-xl font-bold': index == 0 },
							{ 'md:text-xl max-md:text-lg font-bold': index == 1 },
							{ 'md:text-lg max-md:text-base font-bold': index == 2 },
							{ 'md:text-base max-md:text-sm font-bold': index > 2 },
						]"
					>
						{{ person.name.name }}
					</p>
				</div>
				<div class="md:px-10">
					<p class="font-black"
											:class="[
							{ 'md:text-2xl max-md:text-xl font-bold': index == 0 },
							{ 'md:text-xl max-md:text-lg font-bold': index == 1 },
							{ 'md:text-lg max-md:text-base font-bold': index == 2 },
							{ 'md:text-base max-md:text-sm font-bold': index > 2 },
						]"
					>Point. {{ person.point }}</p>
				</div>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript" setup>
import { Link, router } from "@inertiajs/inertia-vue3";
const props = defineProps({
	data: Object,
});
console.log(props.data);
</script>
