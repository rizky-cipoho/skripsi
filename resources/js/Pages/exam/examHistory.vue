<template>
	<Navbar :name="props.auth.user.name" :ziggy="props.ziggy" />
	<div class="px-20 py-5">
		<Link
			:href="route('examSelected', link)"
			class="flex text-red-600 hover:scale-105 items-center"
			as="button"
		>
			<ChevronBack class="w-4 mr-1" />
			<p>Kembali</p>
		</Link>
	</div>
	<div
		class="flex justify-center px-20 pb-10"
		style="z-index: 9999"
		v-if="props.session.length != 0"
	>
		<div class="overflow-x-auto w-full">
			<table class="table w-full">
				<!-- head -->
				<thead>
					<tr class="text-center bg-red-700">
						<th>No.</th>
						<th>Image</th>
						<th>Nama</th>
						<th>Nilai.</th>
						<th>Pada Tanggal</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr
						class="text-center"
						v-for="(session, index) in props.session"
					>
						<td>{{ index + 1 }}</td>
						<td>
							<div
								class="flex items-center justify-center space-x-3"
							>
								<div class="avatar">
									<div class="mask mask-squircle w-20 h-20">
										<img
											src="/image/rasberry.jpg"
											alt="Avatar Tailwind CSS Component"
										/>
									</div>
								</div>
							</div>
						</td>
						<td>
							<div class="flex justify-center">
								<p
									class="break-words max-w-xs whitespace-pre-line"
								>
									{{ session.user.name }}
								</p>
							</div>
						</td>
						<td>
							<pre>{{ point(session.history.question) }}</pre>
						</td>
												<td>
							{{
								new Date(session.created_at).getFullYear() +
								"-" +
								new Date(session.created_at).getMonth() +
								"-" +
								new Date(session.created_at).getDate()
							}}
						</td>
						<th>
							<Link
								:href="
									route('result', [
										session.exam_id,
										session.token,
									])
								"
								class="btn btn-ghost btn-xs"
							>
								details </Link
							><br />
						</th>
					</tr>
				</tbody>
				<!-- foot -->
				<tfoot>
					<tr class="text-center">
						<th></th>
						<th></th>
						<th>Nama</th>
						<th>Nilai.</th>
						<th>Pada Tanggal</th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<div class="top-0 absolute w-full z-[-5]" v-else>
		<div
			class="h-screen flex justify-center items-center w-full text-2xl font-black text-gray-400 select-none"
		>
			Tidak ada History
		</div>
	</div>
</template>
<script type="text/javascript" setup>
import Navbar from "@/Components/navbar.vue";
import { Link, router } from "@inertiajs/inertia-vue3";
import { ChevronBack } from "@vicons/ionicons5";

const props = defineProps({
	auth: Object,
	ziggy: Object,
	session: Object,
});
console.log(props.session);
const link = props.ziggy.location.split("/")[6];
function point(point) {
	let result = 0;
	for (let i in point) {
		if (point[i].answer == null) {
			continue;
		}
		for (let j in point[i].choice) {
			if (point[i].choice[j].choice.keys != null) {
				if (point[i].answer.choice_id == point[i].choice[j].id) {
					result = result + parseInt(point[i].question.point.point);
				}
			}
		}
	}
	return result;
}
</script>
