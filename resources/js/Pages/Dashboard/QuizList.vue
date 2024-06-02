<script>
import { Link, useForm, router } from '@inertiajs/vue3';
import QuizBar from './QuizBar.vue';

export default {
    props : {
        quizzes : {
            type: Object
        }
    },
    data () { 
        return {
            currentQuizzes : []
        }
    },
    mounted () {
        this.currentQuizzes = this.quizzes
    },
    methods: {
        removeQuiz(id){
            this.currentQuizzes = this.currentQuizzes.filter(quiz => quiz.id !== id)
            router.delete(`/quiz/${id}`)
        }
    },
    components: {
        Link, QuizBar
    }

}
</script>

<template>
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
                        class="ml-4 btn-red w-16 h-10 text-white border-2"
                        >
                        Log Out
                    </Link>
                </div>
            </div>
            <div class="p-4 flex flex-col h-full">
                <div class="p-4 flex flex-row items-center border-b-2 border-gray-500">
                    <p class="text-xl text-white">My quizzes</p>
                    <div class="pl-7">
                        <p class="text-white text-xl ">Search _____________________ </p>
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
                    <QuizBar @remove-quiz="removeQuiz" v-for="quiz in currentQuizzes" :key="quiz.id" :quiz="quiz"/>
                </div>
            </div>
        </div>
    </div>
</template>