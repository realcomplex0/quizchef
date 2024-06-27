<script>
import { Link, useForm, router } from '@inertiajs/vue3';
import QuizBar from './QuizBar.vue';
import Modal from '../../Components/Modal.vue';
import DeleteButton from '@/Components/DeleteButton.vue';
import SettingsModal from '@/Components/SettingsModal.vue';

export default {
    props : {
        quizzes : {
            type: Object
        }
    },
    data () { 
        return {
            currentQuizzes : [],
            deleteQuizConfirm: false,
            toggleFavorite: false,
            toggleFavoriteQuizId: -1,
            deleteQuizId: -1,
            searchQuery : '',
            searching: false,
            settingsOpen: false,
        }
    },
    mounted () {
        this.currentQuizzes = this.quizzes
    },
    methods: {
        removeQuiz(id){
            if (id != -1) {
                this.currentQuizzes = this.currentQuizzes.filter(quiz => quiz.id !== id)
                router.delete(`/quiz/${id}`)
            }
            this.disarmDelete();
        },
        removeQuizAsk(id){
            this.deleteQuizConfirm = true;
            this.deleteQuizId = id;
        },
        disarmDelete(){
            this.deleteQuizConfirm = false;
            this.deleteQuizId = -1;
        },
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
        },
        disarmToggleFavorite() {
            this.toggleFavoriteQuizId = -1
            this.toggleFavorite = false
        },
        onToggleFavorite(id) {
            this.toggleFavoriteQuizId = id
            this.toggleFavorite = true
            // let favorite_value = null
            // this.currentQuizzes = this.currentQuizzes.map(quiz => {
            //     if(quiz.id == id){
            //         favorite_value = !quiz.favorite
            //         return {...quiz, 'favorite' : !quiz.favorite}
            //     }
            //     else{
            //         return quiz
            //     }
            // })
            
            // router.patch(`/quiz/${id}`, {'favorite' : favorite_value})
        },
        triggerToggleFavorite(){
            let favorite_value = null
            this.currentQuizzes = this.currentQuizzes.map(quiz => {
                if(quiz.id == this.toggleFavoriteQuizId){
                    favorite_value = !quiz.favorite
                    return {...quiz, 'favorite' : !quiz.favorite}
                }
                else{
                    return quiz
                }
            })
            
            router.patch(`/quiz/${this.toggleFavoriteQuizId}`, {'favorite' : favorite_value})
            this.disarmToggleFavorite()
        }
    },
    computed: {
        deletingQuizName(){
            return this.currentQuizzes.find(quiz => quiz.id == this.deleteQuizId).title
        },
        toggleFavoriteQuizName() {
            return this.currentQuizzes.find(quiz => quiz.id == this.toggleFavoriteQuizId).title
        },
        toggleFavoriteOperation() {
            if(this.currentQuizzes.find(quiz => quiz.id == this.toggleFavoriteQuizId).favorite)
                return this.$global.lang.dashboard.removeFavorite
            else
                return this.$global.lang.dashboard.addFavorite
        },
        filteredList() {
            return this.currentQuizzes.filter(quiz => quiz.title.toLowerCase().includes(this.searchQuery.toLowerCase()))
        },
        favoriteList() {
            return this.currentQuizzes.filter(quiz => quiz.favorite && quiz.title.toLowerCase().includes(this.searchQuery.toLowerCase()))
        },
        recentList() {
            return [...this.currentQuizzes.filter(quiz => quiz.title.toLowerCase().includes(this.searchQuery.toLowerCase()))].sort((x, y) => {
                return new Date(y.updated_at) - new Date(x.updated_at)
            })
        },
    },
    components: {
        Link, QuizBar, DeleteButton, Modal, SettingsModal,
    }

}
</script>

<template>
    <button @click="settingsOpen=true" class="fixed bottom-4 left-4 h-16 w-16 z-10">
        <img class="select-none" src="../../../../public/assets/img/SettingsCog.png" draggable="false">
    </button>
    <SettingsModal :isOpen="settingsOpen" @close="settingsOpen=false"/>
    <!-- modal for deleting quiz -->
    <Modal :isOpen="deleteQuizConfirm" @close="deleteQuizConfirm=false" :height="'9rem'" :width="'25%'" class="">
        <div class="absolute w-full h-12 text-white border-b text-nowrap"> 
            <p class="text-mid text-l">{{ $global.lang.dashboard.deleteQuiz }} "{{ deletingQuizName }}"?</p>
        </div>
        <div class="absolute flex w-full h-12 bottom-6 justify-around">
            <button @click="removeQuiz(deleteQuizId)" class="btn-red text-white border-2 p-3 w-1/3">
                {{ $global.lang.dashboard.delete }}
            </button>
            <button @click="disarmDelete" class="btn-blue text-white border-2 p-3 w-1/3">
                {{ $global.lang.dashboard.cancel }}
            </button>
        </div>
    </Modal>

    <Modal :isOpen="toggleFavorite" @close="disarmToggleFavorite" :height="'9rem'" :width="'25%'">
        <div class="absolute w-full h-12 text-white border-b text-nowrap"> 
            <p class="text-mid text-l"> {{toggleFavoriteOperation}} {{toggleFavoriteQuizName}}?</p>
        </div>
        <div class="absolute flex w-full h-12 bottom-6 justify-around">
            <button @click="triggerToggleFavorite" class="btn-green text-white border-2 p-3 w-1/3">
                {{ $global.lang.dashboard.confirm }}
            </button>
            <button @click="disarmToggleFavorite" class="btn-blue text-white border-2 p-3 w-1/3">
                {{ $global.lang.dashboard.cancel }}
            </button>
        </div>
    </Modal>

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
            <div class="p-2 flex flex-col h-full">
                <div class="p-3 flex flex-row items-center border-b-2 border-gray-500">
                    <p class="text-xl text-white">{{ $global.lang.dashboard.myQuizzes }}</p>
                    <div class="pl-7">
                        <p v-if="!searching && !searchQuery" class="cursor-pointer text-white text-xl " @click="startSearch">🔎 {{ $global.lang.dashboard.search }} </p>
                        <input v-else-if="searching" ref="input" v-model="searchQuery" @keyup.enter="stopSearch" @blur="stopSearch"/>
                        <div v-else class="flex flex-row justify-center items-center">
                            
                            <p class="text-white text-xl cursor-pointer" @click="startSearch">🔎 {{ searchQuery }}</p>
                            <DeleteButton @clicked="cancelSearch" />
                            <!-- <button @click="cancelSearch" class="ml-4 inline-flex items-center justify-center w-6 h-6 bg-gray-200 rounded-full hover:bg-gray-500 focus:outline-none select-none">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button> -->
                        </div>
                        
                    </div>
                    <p class="pl-7 text-white text-xl">
                        <Link 
                            :href="route('quiz.view')" 
                        >
                            {{ $global.lang.dashboard.newQuiz }}
                        </Link>
                    </p>
                    <div class="pl-7 text-white text-xl">
                        <p> {{ $global.lang.dashboard.browse }}</p>
                    </div>
                </div>

                <p class="text-yellow-500 p-4 text-xl">{{ $global.lang.dashboard.favorites }}</p>
                <div class="overflow-y-auto max-h-[25vh]">
                    <QuizBar @toggle-favorite="onToggleFavorite" @remove-quiz="removeQuizAsk" v-for="quiz in favoriteList" :key="quiz.id" :quiz="quiz"/>
                </div>
                <div>
                <p class="text-yellow-500 p-4 text-xl">{{ $global.lang.dashboard.recents }}</p>
                </div>
                <div class="overflow-y-auto max-h-[40vh]">
                    <QuizBar @toggle-favorite="onToggleFavorite" @remove-quiz="removeQuizAsk" v-for="quiz in recentList" :key="quiz.id" :quiz="quiz"/>
                </div>
            </div>
        </div>
    </div>
</template>