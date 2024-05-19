<script>

import { router } from '@inertiajs/vue3'

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
        }
    }
}
</script>

<template>
    <div class="bg-light flex flex-row items-center justify-between border-2 border-white rounded-lg m-10">
        <p class="p-8 text-yellow-500 text-xl w-64"> {{ quiz.title }}</p>
        <div>
            <p class="text-white text-xl"> {{quiz.questions}} questions</p>
            <p class="text-white text-xl"> {{quiz.plays}} plays</p>
        </div>
        <div>
            <button @click="startGame" class="mr-4 btn-green w-20 h-10 text-white border-2">
                Play
            </button>
            <button type="submit" class="mr-8 btn-red w-20 h-10 text-white border-2">
                Delete
            </button>
        </div>
        
    </div>
</template>