<script>

import { router, Link } from '@inertiajs/vue3'

export default {
    props: {
        quiz: {
            type: Object
        }
    },
    methods: {
        startGame() {
            router.post('/create-lobby', {
                id : this.quiz.id
            })
        },
        deleteQuiz() {
            this.$emit('remove-quiz', this.quiz.id)
        },
        toggleFavorite() {
            this.$emit('toggle-favorite', this.quiz.id)
        }
    },
    components: {
        Link
    }
}
</script>

<template>
    <div class="bg-light flex flex-row items-center justify-between border-2 border-white rounded-lg mb-4 ml-28 mr-28">
        <p class="p-6 text-yellow-500 text-lg w-64"> {{ quiz.title }}</p>
        <div>
            <p class="text-white text-lg"> {{quiz.questions_count}} {{ $global.lang.dashboard.questions }}</p>
            <p class="text-white text-lg"> {{quiz.plays}} {{ $global.lang.dashboard.plays }}</p>
        </div>
        <div class="flex flex-row items-center">
            <button @click="startGame" class="mr-4 btn-green w-20 h-10 text-white border-2">
                {{ $global.lang.dashboard.play }}
            </button>
        </div>
    </div>
</template>