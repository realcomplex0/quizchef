<script>
import { Link, router } from '@inertiajs/vue3';

export default {
    data () {
        return {
            currentQuiz: {
                plays: 0,
                title: 'MyQuiz',
                questions : [
                    {
                        title: 'Question 0',
                        options: [
                            {title: 'Alpha'},
                            {title: 'Beta'},
                            {title: 'Omega'}
                        ]
                    }
                ]
            },
            selectedQuestion: 0,
            editing: false,
            isEditingQuestionTitle: false,
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
                    this.currentQuiz.questions = [
                        {
                            title: 'Question 0',
                            options: [
                                {title: 'Alpha', editing: false},
                                {title: 'Beta', editing: false},
                                {title: 'Omega', editing: false}
                            ]
                        }
                    ]
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
            this.editOption = index
            this.$nextTick(() => {
                this.$refs.optionInput.focus();
                this.$refs.optionInput.select();
            })
        },
        saveQuiz () {
            if (this.currentQuiz.id){
                router.post(`/new-quiz/${this.currentQuiz.id}`, this.currentQuiz)
            }
            else{
                router.post('/new-quiz', this.currentQuiz)
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
            if(this.editOption == -1) return
            this.editOption = -1
            this.saveQuiz()
        },
        handleSelect(id) {
            this.selectedQuestion = id;
        },
        deleteSelectedQuestion () {
            router.delete(`/new-quiz/${this.currentQuiz.id}/${this.currentQuiz.questions[this.selectedQuestion].id}`)
        },
        addQuestion() {
            this.currentQuiz.questions.push({
                title: `Question ${this.currentQuiz.questions.length}`,
                options: [
                    {title: 'Alpha'},
                    {title: 'Beta'},
                    {title: 'Omega'},
                    {title: 'Omicron'}
                ]
            })
            this.selectedQuestion = this.currentQuiz.questions.length - 1
            this.saveQuiz()
        }
    },
    components: {
        Link
    }

}
</script>

<template>
    <div class="absolute w-full h-full">
        <div class="flex flex-col w-full h-full">
            <div class="p-6 bg-light flex space-between items-center justify-between">
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
                <div class="w-1/6 text-3xl border-r-2 max-h-[70vh] flex flex-col items-center overflow-y-auto overflow-x-hidden">
                    <div v-for="(question, index) in currentQuiz.questions" 
                        :key="index"
                        class="h-20"
                    > 
                        <button v-if="selectedQuestion!=index" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold m-4 w-16 h-16 rounded select-none" @click="handleSelect(index)"> {{ index }}</button>
                        <button v-else class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold m-4 w-16 h-16 border-8 border-orange-500 rounded select-none" @click="handleSelect(index)"> {{ index }}</button>
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
                            class="w-80 h-16 m-4 flex items-center justify-center bg-gray-700 border-2 rounded-xl text-white"
                        >
                            <p v-if="editOption!=index" @click="selectOption(index)">
                                {{ option.title }}
                            </p>
                            <input v-else v-model="option.title" ref="optionInput" @keyup.enter="saveOption" @blur="saveOption" class="text-blue-600"/>
                            
                        </div>
                        <div class="w-80 h-16 m-4 flex items-center justify-center bg-green-700 border-2 rounded-xl text-white">
                            + New option
                        </div>
                    </div>
                </div>
                <div>
                    <button @click="deleteSelectedQuestion" class="mt-16 mr-8 btn-red w-48 h-10 text-white border-2">
                        Delete Question
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>