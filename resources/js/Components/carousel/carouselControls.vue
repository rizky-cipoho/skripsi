<template>
	<div class="flex justify-between w-full">
		<div class="bg-gray-700 bg-opacity-40 text-white text-lg py-2 px-2 cursor-pointer" @click="$emit('addCurrentNumber')">
			{{ next }}
		</div>
		<div class="bg-gray-700 bg-opacity-40 text-white text-lg py-2 px-2 cursor-pointer" @click="$emit('nerfCurrentNumber')">
			{{ previous }}
		</div>
	</div>
</template>
<script>
export default{
	props: ["currentSlide"],
	data: ()=>({
		next: "<",
		previous: ">",
	}),
	methods:{
		// addCurrentNumber: function(){
		// 	this.currentSlide = this.currentSlide;
		// }
	},
	computed:{
		change: function(){
		}
	}
}
</script>