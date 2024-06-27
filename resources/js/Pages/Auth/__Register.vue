<script>
import { Link, useForm } from '@inertiajs/vue3';
import SettingsModal from '@/Components/SettingsModal.vue';

export default {
    data () {
        return {
            form : useForm({
                email: '',
                username: '',
                password: '',
                password_confirmation: ''
            }),
            settingsOpen: false,
        }
    },
    methods: {
        submit() {
            this.form.post(route('register'))
        }
    },
    components: {
        Link, SettingsModal,
    }
}
</script>

<template>
    <SettingsModal :isOpen="settingsOpen" @close="settingsOpen=false"/>
    <button @click="settingsOpen=true" class="fixed bottom-4 left-4 h-16 w-16 z-10">
        <img class="select-none" src="../../../../public/assets/img/SettingsCog.png" draggable="false">
    </button>

    <div class="absolute w-full h-full">
        <div class="flex flex-col items-center justify-center p-4 w-full h-full">
            <p class="text-6xl text-white font-bold select-none">
                <Link href='/'>
                    Quizchef
                </Link>
            </p>
            <form @submit.prevent="submit" class="flex flex-col border-2 rounded-lg w-5/12 bg-light p-10 mt-8">
                <label for="email" class="text-white">
                    {{ $global.lang.registerPage.email }}
                </label>
                <input id="email" v-model="form.email" class="bg-light text-white border-white border-2 rounded-lg"/>
                <label for="username" class="mt-6 text-white">
                    {{ $global.lang.registerPage.username }}
                </label>
                <input id="username" v-model="form.username" class="bg-light text-white border-white border-2 rounded-lg"/>
                <label for="password" class="mt-6 text-white">
                    {{ $global.lang.registerPage.password }}
                </label>
                <input type="password" id="password" v-model="form.password" class="bg-light text-white border-white border-2 rounded-lg"/>
                <label for="rep-password" class="mt-6 text-white">
                    {{ $global.lang.registerPage.repeatPassword }}
                </label>
                <input type="password" id="rep-password" v-model="form.password_confirmation" class="bg-light text-white border-white border-2 rounded-lg"/>
                <div class="mt-10 flex flex-row items-center justify-between">
                    <div class="flex gap-2 items-center">
                        <input id="data" class="bg-light h-7 w-7 border-white border-2 rounded-lg" type="checkbox" />
                        <label for="data" class="text-white"> {{ $global.lang.registerPage.agree }}</label>
                    </div>
                    <button type="submit" class="btn-green w-44 h-10 text-white border-2">
                        {{ $global.lang.registerPage.register }}
                    </button>
                </div>
            </form>
        </div>

    </div> 
</template>