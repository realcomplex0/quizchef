<template>
    <div class="w-full h-full absolute">
        <!-- question title -->
        <div class="absolute w-3/4 h-1/3 left-[12.5%] text-white" :class="{ '!h-1/6 ' : questionData.currentQuestion.image_path != null}">
            <p class="text-4xl text-mid select-none">{{ questionData.currentQuestion.title }}</p>
        </div>
        <!-- image -->
        <div v-if="questionData.currentQuestion.image_path != null" class="absolute h-1/2 top-[16.66%] left-1/2" style="transform: translateX(-50%)">
            <img :src="questionData.currentQuestion.image_path" alt=":(" class="" style="max-height:95%;width:auto">
        </div>
        <!-- answers -->
        <div class="grid grid-cols-2 absolute gap-8 w-3/4 h-2/3 left-[12.5%] bottom-0" :class="{ '!h-1/3' : questionData.currentQuestion.image_path != null, '!gap-4' : (questionData.currentOptions.length > 6)}">
            <div v-for="(option, index) in questionData.currentOptions" :key="index" class="" >
                <button class="w-full h-full border-2 select-none" :class="{ 'btn-blue' : (questionData.optionsAnswer==null), 'btn-green-nh' : (questionData.optionsAnswer != null && questionData.optionsAnswer[index]), 'btn-red-nh' : (questionData.optionsAnswer != null && !questionData.optionsAnswer[index])}" @click="chooseOption(index)">
                    <p class="text-center text-2xl" :class="{'text-black' : (index==highlightedIndex)}" > {{ option }} </p>
                </button>
            </div>
        </div>
        <!-- timer and player counts -->
        <div class="absolute w-[12.5%] top-[8.33%] h-1/6 right-0  flex flex-col justify-around items-center">
            <p class="text-white text-7xl select-none transition-all" :class="{'!text-red-500' : (questionData.timeRemaining <= 5), 'scale-up3' : timerBump==true}">{{ questionData.timeRemaining }}</p>
            <p class="text-white select-none">{{ questionData.answerCount }}/{{ questionData.playerCount }} {{$global.lang.game.players}} {{$global.lang.game.answered}} </p>
        </div>
        <!-- answer confirmation -->
        <div v-if="questionData.confirmedAnswer!=null && questionData.optionsAnswer==null" class="fixed w-full h-[10%] bottom-0 text-white text-response">
            <p class="text-mid">{{$global.lang.game.notification}}</p>
        </div>
        <div v-if="questionData.optionsAnswer!=null && !is_host" class="fixed w-full h-[10%] bottom-0 text-white text-response">
            <p class="text-mid">{{ $global.lang.game.host_wait }}</p>
        </div>
        <audio v-if="soundBump&&$global.settings.sound==1" autoplay hidden>
            <source src="../../../../public/assets/sounds/warning_beep.mp3" type="audio/mpeg">
        </audio>
    </div>
    
</template>

<script>
export default {
    props : {
        questionData : {
            default: {
                currentQuestion: null,
                currentOptions : null,
                optionsAnswer : null,
                confirmedAnswer : null,
                timeRemaining: null,
                playerCount: 0,
                answerCount: 0,
            },
        },
        is_host : {
            default : false,
        }
    },

    data(){
        return {
            timerBump: false,
            prevTime: -1,
            soundBump: false,
        }
    },

    methods: {
        chooseOption(index){
            this.$emit('selected', index);
        }
    },

    computed: {
        highlightedIndex(){
            if (this.questionData.confirmedAnswer != null) return this.questionData.confirmedAnswer;
        }
    },
    watch: {
        questionData : {
            handler(newVal, oldVal){
                if (newVal.timeRemaining != this.prevTime && newVal.timeRemaining <= 5 && newVal.timeRemaining >= 1){
                    this.timerBump = true;
                    setTimeout(() => {
                        this.timerBump = false;
                    }, 150);
                    this.soundBump = true;
                    setTimeout(() => {
                        this.soundBump = false;
                    }, 800);
                }
                this.prevTime = newVal.timeRemaining;
            },
            deep: true,
        }
    }
}
</script>

<style>

</style>