<script>
import { Link, useForm, router } from '@inertiajs/vue3';
import QuizBar from './QuizBar.vue';
import Modal from '../../Components/Modal.vue';
import DeleteButton from '@/Components/DeleteButton.vue';

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
            deleteQuizId: -1,
            searchQuery : '',
            searching: false
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
        }
    },
    computed: {
        deletingQuizName(){
            for (let i in this.currentQuizzes){
                if (this.currentQuizzes[i].id == this.deleteQuizId) return this.currentQuizzes[i].title;
            }
            return "not found :(";
        },
        filteredList() {
            return this.currentQuizzes.filter(quiz => quiz.title.toLowerCase().includes(this.searchQuery.toLowerCase()))
        }
    },
    components: {
        Link, QuizBar, DeleteButton, Modal
    }

}
</script>

<template>
    <!-- modal for deleting quiz -->
    <Modal :isOpen="deleteQuizConfirm" @close="deleteQuizConfirm=false" :height="'9rem'" :width="'25%'" class="">
        <div class="absolute w-full h-12 text-white border-b text-nowrap"> 
            <p class="text-mid text-l">Delete Quiz "{{ deletingQuizName }}"?</p>
        </div>
        <div class="absolute flex w-full h-12 bottom-6 justify-around">
            <button @click="removeQuiz(deleteQuizId)" class="btn-red text-white border-2 p-3 w-1/3">
                Delete
            </button>
            <button @click="disarmDelete" class="btn-blue text-white border-2 p-3 w-1/3">
                Cancel
            </button>
        </div>
    </Modal>

    <div class="absolute w-full h-full">
        <div class="flex flex-col w-full h-full">
            <div class="p-6 bg-light flex space-between items-center justify-between border-b-2 border-white">
                <p class="text-5xl text-white font-bold select-none">
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
                        class="ml-4 btn-red text-white border-2 p-3"
                        >
                        Log Out
                    </Link>
                </div>
            </div>
            <div class="p-4 flex flex-col h-full">
                <div class="p-4 flex flex-row items-center border-b-2 border-gray-500">
                    <p class="text-xl text-white">My quizzes</p>
                    <div class="pl-7">
                        <p v-if="!searching && !searchQuery" class="cursor-pointer text-white text-xl " @click="startSearch">🔎 Search </p>
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
                            New Quiz
                        </Link>
                    </p>
                </div>
                <div class="overflow-y-auto max-h-[70%]">
                    <QuizBar @remove-quiz="removeQuizAsk" v-for="quiz in filteredList" :key="quiz.id" :quiz="quiz"/>
                </div>
            </div>
        </div>
    </div>
</template>