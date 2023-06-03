<template>
    <div class="flex justify-center items-center w-full h-screen bg-gray-50">
        <GuestLayout :name="title">
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="username" value="username" />
                <TextInput
                    id="username"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.username"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.username" />
            </div>
            <div class="flex grid grid-cols-3">
                <div class="mt-4 pr-2">
                    <InputLabel for="date" value="Tanggal Lahir" />
                    <select
                        id="date"
                        type="number"
                        class="mt-1 block w-full select select-bordered max-w-xs"
                        v-model="form.date"
                        required
                        autocomplete="date"
                    >
                        <option v-for="i in 31">{{i}}</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.date" />
                </div>
                <div class="mt-4 ">
                    <InputLabel for="month" value="Bulan Lahir" />
                    <select
                        id="month"
                        type="number"
                        class="mt-1 block w-full select select-bordered max-w-xs"
                        v-model="form.month"
                        required
                        autocomplete="month"
                    >
                        <option v-for="i in 12">{{i}}</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.month" />
                </div>
                <div class="mt-4 pl-2">
                    <InputLabel for="year" value="Tahun Lahir" />
                    <select
                        id="year"
                        type="number"
                        class="mt-1 block w-full select select-bordered max-w-xs tex-center"
                        v-model="form.year"
                        required
                        autocomplete="year"
                    >
                        <option v-for="i in year">{{i}}</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.year" />
                </div>
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>
            <div class="text-right text-green-600" v-if="props.alert != null">
                <small>{{ props.alert }}</small>
            </div>
            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900"
                >
                    Sudah punya Akun?
                </Link>
                <PrimaryButton
                    class="ml-4 bg-red-600 border-none"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Daftar
                </PrimaryButton>
            </div>
        </form>
        <Notification :text="props.alert" :show="props.alert" />
    </GuestLayout>
    </div>
</template>
<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Notification from "@/Components/notification/index.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref, watch } from "vue";

const props = defineProps({
    alert: String,
});
const date = ref("")

const form = useForm({
    name: "",
    username: "",
    password: "",
    date: "",
    month: "",
    year: "",
    password_confirmation: "",
    terms: false,
});
const now = new Date;

const alert = ref(props.alert);
const year = ref([])
for (var i = 1980; i <= now.getFullYear()-5; i++) {
    year.value.push(i)
}
const submit = () => {
    form.post(route("register"), {
        onFinish: (output) => form.reset("password", "password_confirmation"),
        // onFinish: (output) => form.reset(),
    });
};
const title = "Register";
</script>
