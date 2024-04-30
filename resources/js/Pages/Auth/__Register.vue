<script>
import { Link, useForm } from '@inertiajs/vue3';

export default {
    data () {
        return {
            form : useForm({
                email: '',
                username: '',
                password: '',
                pasword_repeat: ''
            })
        }
    },
    methods: {
        submit() {
            this.form.post(route('_register'))
        }
    }
}

</script>

<template>
    <div>
        <div class="flex flex-col items-center justify-center p-4 w-full">
            <p class="text-6xl text-white font-bold select-none">
                QuizChef
            </p>
            <form @submit.prevent="submit" class="flex flex-col border-2 rounded-lg w-5/12 bg-light p-10 mt-8">
                <label for="email" class="text-white">
                    E-Mail
                </label>
                <input id="email" v-model="form.email" class="bg-light text-white border-white border-2 rounded-lg"/>
                <label for="username" class="mt-6 text-white">
                    Username
                </label>
                <input id="username" v-model="form.username" class="bg-light text-white border-white border-2 rounded-lg"/>
                <label for="password" class="mt-6 text-white">
                    Password
                </label>
                <input type="password" id="password" v-model="form.password" class="bg-light text-white border-white border-2 rounded-lg"/>
                <label for="rep-password" class="mt-6 text-white">
                    Repeat password
                </label>
                <input type="password" id="rep-password" v-model="form.pasword_repeat" class="bg-light text-white border-white border-2 rounded-lg"/>
                <div class="mt-10 flex flex-row items-center justify-between">
                    <div class="flex gap-2 items-center">
                        <input id="data" class="bg-light h-7 w-7 border-white border-2 rounded-lg" type="checkbox" />
                        <label for="data" class="text-white"> I agree to share my data with China</label>
                    </div>
                    <button type="submit" class="bg-green-500 w-44 h-10 text-white border-2 rounded-lg">
                        Register
                    </button>
                </div>
            </form>
        </div>

    </div> 
</template>