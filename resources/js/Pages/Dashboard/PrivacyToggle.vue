<script>
import { Link, router } from '@inertiajs/vue3';

export default {
    data () {
        return {
            isPublic : 0,
            scaleTimer: null,
            bump: false,
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
            window.clearTimeout(this.scaleTimer);
            this.bump = false;
            setTimeout(() => {
                this.bump = true;
            }, 1);
            this.scaleTimer = setTimeout(() => {
                this.bump = false;
            }, 100)
        }
    }
}

</script>

<template>
    <audio v-if="bump&&$global.settings.sound==1" autoplay>
        <source src="../../../../public/assets/sounds/pop.mp3" type="audio/mpeg">
    </audio>
    <div>
        <button @click="togglePrivacy"
                :class="{ 'bg-purple-700 hover:bg-purple-800': isPublic, 'bg-gray-500 hover:bg-gray-400': !isPublic, 'scale-up2' : bump }"
                class="text-white font-bold h-10 w-60 rounded focus:outline-none transition-all duration-150">
            <span v-if="!isPublic">🔒{{ $global.lang.editQuiz.private }}</span>
            <span v-else>🌐{{ $global.lang.editQuiz.public }}</span>
        </button>
    </div>
</template>