<script>
import { Link, router } from '@inertiajs/vue3';
import Modal from '../../Components/Modal.vue';

export default {
    data () {
        return {
            currentQuiz: {
                plays: 0,
                title: 'Untitled Quiz',
                questions : [
                    {
                        title: 'Question 1',
                        options: [
                            {title: 'Alpha', correct: 0},
                            {title: 'Beta', correct: 1},
                            {title: 'Omega', correct: 1}
                        ]
                    }
                ]
            },
            defaultQuestions: true,
            selectedQuestion: 0,
            selectedOption: -1,
            editing: false,
            isEditingQuestionTitle: false,
            deleteSelectedQuestionConfirm: false,
        }
    },
    props : {
        quiz : {
            type: Object
        }
    },
    watch : {
        quiz(newQuiz) {
            this.localQuiz(newQuiz)
        }
    },
    mounted(){
        this.localQuiz(this.quiz)
    },
    methods: {
        localQuiz(newQuiz) {
            if(newQuiz){
                this.title = newQuiz.title;
                this.currentQuiz = newQuiz;
                if(newQuiz.questions.length == 0){
                    this.defaultQuestions = true
                    this.currentQuiz.questions = [
                        {
                            title: 'Question 1',
                            options: [
                                {title: 'Alpha', correct: 0},
                                {title: 'Beta', correct: 1},
                                {title: 'Omega', correct: 1}
                            ]
                        }
                    ]
                }
                else{
                    this.defaultQuestions = false
                }
            }
            if (this.selectedQuestion >= this.currentQuiz.questions.length){
                this.selectedQuestion = this.currentQuiz.questions.length - 1;
            }
        },
        editTitle() {
            this.editing = true
            this.$nextTick(() => {
                this.$refs.input.focus();
                this.$refs.input.select();
            });
        },
        editQuestionTitle() {
            this.isEditingQuestionTitle = true
            this.$nextTick(() => {
                this.$refs.input.focus();
                this.$refs.input.select();
            })
        },
        selectOption(index) {
            this.selectedOption = index
            this.$nextTick(() => {
                this.$refs.options[0].focus();
                this.$refs.options[0].select();
            })
        },
        saveQuiz () {
            if (this.currentQuiz.id){
                router.post(`/quiz/${this.currentQuiz.id}`, this.currentQuiz)
            }
            else{
                router.post('/quiz', this.currentQuiz)
            }
        },
        saveTitle() {
            if(!this.editing) return;
            this.editing = false
            this.saveQuiz()
        },
        saveQuestionTitle() {
            if(!this.isEditingQuestionTitle) return;
            this.isEditingQuestionTitle = false
            this.saveQuiz() 
        },
        saveOption() {
            if(this.selectedOption === -1) return
            this.selectedOption = -1
            this.saveQuiz()
        },
        handleSelect(id) {
            this.selectedQuestion = id;
        },
        deleteSelectedQuestion () {
            this.deleteSelectedQuestionConfirm = false;
            router.delete(`/quiz/${this.currentQuiz.id}/question/${this.currentQuiz.questions[this.selectedQuestion].id}`)
        },
        deleteSelectedQuestionAsk() {
            this.deleteSelectedQuestionConfirm = true;
        },
        addQuestion() {
            this.currentQuiz.questions.push({
                title: `Question ${this.currentQuiz.questions.length + 1}`,
                options: [
                    {title: 'Alpha', correct: 1},
                    {title: 'Beta', correct: 1},
                    {title: 'Omega', correct: 0},
                    {title: 'Omicron', correct: 1}
                ]
            })
            this.selectedQuestion = this.currentQuiz.questions.length - 1
            this.saveQuiz()
        },
        newOption() {
            if (this.currentQuiz.questions[this.selectedQuestion].options.length >= 8) return
            this.currentQuiz.questions[this.selectedQuestion].options.push({
                title : 'Untitled Answer',
                correct : 0
            })
            this.saveQuiz()
        },
        deleteOption (idx) {
            if (this.defaultQuestions){
                this.currentQuiz.questions[this.selectedQuestion].options.splice(idx, 1)
                this.saveQuiz()
            }
            else{
                router.delete(`/quiz/${this.currentQuiz.id}/option/${idx}`)
            }
        }
    },
    components: {
        Link, Modal
    }

}
</script>

<template>
    <!-- Modal for deleting questions -->
    <Modal :isOpen="deleteSelectedQuestionConfirm" @close="deleteSelectedQuestionConfirm=false" :height="'9rem'" :width="'25%'" class="">
        <div class="absolute w-full h-12 text-white border-b text-nowrap"> 
            <p class="text-mid text-l">Delete Question {{ selectedQuestion+1 }}?</p>
        </div>
        <div class="absolute flex w-full h-12 bottom-6 justify-around">
            <button @click="deleteSelectedQuestion" class="btn-red text-white border-2 p-3 w-1/3">
                Delete
            </button>
            <button @click="deleteSelectedQuestionConfirm=false" class="btn-blue text-white border-2 p-3 w-1/3">
                Cancel
            </button>
        </div>
    </Modal>

    <div class="absolute w-full h-full">
        <div class="flex flex-col w-full h-full">
            <div class="p-6 bg-light flex space-between items-center justify-between border-b-2 border-white">
                <p class="text-5xl text-white font-bold select-none">
                    <Link :href="route('dashboard')">
                        QuizChef
                    </Link>
                </p>
                <div class="flex flex-row justify-center items-center w-full select-none">
                    <p v-if="!editing" @click="editTitle" class="text-white text-4xl select-none">
                        {{currentQuiz.title}}
                    </p>
                    <input v-else v-model="currentQuiz.title" ref="input" @keyup.enter="saveTitle" @blur="saveTitle" class="bg-gray-700 text-white text-4xl h-10 select-none">
                </div>
            </div>
            <div class="flex flex-row">
                <div class="w-1/6 text-3xl border-r-2 flex flex-col items-center">
                    <div class="max-h-[70vh] flex flex-col items-center overflow-y-auto overflow-x-hidden">
                        <div v-for="(question, index) in currentQuiz.questions" 
                            :key="index"
                            class="h-20"
                        > 
                            <button v-if="selectedQuestion!=index" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold m-4 w-16 h-16 rounded select-none" @click="handleSelect(index)"> {{ index + 1 }}</button>
                            <button v-else class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold m-4 w-16 h-16 border-8 border-orange-500 rounded select-none" @click="handleSelect(index)"> {{ index + 1}}</button>
                        </div>
                    </div>
                    <div class="h-20">
                        <button class="bg-green-500 hover:bg-green-600 text-green-700 font-bold text-5xl m-4 w-16 h-16 rounded select-none" @click="addQuestion"> + </button>
                    </div>
                </div>
                <div class="flex pt-12 flex-col items-center justify-normal w-full">
                    <p v-if="!isEditingQuestionTitle" @click="editQuestionTitle"
                    class="text-white-500 p-4 text-white text-4xl select-none"> 
                        {{currentQuiz.questions[selectedQuestion].title}} 
                    </p>
                    <input v-else v-model="currentQuiz.questions[selectedQuestion].title" ref="input" @keyup.enter="saveQuestionTitle" @blur="saveQuestionTitle" 
                        class="bg-gray-700 m-4 text-white text-4xl h-10 select-none"/>
                    <div class="grid grid-cols-2 gap-4">
                        <div 
                            v-for="(option, index) in currentQuiz.questions[selectedQuestion].options" 
                            :key="index"
                            class="w-80 h-16 m-2 flex items-center justify-between bg-gray-700 border-2 rounded-xl text-white"
                        >
                            <input type="checkbox" v-model="option.correct" true-value="1" false-value="0"
                                @change="saveQuiz"
                                class="m-4 rounded-full h-6 w-6 text-green-600 focus:ring-blue-600 border-gray-300" />
                            <p v-if="selectedOption!=index" @click="selectOption(index)">
                                {{ option.title }}
                            </p>
                            <input v-else v-model="option.title" ref="options" @keyup.enter="saveOption" @blur="saveOption" class="text-blue-600"/>
                            <button @click="deleteOption(option.id)" class="m-4 inline-flex items-center justify-center w-6 h-6 bg-gray-200 rounded-full hover:bg-gray-500 focus:outline-none select-none">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <button v-if="currentQuiz.questions[selectedQuestion].options.length<8" @click="newOption" class="w-80 h-16 m-2 btn-green rounded-xl text-white border-2">
                            + New option
                        </button>
                    </div>
                </div>
                <div>
                    <button @click="deleteSelectedQuestionAsk" class="mt-16 mr-8 btn-red w-48 h-12 text-white border-2">
                        Delete question
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>