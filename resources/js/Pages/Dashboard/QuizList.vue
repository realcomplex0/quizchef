<script>
import { Link, useForm } from '@inertiajs/vue3';
import QuizBar from './QuizBar.vue';

export default {
    props : {
        quizzes : {
            type: Object
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
            <div class="p-6 flex space-between items-center justify-between">
                <p class="text-5xl text-white font-bold select-none">
                    <Link href='/'>
                        QuizChef
                    </Link>
                </p>
                <div class="flex flex-row items-center">
                    <p class="text-red-700">
                        {{ $page.props.auth.user.username }}
                    </p>
                    <button type="submit" class="ml-4 btn-blue w-16 h-10 text-white border-2">
                        <Link 
                            :href="route('logout')" 
                            method="post"
                            >
                            Log Out
                        </Link>
                        
                    </button>
                </div>
            </div>
            <div class="p-6 flex flex-col bg-light h-full border-t-2 border-white">
                <div class="flex flex-row items-center">
                    <p class="text-xl text-white">My quizzes</p>
                    <div class="pl-7">
                        <p class="text-white text-xl ">Search _____________________ </p>
                    </div>
                </div>
                <div class="overflow-y-auto max-h-[500px]">
                    <QuizBar v-for="quiz in quizzes" :key="quiz.id" :quiz="quiz"/>
                </div>
            </div>
        </div>
    </div>
</template>