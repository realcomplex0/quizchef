<script>
import { Link, router } from '@inertiajs/vue3';
import Modal from '../../Components/Modal.vue';
import DeleteButton from '@/Components/DeleteButton.vue';
import ImageUpload from './ImageUpload.vue';
import TimerDropdown from './TimerDropdown.vue'
import PrivacyToggle from './PrivacyToggle.vue'
import SettingsModal from '@/Components/SettingsModal.vue';

export default {
    data () {
        return {
            currentQuiz: {
                plays: 0,
                title: this.$global.lang.defaultQuiz.title,
                favorite: false,
                public: 0,
                questions : [
                    {
                        title: `${this.$global.lang.defaultQuiz.question} 1`,
                        timer: 30,
                        options: [
                            {title: this.$global.lang.defaultQuiz.optionA, correct: 0},
                            {title: this.$global.lang.defaultQuiz.optionB, correct: 1},
                            {title: this.$global.lang.defaultQuiz.optionC, correct: 1},
                            {title: this.$global.lang.defaultQuiz.optionD, correct: 1}
                        ]
                    }
                ]
            },
            defaultQuestions: true,
            selectedQuestion: 0,
            selectedOption: -1,
            editing: false,
            curImg: 'test',
            isEditingQuestionTitle: false,
            deleteSelectedQuestionConfirm: false,
            settingsOpen: false,
            isSaving: false,
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
                            title: `${this.$global.lang.defaultQuiz.question} 1`,
                            timer: 30,
                            options: [
                                {title: this.$global.lang.defaultQuiz.optionA, correct: 0},
                                {title: this.$global.lang.defaultQuiz.optionB, correct: 1},
                                {title: this.$global.lang.defaultQuiz.optionC, correct: 1},
                                {title: this.$global.lang.defaultQuiz.optionD, correct: 1}
                            ],
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
            if (this.saving) return;
            this.saving = true;
            setTimeout(() => {
                this.saving = false;
            }, 1000);
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
            if (this.currentQuiz.questions.length >= 10) return;
            this.currentQuiz.questions.push({
                title: `${this.$global.lang.defaultQuiz.question} ${this.currentQuiz.questions.length + 1}`,
                timer: 30,
                options: [
                    {title: this.$global.lang.defaultQuiz.optionA, correct: 0},
                    {title: this.$global.lang.defaultQuiz.optionB, correct: 1},
                    {title: this.$global.lang.defaultQuiz.optionC, correct: 1},
                    {title: this.$global.lang.defaultQuiz.optionD, correct: 1}
                ]
            })
            this.selectedQuestion = this.currentQuiz.questions.length - 1
            this.saveQuiz()
        },
        newOption() {
            if (this.currentQuiz.questions[this.selectedQuestion].options.length >= 8) return
            this.currentQuiz.questions[this.selectedQuestion].options.push({
                title : this.$global.lang.defaultQuiz.option,
                correct : 0
            })
            this.saveQuiz()
        },
        deleteOption (idx) {
            this.currentQuiz.questions[this.selectedQuestion].options.splice(idx, 1)
            if (this.defaultQuestions){
                this.saveQuiz()
            }
            else{
                router.delete(`/quiz/${this.currentQuiz.id}/option/${idx}`)
            }
        },
        update_time(nTime) { 
            this.currentQuiz.questions[this.selectedQuestion].timer = nTime
            this.saveQuiz()
        },
        save_image(img, img_url) {
            if(this.currentQuiz.id){
                this.currentQuiz.questions[this.selectedQuestion].image_path = img_url
                router.post(`/img/quiz/${this.currentQuiz.id}/question/${this.currentQuiz.questions[this.selectedQuestion].id}`, {
                    'image' : img
                })
            }
            else{
                this.currentQuiz.questions[this.selectedQuestion].image_path = img_url
                router.post('/quiz', this.currentQuiz, {onSuccess: (page) => {
                    this.currentQuiz.questions[this.selectedQuestion].image_path = img_url
                    router.post(`/img/quiz/${this.currentQuiz.id}/question/${this.currentQuiz.questions[this.selectedQuestion].id}`, {
                        'image' : img
                    })
                }})
            }
        },
        delete_image() {
            router.delete(`/img/quiz/${this.currentQuiz.id}/question/${this.currentQuiz.questions[this.selectedQuestion].id}`)
        },
        toggle_privacy() {
            this.currentQuiz.public = !this.currentQuiz.public
            this.saveQuiz()
        },
        leave() {
            this.saving = false;
            this.saveQuiz();
            setTimeout(() => {
                router.get('/dashboard');
            }, 25);
        }
    },
    components: {
        Link, Modal, DeleteButton, ImageUpload, TimerDropdown, PrivacyToggle, SettingsModal,
    }

}
</script>

<template>
    <SettingsModal :isOpen="settingsOpen" @close="settingsOpen=false"/>
    <button @click="settingsOpen=true" class="settings-cog">
        <img class="select-none" src="../../../../public/assets/img/SettingsCog.png" draggable="false">
    </button>
    <!-- Modal for deleting questions -->
    <Modal :isOpen="deleteSelectedQuestionConfirm" @close="deleteSelectedQuestionConfirm=false" :height="'9rem'" :width="'25%'" class="">
        <div class="absolute w-full h-12 text-white border-b text-nowrap"> 
            <p class="text-mid text-l">{{$global.lang.editQuiz.delete}} {{ selectedQuestion+1 }}?</p>
        </div>
        <div class="absolute flex w-full h-12 bottom-6 justify-around">
            <button @click="deleteSelectedQuestion" class="btn-red text-white border-2 p-3 w-1/3">
                {{$global.lang.dashboard.delete}}
            </button>
            <button @click="deleteSelectedQuestionConfirm=false" class="btn-blue text-white border-2 p-3 w-1/3">
                {{$global.lang.dashboard.cancel}}
            </button>
        </div>
    </Modal>

    <div class="absolute w-full h-full">
        <div class="flex flex-col w-full h-full">
            <div class="p-6 bg-light flex space-between items-center justify-between border-b-2 border-white">
                <button class="text-5xl text-white font-bold select-none" @click="leave">
                    <p>QuizChef</p>
                </button>
                <div class="flex flex-row justify-center items-center w-full select-none">
                    <p v-if="!editing" @click="editTitle" class="text-white text-4xl select-none">
                        {{currentQuiz.title}}
                    </p>
                    <input v-else v-model="currentQuiz.title" ref="input" @keyup.enter="saveTitle" @blur="saveTitle" class="bg-gray-700 text-white text-4xl h-10 select-none">
                </div>
                <PrivacyToggle @toggle="toggle_privacy" :public_quiz="currentQuiz.public"/>
            </div>
            <div class="flex flex-row">
                <div class="w-1/6 text-3xl border-r-2 flex flex-col items-center">
                    <div class="max-h-[60vh] flex flex-col overflow-y-auto h-full overflow-x-hidden">
                        <div v-for="(question, index) in currentQuiz.questions" 
                            :key="index"
                            class="h-20"
                        > 
                            <button class="bg-light hover:bg-gray-400 text-white font-bold border-2 m-4 w-16 h-16 rounded select-none transition-all" style="transition-duration: 250ms;" :class="{ '!bg-white text-light pointer-events-none scale-up2' : selectedQuestion==index}" @click="handleSelect(index)"> {{ index + 1 }}</button>
                        </div>
                    </div>
                    <div v-if="currentQuiz.questions.length < 10" class="h-20">
                        <button class="btn-green border-2 text-white font-bold text-5xl m-4 w-16 h-16 rounded select-none transition scale-up " @click="addQuestion">
                            <p class="pb-1">+</p>
                        </button>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-normal w-full max-h-[70vh] overflow-y-auto m-8">
                    <p v-if="!isEditingQuestionTitle" @click="editQuestionTitle"
                    class="text-white-500 p-4 text-white text-4xl select-none"> 
                        {{currentQuiz.questions[selectedQuestion].title}} 
                    </p>
                    <input v-else v-model="currentQuiz.questions[selectedQuestion].title" ref="input" @keyup.enter="saveQuestionTitle" @blur="saveQuestionTitle" 
                        class="bg-gray-700 m-4 text-white text-4xl h-10 select-none"/>
                    <TimerDropdown @update="update_time" :time_limit="currentQuiz.questions[selectedQuestion].timer"/>
                    <ImageUpload :imgUrl="currentQuiz.questions[selectedQuestion].image_path" @img-save="save_image" @img-delete="delete_image" class="p-4"/>
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
                            <DeleteButton @clicked="deleteOption(option.id)" class="mr-4"/>
                        </div>
                        <button v-if="currentQuiz.questions[selectedQuestion].options.length<8" @click="newOption" class="w-80 h-16 m-2 btn-green rounded-xl text-white border-2">
                            {{$global.lang.editQuiz.newOption}}
                        </button>
                    </div>
                </div>
                <div>
                    <button @click="deleteSelectedQuestionAsk" class="mt-16 mr-8 btn-red w-48 h-12 text-white border-2">
                        {{$global.lang.editQuiz.delete}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.prevent-shift {
    width: calc(100vw - 100%);
}

</style>