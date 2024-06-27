<script>
import { Link, useForm, router } from '@inertiajs/vue3';
import SettingsModal from '@/Components/SettingsModal.vue';
export default {
    props : {
        
    },
    data () { 
        return {
            
        }
    },
    mounted () {
        
    },
    methods: {
        
    },
    components: {
        Link, SettingsModal
    }

}
</script>

<template>
    <button @click="settingsOpen=true" class="fixed bottom-4 left-4 h-16 w-16 z-10">
        <img class="select-none" src="../../../../public/assets/img/SettingsCog.png" draggable="false">
    </button>
    <SettingsModal :isOpen="settingsOpen" @close="settingsOpen=false"/>
    <!-- modal for deleting quiz -->
    

    <div class="absolute w-full h-full">
        <div class="flex flex-col w-full h-full">
            <div class="p-4 bg-light flex space-between items-center justify-between border-b-2 border-white">
                <p class="pl-4 text-3xl text-white font-bold select-none">
                    <Link href='/'>
                        QuizChef
                    </Link>
                </p>
                <div class="flex flex-row items-center">
                    <p class="text-white underline">
                        {{ $page.props.auth.user.username }}
                    </p>
                    <Link 
                        :href="route('logout')" 
                        method="post"
                        as="button"
                        class="ml-4 btn-red text-white border-2 p-1"
                        >
                        {{$global.lang.dashboard.logout}}
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>