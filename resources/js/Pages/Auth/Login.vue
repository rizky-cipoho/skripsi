<template>
    <GuestLayout :name="title">
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="username" value="Username" />
                <TextInput id="username" type="text" class="mt-1 block w-full" v-model="form.username" required autofocus autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.username" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Forgot your password?
                </Link>

                <PrimaryButton class="ml-4 bg-red-600 border-none" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Masuk
                </PrimaryButton>
            </div>
        </form>
        <br>
        <hr class="border-gray-300">
        <Link href="/register" as="button" class="mt-5 bg-red-600 text-white w-full py-2 rounded hover:bg-gray-700 transition duration-150 ease-in-out active:bg-gray-900">
            Tidak Punya Akun?
        </Link>
    </GuestLayout>
</template>
<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});
const title = "Login"
const form = useForm({
    username: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>