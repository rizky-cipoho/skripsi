<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
let image = 'image/leaves.png';
const imageArr = [
{image: 'image/double-bubble-outline.png'},
{image: 'image/moroccan-flower.png'},
{image: 'image/dot.png'},
{image: 'image/terrazo.png'},
{image: 'image/tic-tac-toe.png'},
{image: 'image/wavy.png'},
{image: 'image/waves.png'},
{image: 'image/memphis.png'},
{image: 'image/funky-lines.png'},
]

function rand(){
    let rand = Math.floor(Math.random() * imageArr.length);
    console.log();
    image = imageArr[rand].image;
}
rand();
</script>

<template>
    <div class="grid h-screen place-items-center" :style="{'background-image': 'url('+image+')'}">
        <div class="border p-5 border-gray-200 w-4/12 bg-white rounded drop-shadow-2xl grid justify-items-center">
            <h1 class="font-bold text-2xl my-2">
                Masuk
            </h1>
            
            Langsung Masuk
            <Link as="button" class="py-2 my-1 border border-gray-500 bg-gray-800 text-white px-4 transition duration-500 rounded m-auto w-8/12 font-bold hover:bg-gray-700" :href="route('guestLogin')">
                Sebagai Tamu
            </Link>
            <br>
            <div class="w-full m-auto text-center">
                <Link :href="route('login')" as="button" class="py-2 my-1 border border-gray-500 bg-gray-800 text-white px-4  hover:bg-gray-700 transition duration-500 rounded w-3/12 mx-0.5 font-bold">
                    Masuk
                </Link>          
                
                
                <Link :href="route('register')" as="button" class="py-2 my-1 border border-gray-500 bg-gray-800 text-white px-4 transition duration-500 rounded w-3/12  mx-0.5 font-bold hover:bg-gray-700">
                    Daftar
                </Link>
            </div>
        </div>
    </div>
</template>
