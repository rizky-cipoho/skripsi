<template>
	<transition name="slide-in">
		<div 
		class="absolute top-0 bottom-0 left-0 right-0 flex justify-center" 
		v-show="currentSlide === index">
		<div class="flex justify-center items-center p-20">
			<div>
				<img src="/image/kutip-buka.png" class="h-5 m-5">
				<p class="text-lg font-semibold">Aku sudah pernah merasakan semua kepahitan dalam hidup dan yang paling pahit ialah berharap kepada manusia.</p>
				<div class="flex justify-end">
					<img src="/image/kutip-tutup.png" class="h-5 mt-5 mr-10">	
				</div>
			</div>
		</div>
		<img :src="image" class="rounded-xl">
		</div>
	</transition>
</template>
<style scoped>
.slide-in-enter-active,
.slide-in-leave-active {
	transition: all 1s ease-in-out;
}
.slide-in-enter-from {
	transform: translateX(-100%);
}
.slide-in-leave-to{
	transform: translateX(100%);
}

</style>
<script>
export default{
	props: ["image", "currentSlide", "index"]
}
</script>
