<script>
import { Link, useForm } from '@inertiajs/vue3';

export default {
    data () {
        return {
            form : useForm({
                email: '',
                password: '',
            })
        }
    },
    methods: {
        submit() {
            this.form.post(route('_login'))
        }
    },
    components: {
        Link
    }
}
</script>

<template>
    <div class="absolute w-full h-full">
        <div class="flex flex-col items-center justify-center p-4 w-full h-full">
            <p class="text-6xl text-white font-bold select-none">
                <Link href='/'>
                    Quizchef
                </Link>
            </p>
            <form @submit.prevent="submit" class="flex flex-col border-2 rounded-lg w-5/12 bg-light p-10 mt-8">
                <label for="email" class="text-white">
                    E-Mail
                </label>
                <input id="email" v-model="form.email" class="bg-light text-white border-white border-2 rounded-lg"/>
                <label for="password" class="mt-6 text-white">
                    Password
                </label>
                <input type="password" id="password" v-model="form.password" class="bg-light text-white border-white border-2 rounded-lg"/>
                <div class="mt-10 flex flex-row items-center justify-between">
                    <p class="text-white">Forgot password?</p>
                    <button type="submit" class="bg-green-500 w-44 h-10 text-white border-2 rounded-lg">
                        Log In
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>