<template>
	<Navbar :name="props.auth.user.name" :ziggy="props.ziggy" />
	<div
		class="flex justify-center px-20 py-10 z-50"
		v-if="props.session.length != 0"
	>
		<div class="overflow-x-auto w-full">
			<!-- <pre>{{ sessionNow }}</pre> -->
			<table class="table w-full">
				<!-- head -->
				<thead>
					<tr class="text-center bg-red-700">
						<th>No.</th>
						<th>Image</th>
						<th>Ujian</th>
						<th>Point.</th>
						<th>Nilai.</th>
						<th>Pada Tanggal</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr
						class="text-center"
						v-for="(session, index) in sessionNow"
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
									{{ session.session.exam.exam }}
								</p>
							</div>
						</td>
						<td>
							<p v-show="
									arrayHistory.includes(session.session.history.id) ==
									true
								">{{ sessionNow[index].point }}</p>
							
							<!-- <pre>{{ session.history }}</pre> -->
							<small	
								v-show="
									arrayHistory.includes(session.session.history.id) ==
									false
								"
								>Pengerjaan Duplikat</small	
							>
						</td>
						<td>
							{{ session.session.rate }}
						</td>
						<td>
							{{
								new Date(session.session.created_at).getFullYear() +
								"-" +
								new Date(session.session.created_at).getMonth() +
								"-" +
								new Date(session.session.created_at).getDate()
							}}
						</td>
						<th>
							<Link
								:href="
									route('result', [
										session.session.exam_id,
										session.session.token,
									])
								"
								class="btn btn-ghost btn-xs"
							>
								details </Link
							><br />
							<Link
								:href="route('examInfo', session.session.exam_id)"
								class="btn btn-ghost btn-xs"
							>
								Lihat Ujian
							</Link>
						</th>
					</tr>
				</tbody>
				<!-- foot -->
				<tfoot>
					<tr class="text-center">
						<th></th>
						<th></th>
						<th>Ujian</th>
						<th>Point.</th>
						<th>Nilai.</th>
						<th>Tanggal</th>
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
import { ref, onBeforeMount } from "vue";

const props = defineProps({
	auth: Object,
	ziggy: Object,
	session: Object,
});

const array = ref([]);
const arrayHistory = ref([]);
let sessionData = ref([...props.session]);
let sessionNow = ref([]);
onBeforeMount(() => {
	sessionData.value.reverse();

	for (let i in sessionData.value) {
		sessionNow.value.push({
			point: point(sessionData.value[i].history),
			session: sessionData.value[i],
		});
	}
	sessionNow.value.reverse();
	// console.log(sessionNow.value);

});
	console.log(props.session)
	console.log("props")

function point(arr) {
	let result = 0;
	console.log(arr)

	let point = arr.question;
	if (array.value.includes(arr.exam_id) == false) {
		arrayHistory.value.push(arr.id);
		array.value.push(arr.exam_id);

		for (let i in point) {
			if (point[i].answer == null) {
				continue;
			}
			for (let j in point[i].choice) {
				if (point[i].choice[j].choice.keys != null) {
					if (point[i].answer.choice_id == point[i].choice[j].id) {
						result =
							result + parseInt(point[i].question.point.point);
					}
				}
			}
		}
	}

	return result;
}
// console.log(sessionData.value)
</script>
