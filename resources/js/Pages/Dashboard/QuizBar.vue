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
            <p class="text-white text-lg"> {{quiz.questions_count}} questions</p>
            <p class="text-white text-lg"> {{quiz.plays}} plays</p>
        </div>
        <div class="flex flex-row items-center">
            <button @click="startGame" class="mr-4 btn-green w-20 h-10 text-white border-2">
                Play
            </button>
            <Link
                :href="route('quiz.view', { id : quiz.id })">
                <button class="mr-4 btn-blue w-20 h-10 text-white border-2">
                    Edit
                </button>
            </Link>
            <button @click="deleteQuiz" class="mr-4 btn-red w-20 h-10 text-white border-2">
                Delete
            </button>
            <button @click="toggleFavorite">
                <img v-if="quiz.favorite" class="select-none w-4 h-4 mr-4" src="../../../../public/assets/img/ActiveFavorite.png" draggable="false">
                <img v-else class="select-none w-4 h-4 mr-4" src="../../../../public/assets/img/InactiveFavorite.png" draggable="false" />
            </button>
        </div>
    </div>
</template>