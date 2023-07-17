<template>
	<Navbar :user="props.auth.user" :ziggy="props.ziggy" />
	<div class="flex justify-center max-md:px-5 max-md:py-20 p-20">
		<div class="flex justify-center md:w-8/12 max-md:w-full">
			<div class="md:flex bg-gray-100 border p-5 rounded w-full">
				<div>
					<div
						:style="`background-image: url('${image}')`"
						class="w-36 h-36 bg-cover bg-center rounded max-md:m-auto"
					></div>
					<button
						class="shadow justify-center bg-red-600 hover:bg-gray-700 text-white mt-2 rounded font-semibold focus:outline-none tracking-widest active:bg-red-600 focus:shadow-outline-gray transition ease-in-out duration-250 inline-flex block mb-1 w-full mt-4 cursor-pointer"
					>
						<input
							type="file"
							name=""
							class="cursor-pointer opacity-0 pb-2 pt-1 w-36 bg-black absolute"
							accept="image/png, image/gif, image/jpeg"
							@change="imageExam"
						/>
						<p class="py-2">Ganti Gambar</p>
					</button>
				</div>
				<div class="pl-5 w-full">
					<div class="flex items-center max-md:py-2">
						<strong class="md:text-xl max-md:text-lg">{{ name }}</strong>
						
						<div>
							<CreateOutline
								class="w-4 h-4 ml-5 cursor-pointer text-red-600 hover:text-gray-700 active:scale-75"
								@click="showName = true"
							/>
						</div>
					</div>
						<small class="">({{ date }})</small>

					<div class="py-4 md:flex md:grid md:grid-cols-2 w-full">
						<div class="mr-6">
							<div class="flex items-center">
								<div>
									<SchoolOutline class="w-5 h-5 text-red-600" />
								</div>
								<p
									class="px-2"
									:class="{
										'text-gray-400 text-sm': school == null,
									}"
								>
									{{ school == null ? "Tidak ada" : school }}
								</p>
								<CreateOutline
									class="w-3 h-3 ml-3 cursor-pointer text-red-600 hover:text-gray-700 active:scale-75"
									@click="showSchool = true"
								/>
							</div>
							<!-- 							<div class="flex items-center">
								<SchoolOutline class="w-5 h-5 text-red-600" />
								<p class="px-2">asdsad</p>
							</div> -->
						</div>
						<div>
							<div class="flex items-center">
								<SchoolOutline class="w-5 h-5 text-red-600" />
								<p class="px-2">Poin. {{ props.point }}</p>
							</div>
							<!-- 							<div class="flex items-center">
								<SchoolOutline class="w-5 h-5 text-red-600" />
								<p class="px-2">asdsad</p>
							</div> -->
						</div>
					</div>
					<div class="flex justify-center items-center w-full">
						<p
							class="text-center"
							:class="{
								'text-gray-400 text-sm': description == null,
							}"
						>
							{{
								description == null ? "Tidak ada" : description
							}}
						</p>
						<div>
							<CreateOutline
								class="w-4 h-4 ml-5 cursor-pointer text-red-600 hover:text-gray-700 active:scale-75"
								@click="showDescription = true"
							/>
						</div>
					</div>
				</div>
			</div>
		</div>
		<ModalExamChange
			:show="showName"
			@changeInput="changeName"
			@closeModal="closeModal"
			:pending="pending"
			:input="nameValue"
			:title="'Ubah Nama User'"
		/>
		<ModalExamChange
			:show="showDescription"
			@changeInput="changeDescription"
			@closeModal="closeModal"
			:pending="pending"
			:input="descriptionValue"
			:title="'Ubah Description'"
		/>
		<ModalExamChange
			:show="showSchool"
			@changeInput="changeSchool"
			@closeModal="closeModal"
			:pending="pending"
			:input="schoolValue"
			:title="'Ubah Sekolah'"
		/>
		<Transition>
			<Notification :show="pending" :sign="'pending'" :text="Pending" />
		</Transition>
	</div>
</template>
<script type="text/javascript" setup>
import Navbar from "@/Components/navbar.vue";
import Notification from "@/Components/notification/index.vue";
import { Link, router } from "@inertiajs/inertia-vue3";
import ModalExamChange from "@/Components/modal/modalExamChange.vue";
import { ref } from "vue";
import {
	SchoolOutline,
	BookOutline,
	CopyOutline,
	CreateOutline,
	TimeOutline,
	KeyOutline,
	Pencil,
	Analytics,
	AddCircleOutline,
} from "@vicons/ionicons5";
const props = defineProps({
	auth: Object,
	ziggy: Object,
	session: Object,
	user: Object,
	point: Number,
});
let user = ref(props.auth.user);
let showName = ref(false);
let showSchool = ref(false);
let showDescription = ref(false);
let name = ref(props.auth.user.name);
let school = ref(props.auth.user.school);
let description = ref(props.auth.user.description);
let pending = ref(false);
let image = ref();
if (props.user.attachment != null) {
	image.value = props.user.attachment.path+props.user.attachment.filename
}
function changeName(val) {
	pending.value = true;
	axios
		.post(route("settingName"), {
			name: val,
		})
		.then((output) => {
			name.value = output.data;
			closeModal();
			pending.value = false;
		});
}
function changeSchool(val) {
	pending.value = true;
	axios
		.post(route("settingSchool"), {
			school: val,
		})
		.then((output) => {
			school.value = output.data;
			pending.value = false;
			closeModal();
		});
}
function changeDescription(val) {
	pending.value = true;
	axios
		.post(route("settingDescription"), {
			description: val,
		})
		.then((output) => {
			description.value = output.data;
			closeModal();
			pending.value = false;
		});
}
function closeModal() {
	showName.value = false;
	showSchool.value = false;
	showDescription.value = false;
}
const imageExam = (e) => {
	pending.value = true;
	const url = URL.createObjectURL(e.target.files[0]);
	let formData = new FormData();

	formData.append("file", e.target.files[0]);
	axios
		.post(route("settingImage"), formData, {
			headers: {
				"Content-Type": "multipart/form-data",
			},
		})
		.then((output) => {
			image.value = output.data.user.attachment.path+output.data.user.attachment.filename
			pending.value = false;
		});
};
const date = ref("");
const d = new Date(props.auth.user.birth);
date.value = `${d.getDate()} - ${d.getMonth()} - ${d.getFullYear()}`;
</script>
