<script>
import { Link, useForm, router } from '@inertiajs/vue3';
import SettingsModal from '@/Components/SettingsModal.vue';
import PublicQuizBar from './PublicQuizBar.vue';
import DeleteButton from '@/Components/DeleteButton.vue';

export default {
    props : ['quizzes'],
    data () { 
        return {
            settingsOpen: false,
            searching: false,
            searchQuery: ''
        }
    },
    mounted () {
        console.log(this.quizzes)
    },
    computed : {
        orderByPopular() {
            return [...this.quizzes.filter(quiz => quiz.title.toLowerCase().includes(this.searchQuery.toLowerCase()))].sort((x, y) => {
                return y.plays - x.plays
            })
        }
    },
    methods: {
        startSearch() {
            this.searching = true
            this.$nextTick(() => {
                this.$refs.input.focus();
                this.$refs.input.select();
            })
        },
        stopSearch() {
            this.searching = false
        },
        cancelSearch() {
            this.searching = false
            this.searchQuery = ''
        }
    },
    components: {
        Link, SettingsModal, PublicQuizBar, DeleteButton
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
            <div class="flex flex-row items-center">
                <p class="p-5 text-white text-xl">{{ $global.lang.dashboard.popular }}</p>
                <p v-if="!searching && !searchQuery" class="cursor-pointer text-white text-xl " @click="startSearch">🔎 {{ $global.lang.dashboard.search }} </p>
                <input v-else-if="searching" ref="input" v-model="searchQuery" @keyup.enter="stopSearch" @blur="stopSearch"/>
                <div v-else class="flex flex-row justify-center items-center">
                        
                    <p class="text-white text-xl cursor-pointer" @click="startSearch">🔎 {{ searchQuery }}</p>
                    <DeleteButton @clicked="cancelSearch" />
                </div>
            </div>
            <div>
                <PublicQuizBar v-for="quiz in orderByPopular" :key="quiz.id" :quiz="quiz"/>
            </div>
        </div>
    </div>
</template>