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
						class="w-24 h-24 bg-cover rounded-full bg-center"
						:class="[
							{ 'w-36 h-36': index == 0 },
							{ 'w-32 h-32': index == 1 },
							{ 'w-28 h-28': index == 2 },
						]"
						style="background-image: url('/image/rasberry.jpg')"
					></div>
				</div>
				<div class="px-10">
					<p
						class="break-words w-48 text-center font-black"
						:class="[
							{ 'text-2xl font-bold': index == 0 },
							{ 'text-xl font-bold': index == 1 },
							{ 'text-lg font-bold': index == 2 },
						]"
					>
						{{ person.name.name }}
					</p>
				</div>
				<div class="px-10">
					<p class="font-black"
											:class="[
							{ 'text-2xl font-bold': index == 0 },
							{ 'text-xl font-bold': index == 1 },
							{ 'text-lg font-bold': index == 2 },
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
