<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,Link } from '@inertiajs/inertia-vue3';
import {ref} from "vue";

const input = ref({
  search: ""
});

function add(){
  // d.preventDefault();
  
}
function numberFormat(n){
  const numbering = n;
  return numbering.toLocaleString("id-ID");
}


</script>

<template>
  <Head title="Dashboard" />
  
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="alert alert-info shadow-lg">
          <div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current flex-shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>New software update available.</span>
          </div>
        </div>
        <br>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="overflow-x-auto w-full">
              <table class="table w-full">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Image</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Job</th>
                    <th class="text-center">Point</th>
                    <th class="text-center">Siapa Sih Dia?</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="i in 20">
                    <td class="text-center"><div class="rounded" :class="[i==1?'bg-yellow-400':i==2?'bg-stone-300':i==3?'bg-amber-600':'']">{{i}}</div></td>
                    <td>
                      <img src="https://pict.sindonews.net/dyn/620/pena/news/2022/01/13/39/655579/harga-fotofoto-ghozali-bikin-melongo-ada-yang-laku-rp42-miliiar-fwx.jpg" class="w-12 h-12 bg-cover rounded-full  m-auto">
                    </td>
                    <td>
                      <div class="text-center items-center space-x-3 ">
                        <div>
                          <div class="font-bold">Hart Hagerty</div>
                        </div>
                      </div>
                    </td>
                    <td class="text-center">
                      Zemlak, Daniel and Leannon
                    </td>
                    <td class="text-center">{{ numberFormat(9127321312) }}</td>
                    <th class="text-center">
                      <button class="btn btn-ghost btn-xs">details</button>
                    </th>
                  </tr>
                </tbody>                
              </table>
            </div>
          </div>
        </div>
        <br>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
