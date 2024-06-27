<script>
import { Link, useForm } from '@inertiajs/vue3';
import SettingsModal from '@/Components/SettingsModal.vue';

export default {
    data () {
        return {
            form : useForm({
                email: '',
                password: '',
            }),
            settingsOpen: false,
        }
    },
    methods: {
        submit() {
            this.form.post(route('login'))
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
                    {{ $global.lang.loginPage.email }}
                </label>
                <input id="email" v-model="form.email" class="bg-light text-white border-white border-2 rounded-lg"/>
                <label for="password" class="mt-6 text-white">
                    {{ $global.lang.loginPage.password }}
                </label>
                <input type="password" id="password" v-model="form.password" class="bg-light text-white border-white border-2 rounded-lg"/>
                <div class="mt-10 flex flex-row items-center justify-between">
                    <p class="text-white">{{ $global.lang.loginPage.forgotPassword }}</p>
                    <button type="submit" class="btn-green w-44 h-10 text-white border-2">
                        {{ $global.lang.loginPage.login }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>