<script>
import { Link, router } from '@inertiajs/vue3';

export default {
    data () {
        return {
            isPublic : 0
        }
    },
    props : ['public_quiz'],
    mounted () {
        this.isPublic = this.public_quiz
    },
    watch : {
        public_quiz(newValue){
            this.isPublic = newValue
        }
    },
    methods : {
        togglePrivacy () {
            this.isPublic = !this.isPublic
            this.$emit('toggle')
        }
    }
}

</script>

<template>
    <div>
        <button @click="togglePrivacy"
                :class="{ 'bg-blue-700 hover:bg-blue-800': isPublic, 'bg-gray-500 hover:bg-gray-400': !isPublic }"
                class="text-white font-bold h-10 w-60  rounded focus:outline-none transition-colors duration-300">
            <span v-if="!isPublic">🔒Your Quiz Is private</span>
            <span v-else="isPublic">🌐Your Quiz is public</span>
        </button>
    </div>
</template>