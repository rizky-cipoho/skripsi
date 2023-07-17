<template>
	<Navbar :user="props.auth.user" :ziggy="props.ziggy" />
	<div class="px-20 max-md:px-10 my-10">
		<strong class="text-2xl my-10">Data Hasil Pencarian</strong>

		<div class="overflow-x-auto" v-if="props.data.length != 0">
			<table class="table w-full text-center mt-10">
				<thead>
					<tr>
						<th></th>
						<th>Gambar</th>
						<th>Judul Ujian</th>
						<th>Pelajaran</th>
						<th>Diperuntukkan</th>
						<th>Pemilik</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(data, index) in props.data">
						<td>{{ index + 1 }}</td>
						<td class="flex justify-center">
							<div
								:style="`background-image: url('${data.attachment.path}${data.attachment.filename}')`"
								class="w-20 h-20 bg-cover rounded shadow bg-center"
							></div>
						</td>
						<td>{{ data.exam }}</td>
						<td v-if="data.lesson.lesson == 'Lainnya'">
							{{ data.lesson.lesson }} ({{ data.other }})
						</td>
						<td v-else>{{ data.lesson.lesson }}</td>
						<td>{{ data.tier }}</td>
						<td>{{ data.user.name }}</td>
						<td>
							<Link
								:href="
									route('examInfo', data.id)
								"
								class="btn btn-ghost btn-xs"
							>
								Lihat Ujian
							</Link>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div v-else class="flex justify-center items-center h-96 text-gray-400">
			<strong class="select-none">Data Tidak ditemukan</strong>
		</div>
	</div>
</template>
<script setup>
import Navbar from "@/Components/navbar.vue";
import { ref, watch, computed } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/inertia-vue3";

const props = defineProps({
	auth: Object,
	ziggy: Object,
	data: Object,
});
</script>
