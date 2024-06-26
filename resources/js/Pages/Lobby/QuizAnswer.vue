<template>
    <div class="w-full h-full absolute">
        <div class="absolute w-3/4 h-1/3 left-[12.5%] text-white" :class="{ '!h-1/6 ' : question.image_path != null}">
            <p class="text-4xl text-mid">{{ question.title }}</p>
        </div>
        <div v-if="question.image_path != null" class="absolute h-1/2 top-[16.66%] left-1/2" style="transform: translateX(-50%)">
            <img  :src="question.image_path" alt=":(" class="" style="max-height:95%;width:auto">
        </div>
        <div class="grid grid-cols-2 absolute gap-8 w-3/4 h-2/3 left-[12.5%] bottom-0" :class="{ '!h-1/3' : question.image_path != null, '!gap-4' : (options.length > 6)}">
            <div v-for="(option, index) in options" :key="index" class="" >
                <button class="w-full h-full border-2" :class="{ 'btn-blue' : (optionsAnswer==null), 'btn-green-nh' : (optionsAnswer != null && optionsAnswer[index]), 'btn-red-nh' : (optionsAnswer != null && !optionsAnswer[index])}" @click="chooseOption(index)">
                    <p class="text-center text-2xl" :class="{'text-black' : (index==chosen)}" > {{ option }} </p>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props : {
        question : {
            default: null,
        },
        options : {
            default: null,
        },
        optionsAnswer : {
            default: null,
        }
    },

    data(){
        return {
            chosen: -1,
        }
    },

    methods: {
        chooseOption(index){
            if (this.chosen == -1) this.chosen = index;
            this.$emit('selected', index);
        }
    },

}
</script>

<style>

</style>