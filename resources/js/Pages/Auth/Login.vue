<template>
    <div class="flex items-center justify-center h-screen w-full bg-gray-50">
        <div class="flex items-center">
            <GuestLayout :name="title" class="">
                <Head title="Log in" />
                <div
                    class="alert alert-success shadow-lg mb-5"
                    v-if="page.props.value.flash.message != undefined"
                >
                    <div>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="stroke-current flex-shrink-0 h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <span>{{ page.props.value.flash.message }}</span>
                    </div>
                </div>
                <div
                    class="alert alert-error shadow-lg mb-5"
                    v-if="page.props.value.flash.catch != undefined"
                >
                    <div>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="stroke-current flex-shrink-0 h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <span>{{ page.props.value.flash.catch }}</span>
                    </div>
                </div>
                <div
                    v-if="status"
                    class="mb-4 font-medium text-sm text-green-600"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="username" value="Username" />
                        <TextInput
                            id="username"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.username"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.username"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="underline text-sm text-gray-600 hover:text-gray-900"
                        >
                            Forgot your password?
                        </Link>

                        <PrimaryButton
                            class="ml-4 bg-red-600 border-none"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Masuk
                        </PrimaryButton>
                    </div>
                </form>
                <br />
                <hr class="border-gray-300" />
                <Link
                    href="/register"
                    as="button"
                    class="mt-5 bg-red-600 text-white w-full py-2 rounded hover:bg-gray-700 transition duration-150 ease-in-out active:bg-gray-900"
                >
                    Tidak Punya Akun?
                </Link>
            </GuestLayout>
        </div>
    </div>
</template>
<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/inertia-vue3";
import { computed } from "vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
    alert: String,
});
const page = usePage();
const user = computed(() => page.props.auth.user);
const title = "Login";
const form = useForm({
    username: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>
