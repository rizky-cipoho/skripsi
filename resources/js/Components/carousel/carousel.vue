<template>
	<div>
		<carouselItem v-for="(slide, index) in slides" :key="index" :image="slide" :currentSlide="currentSlide" :index="index"/>
		<carouselControls class="absolute top-[43%]" :currentSlide="currentSlide" v-on:addCurrentNumber="addCurrentNumber" v-on:nerfCurrentNumber="nerfCurrentNumber"/>

	</div>
</template>
<script type="text/javascript">
import carouselItem from './carouselItem.vue'
import carouselControls from './carouselControls.vue'
import {ref} from "vue"

export default{
	props: ["slides"],
	components: { carouselItem, carouselControls },
	data: ()=>({
		currentSlide: 0,
		slideInterval: null
	}),
	methods:{
		setCurrentSlide(index){
			this.currentSlide = index;
		},
		addCurrentNumber(){
			this.currentSlide = this.currentSlide < this.slides.length - 1 ? this.currentSlide + 1 : 0;
			this.$emit('transitionNameNext')
		},
		nerfCurrentNumber(){
			this.currentSlide = this.currentSlide > 0 ? this.currentSlide - 1 : this.slides.length - 1;
			this.$emit('transitionNameprevious')

		}
	},
	mounted (){
		// this.slideInterval = setInterval(()=>{
		// 	let index = this.currentSlide < this.slides.length - 1 ? this.currentSlide + 1 : 0;
		// 	this.setCurrentSlide(index);
		// },5000)
	},
	beforeUnmount (){
		// clearInterval(this.slideInterval);
	}

}
</script>